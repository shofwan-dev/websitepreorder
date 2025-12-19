<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'min_quota' => ['required', 'integer', 'min:1'],
            'specifications' => ['nullable', 'string'],
            'images' => ['nullable', 'array', 'max:4'], // Max 4 images
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'video' => ['nullable', 'file', 'mimes:mp4,mov,avi,wmv', 'max:51200'],
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Phase 1: Enhanced debugging
                \Log::info('Processing image file', [
                    'error' => $image ? $image->getError() : 'null',
                    'size' => $image ? $image->getSize() : 0,
                    'name' => $image ? $image->getClientOriginalName() : 'none',
                    'mime' => $image ? $image->getMimeType() : 'none',
                    'path' => $image ? $image->getPathname() : 'none',
                    'readable' => $image && $image->getPathname() ? is_readable($image->getPathname()) : false
                ]);
                
                // Phase 3: Enhanced validation
                if ($image && 
                    $image->getError() === UPLOAD_ERR_OK && 
                    $image->getSize() > 0 && 
                    $image->getPathname() && 
                    is_readable($image->getPathname())) {
                    try {
                        // Phase 4: Manual file move
                        $extension = $image->getClientOriginalExtension();
                        $filename = \Str::random(40) . '.' . $extension;
                        $destinationPath = storage_path('app/public/products');
                        
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }
                        
                        $fullPath = $destinationPath . '/' . $filename;
                        
                        if (move_uploaded_file($image->getPathname(), $fullPath)) {
                            $relativePath = 'products/' . $filename;
                            $imagePaths[] = $relativePath;
                            \Log::info('Image uploaded successfully (store - manual): ' . $relativePath);
                        } else {
                            \Log::error('Failed to move uploaded file (store)', [
                                'from' => $image->getPathname(),
                                'to' => $fullPath
                            ]);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Image upload failed: ' . $e->getMessage());
                    }
                } else {
                    \Log::warning('File skipped - validation failed', [
                        'error_code' => $image ? $image->getError() : 'null',
                        'size' => $image ? $image->getSize() : 0
                    ]);
                }
            }
        }

        // Handle video upload
        $videoPath = null;
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            
            // Phase 1: Enhanced debugging
            \Log::info('Processing video file', [
                'error' => $video ? $video->getError() : 'null',
                'size' => $video ? $video->getSize() : 0,
                'name' => $video ? $video->getClientOriginalName() : 'none',
                'path' => $video ? $video->getPathname() : 'none',
                'readable' => $video && $video->getPathname() ? is_readable($video->getPathname()) : false
            ]);
            
            // Phase 3: Enhanced validation
            if ($video && 
                $video->getError() === UPLOAD_ERR_OK && 
                $video->getSize() > 0 && 
                $video->getPathname() && 
                is_readable($video->getPathname())) {
                try {
                    // Phase 4: Manual file move
                    $extension = $video->getClientOriginalExtension();
                    $filename = \Str::random(40) . '.' . $extension;
                    $destinationPath = storage_path('app/public/products/videos');
                    
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }
                    
                    $fullPath = $destinationPath . '/' . $filename;
                    
                    if (move_uploaded_file($video->getPathname(), $fullPath)) {
                        $videoPath = 'products/videos/' . $filename;
                        \Log::info('Video uploaded successfully (store - manual): ' . $videoPath);
                    } else {
                        \Log::error('Failed to move uploaded video (store)', [
                            'from' => $video->getPathname(),
                            'to' => $fullPath
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Video upload failed: ' . $e->getMessage());
                }
            } else {
                \Log::warning('Video skipped - validation failed', [
                    'error_code' => $video ? $video->getError() : 'null',
                    'size' => $video ? $video->getSize() : 0
                ]);
            }
        }

        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'min_quota' => $validated['min_quota'],
            'current_quota' => 0,
            'specifications' => $validated['specifications'] ?? null,
            'images' => $imagePaths,
            'video_url' => $videoPath,
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'min_quota' => ['required', 'integer', 'min:1'],
            'specifications' => ['nullable', 'string'],
            'images' => ['nullable', 'array', 'max:4'], // Max 4 images
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'video' => ['nullable', 'file', 'mimes:mp4,mov,avi,wmv', 'max:51200'], // max 50MB
        ]);

        // Ensure images is always an array
        $imagePaths = $product->images;
        if (!is_array($imagePaths)) {
            $imagePaths = $imagePaths ? json_decode($imagePaths, true) : [];
        }
        $imagePaths = $imagePaths ?? [];

        if ($request->hasFile('images')) {
            // Delete old images
            if (is_array($imagePaths)) {
                foreach ($imagePaths as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }


            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                // Phase 1: Enhanced debugging
                \Log::info('Processing image file (update)', [
                    'error' => $image ? $image->getError() : 'null',
                    'size' => $image ? $image->getSize() : 0,
                    'name' => $image ? $image->getClientOriginalName() : 'none',
                    'mime' => $image ? $image->getMimeType() : 'none',
                    'path' => $image ? $image->getPathname() : 'none',
                    'readable' => $image && $image->getPathname() ? is_readable($image->getPathname()) : false
                ]);
                
                // Phase 3: Enhanced validation
                if ($image && 
                    $image->getError() === UPLOAD_ERR_OK && 
                    $image->getSize() > 0 && 
                    $image->getPathname() && 
                    is_readable($image->getPathname())) {
                    try {
                        // Phase 4: Manual file move instead of store()
                        $extension = $image->getClientOriginalExtension();
                        $filename = \Str::random(40) . '.' . $extension;
                        $destinationPath = storage_path('app/public/products');
                        
                        // Create directory if not exists
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }
                        
                        $fullPath = $destinationPath . '/' . $filename;
                        
                        // Move uploaded file manually
                        if (move_uploaded_file($image->getPathname(), $fullPath)) {
                            $relativePath = 'products/' . $filename;
                            $imagePaths[] = $relativePath;
                            \Log::info('Image uploaded successfully (update - manual): ' . $relativePath);
                        } else {
                            \Log::error('Failed to move uploaded file (update)', [
                                'from' => $image->getPathname(),
                                'to' => $fullPath
                            ]);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Image upload failed (update): ' . $e->getMessage());
                    }
                } else {
                    \Log::warning('File skipped (update) - validation failed', [
                        'error_code' => $image ? $image->getError() : 'null',
                        'size' => $image ? $image->getSize() : 0
                    ]);
                }
            }
        }

        // Handle video upload
        $videoPath = $product->video_url;
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            
            // Phase 1: Enhanced debugging
            \Log::info('Processing video file (update)', [
                'error' => $video ? $video->getError() : 'null',
                'size' => $video ? $video->getSize() : 0,
                'name' => $video ? $video->getClientOriginalName() : 'none',
                'path' => $video ? $video->getPathname() : 'none',
                'readable' => $video && $video->getPathname() ? is_readable($video->getPathname()) : false
            ]);
            
            // Phase 3: Enhanced validation
            if ($video && 
                $video->getError() === UPLOAD_ERR_OK && 
                $video->getSize() > 0 && 
                $video->getPathname() && 
                is_readable($video->getPathname())) {
                try {
                    // Delete old video if exists
                    if ($product->video_url) {
                        Storage::disk('public')->delete($product->video_url);
                    }

                    // Phase 4: Manual file move
                    $extension = $video->getClientOriginalExtension();
                    $filename = \Str::random(40) . '.' . $extension;
                    $destinationPath = storage_path('app/public/products/videos');
                    
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }
                    
                    $fullPath = $destinationPath . '/' . $filename;
                    
                    if (move_uploaded_file($video->getPathname(), $fullPath)) {
                        $videoPath = 'products/videos/' . $filename;
                        \Log::info('Video uploaded successfully (update - manual): ' . $videoPath);
                    } else {
                        \Log::error('Failed to move uploaded video (update)', [
                            'from' => $video->getPathname(),
                            'to' => $fullPath
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Video upload failed (update): ' . $e->getMessage());
                }
            } else {
                \Log::warning('Video skipped (update) - validation failed', [
                    'error_code' => $video ? $video->getError() : 'null',
                    'size' => $video ? $video->getSize() : 0
                ]);
            }
        }

        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'min_quota' => $validated['min_quota'],
            'specifications' => $validated['specifications'] ?? null,
            'images' => $imagePaths,
            'video_url' => $videoPath,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product)
    {
        // Delete images
        if ($product->images && is_array($product->images)) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        // Delete video
        if ($product->video_url) {
            Storage::disk('public')->delete($product->video_url);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Toggle product active status
     */
    public function toggleActive(Product $product)
    {
        $product->is_active = !$product->is_active;
        $product->save();

        $status = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Produk berhasil {$status}.");
    }
}

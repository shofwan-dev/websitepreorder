<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display website settings page
     */
    public function website()
    {
        $settings = Setting::getGroup('website');
        
        return view('admin.settings.website', [
            'settings' => $settings
        ]);
    }

    /**
     * Update website settings
     */
    public function updateWebsite(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:500'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,ico,webp', 'max:2048'],
        ]);

        // Save text settings
        Setting::setValue('site_name', $validated['site_name'], 'website');
        Setting::setValue('tagline', $validated['tagline'] ?? '', 'website');
        Setting::setValue('email', $validated['email'] ?? '', 'website');
        Setting::setValue('phone', $validated['phone'] ?? '', 'website');
        Setting::setValue('address', $validated['address'] ?? '', 'website');

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            
            // Debug logging
            \Log::info('Logo upload attempt', [
                'isValid' => $logo->isValid(),
                'size' => $logo->getSize(),
                'realPath' => $logo->getRealPath(),
                'mimeType' => $logo->getMimeType(),
                'clientOriginalName' => $logo->getClientOriginalName(),
                'extension' => $logo->getClientOriginalExtension(),
                'tmpName' => $logo->getPathname()
            ]);
            
            // Relaxed validation - just check if file is valid and is an image
            if ($logo->isValid() && $logo->getSize() > 0) {
                $mimeType = $logo->getMimeType();
                $extension = strtolower($logo->getClientOriginalExtension());
                
                // Accept common image formats
                $allowedMimes = [
                    'image/png', 'image/jpeg', 'image/jpg', 'image/pjpeg',
                    'image/svg+xml', 'image/svg', 
                    'image/x-icon', 'image/vnd.microsoft.icon',
                    'image/webp', 'image/gif'
                ];
                
                $allowedExtensions = ['png', 'jpg', 'jpeg', 'svg', 'ico', 'webp', 'gif'];
                
                // Check either MIME type or extension
                if (in_array($mimeType, $allowedMimes) || in_array($extension, $allowedExtensions)) {
                    try {
                        // Generate unique filename
                        $filename = time() . '_' . uniqid() . '.' . $extension;
                        $path = 'logos/' . $filename;
                        
                        // Use alternative method: read file content and put to storage
                        // This bypasses the getRealPath issue
                        $fileContent = file_get_contents($logo->getPathname());
                        
                        if ($fileContent === false) {
                            \Log::error('Failed to read uploaded file content');
                            return back()->withErrors(['logo' => 'Gagal membaca file yang diupload.']);
                        }
                        
                        // Store to public disk
                        $stored = \Storage::disk('public')->put($path, $fileContent);
                        
                        if ($stored) {
                            Setting::setValue('site_logo', $path, 'website');
                            \Log::info('Logo uploaded successfully', ['path' => $path]);
                        } else {
                            \Log::error('Failed to store logo file to disk');
                            return back()->withErrors(['logo' => 'Gagal menyimpan logo ke storage.']);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Logo upload exception: ' . $e->getMessage(), [
                            'trace' => $e->getTraceAsString()
                        ]);
                        return back()->withErrors(['logo' => 'Terjadi kesalahan saat upload logo: ' . $e->getMessage()]);
                    }
                } else {
                    \Log::warning('File rejected - invalid type', [
                        'mimeType' => $mimeType,
                        'extension' => $extension
                    ]);
                    return back()->withErrors(['logo' => 'Format file tidak didukung. MIME: ' . $mimeType . ', Extension: ' . $extension]);
                }
            } else {
                \Log::warning('File rejected - invalid or empty', [
                    'isValid' => $logo->isValid(),
                    'size' => $logo->getSize()
                ]);
                return back()->withErrors(['logo' => 'File tidak valid atau kosong. Size: ' . $logo->getSize() . ' bytes']);
            }
        }

        // Handle logo removal
        if ($request->has('remove_logo') && $request->remove_logo == '1') {
            Setting::setValue('site_logo', '', 'website');
        }

        return back()->with('success', 'Pengaturan website berhasil disimpan.');
    }

    /**
     * Display WhatsApp settings page
     */
    public function whatsapp()
    {
        $settings = Setting::getGroup('whatsapp');
        
        return view('admin.settings.whatsapp', [
            'settings' => $settings
        ]);
    }

    /**
     * Update WhatsApp settings
     */
    public function updateWhatsapp(Request $request)
    {
        $validated = $request->validate([
            'api_key' => ['nullable', 'string', 'max:255'],
            'sender' => ['nullable', 'string', 'max:20'],
            'endpoint' => ['nullable', 'url', 'max:500'],
        ]);

        // Save each setting
        Setting::setValue('whatsapp_api_key', $validated['api_key'] ?? '', 'whatsapp');
        Setting::setValue('whatsapp_sender', $validated['sender'] ?? '', 'whatsapp');
        Setting::setValue('whatsapp_endpoint', $validated['endpoint'] ?? '', 'whatsapp');

        return back()->with('success', 'Pengaturan WhatsApp berhasil disimpan.');
    }

    /**
     * Display payment settings page
     */
    public function payment()
    {
        return view('admin.settings.payment');
    }

    /**
     * Update payment settings
     */
    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            'ipaymu_va' => ['required', 'string', 'max:50'],
            'ipaymu_api_key' => ['required', 'string', 'max:255'],
            'ipaymu_environment' => ['required', 'in:sandbox,production'],
        ]);

        // Update .env file
        $this->updateEnvFile([
            'IPAYMU_VA' => $validated['ipaymu_va'],
            'IPAYMU_API_KEY' => $validated['ipaymu_api_key'],
            'IPAYMU_ENVIRONMENT' => $validated['ipaymu_environment'],
        ]);

        // Clear config cache
        \Artisan::call('config:clear');

        return back()->with('payment_success', 'Pengaturan iPaymu berhasil disimpan.');
    }

    /**
     * Test iPaymu connection
     */
    public function testPayment()
    {
        try {
            $ipaymu = new \App\Services\IPaymuService();
            $result = $ipaymu->testConnection();

            if ($result['success']) {
                return back()->with('payment_success', 'Koneksi ke iPaymu berhasil! Status: ' . $result['status_code']);
            } else {
                return back()->with('payment_error', 'Koneksi gagal: ' . $result['message']);
            }
        } catch (\Exception $e) {
            return back()->with('payment_error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update .env file with new values
     */
    private function updateEnvFile(array $data)
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            // Escape special characters in value
            $escapedValue = preg_replace('/[^A-Za-z0-9_\-.]/', '', $value);
            
            // Check if key exists
            if (preg_match("/^{$key}=.*/m", $envContent)) {
                // Update existing key
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}={$escapedValue}",
                    $envContent
                );
            } else {
                // Add new key
                $envContent .= "\n{$key}={$escapedValue}";
            }
        }

        file_put_contents($envFile, $envContent);
    }

    /**
     * Display content management page
     */
    public function content()
    {
        $settings = Setting::whereIn('group', ['content', 'website'])->pluck('value', 'key')->toArray();
        
        return view('admin.settings.content', [
            'settings' => $settings
        ]);
    }

    /**
     * Update content (About, How It Works, FAQ)
     */
    public function updateContent(Request $request)
    {
        $section = $request->input('section');

        switch ($section) {
            case 'about':
                $validated = $request->validate([
                    'about_title' => 'nullable|string|max:255',
                    'about_description' => 'nullable|string',
                    'about_vision' => 'nullable|string',
                    'about_mission' => 'nullable|string',
                ]);

                foreach ($validated as $key => $value) {
                    Setting::setValue($key, $value ?? '', 'content');
                }
                break;

            case 'how_it_works':
                $steps = $request->input('steps', []);
                
                // Reindex and add number field
                $formattedSteps = [];
                foreach (array_values($steps) as $index => $step) {
                    $formattedSteps[] = [
                        'number' => $index + 1,
                        'title' => $step['title'] ?? '',
                        'description' => $step['description'] ?? '',
                        'icon' => $step['icon'] ?? 'fas fa-star'
                    ];
                }
                
                Setting::setValue('how_it_works_steps', json_encode($formattedSteps), 'content');
                break;

            case 'faq':
                $faqs = $request->input('faqs', []);
                
                // Reindex FAQ array
                $formattedFaqs = [];
                foreach (array_values($faqs) as $faq) {
                    $formattedFaqs[] = [
                        'question' => $faq['question'] ?? '',
                        'answer' => $faq['answer'] ?? ''
                    ];
                }
                
                Setting::setValue('faq_items', json_encode($formattedFaqs), 'content');
                break;

            default:
                return back()->withErrors(['section' => 'Invalid section']);
        }

        return back()->with('success', 'Konten berhasil diperbarui!');
    }
}

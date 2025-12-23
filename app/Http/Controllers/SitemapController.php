<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Homepage
        $sitemap .= '<url>';
        $sitemap .= '<loc>' . url('/') . '</loc>';
        $sitemap .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
        $sitemap .= '<changefreq>daily</changefreq>';
        $sitemap .= '<priority>1.0</priority>';
        $sitemap .= '</url>';
        
        // Static pages - High priority
        $staticPages = [
            ['url' => '/about', 'priority' => '0.9', 'changefreq' => 'monthly'],
            ['url' => '/contact', 'priority' => '0.9', 'changefreq' => 'monthly'],
            ['url' => '/how-it-works', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => '/faq', 'priority' => ' 0.8', 'changefreq' => 'monthly'],
            ['url' => '/refund-policy', 'priority' => '0.6', 'changefreq' => 'yearly'],
            ['url' => '/terms-conditions', 'priority' => '0.6', 'changefreq' => 'yearly'],
        ];
        
        foreach ($staticPages as $page) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . url($page['url']) . '</loc>';
            $sitemap .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
            $sitemap .= '<changefreq>' . $page['changefreq'] . '</changefreq>';
            $sitemap .= '<priority>' . $page['priority'] . '</priority>';
            $sitemap .= '</url>';
        }
        
        // Products - Dynamic pages
        $products = Product::where('is_active', true)
            ->latest('updated_at')
            ->get();
            
        foreach ($products as $product) {
            $sitemap .= '<url>';
            // Note: Assuming you have a product detail route
            $sitemap .= '<loc>' . url('/products/' . $product->id) . '</loc>';
            $sitemap .= '<lastmod>' . $product->updated_at->toAtomString() . '</lastmod>';
            $sitemap .= '<changefreq>weekly</changefreq>';
            $sitemap .= '<priority>0.7</priority>';
            $sitemap .= '</url>';
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }
}

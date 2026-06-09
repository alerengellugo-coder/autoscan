<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Enums\ProductCategory;
use Illuminate\Http\Request;

/**
 * Controller: PageController
 *
 * Handles public-facing static pages:
 *   - Home (landing page)
 *   - About
 *   - Services
 *   - Contact
 *
 * These routes do not require authentication.
 */
class PageController extends Controller
{
    /**
     * Display the home / landing page.
     *
     * Public page that showcases the AutoScan service offering.
     */
    public function home()
    {
        // Load all active products from DB, grouped by category
        $products = Product::active()->orderBy('name')->get();
        $categories = ProductCategory::cases();

        // Group products by category
        $productsByCategory = $products->groupBy(fn ($p) => $p->category?->value ?? 'other');

        return view('pages.home', [
            'products' => $products,
            'categories' => $categories,
            'products_by_category' => $productsByCategory,
        ]);
    }

    /**
     * Display the about page.
     *
     * Public informational page about the company.
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Display the services page.
     *
     * Public page listing all available services offered
     * by the AutoScan workshop.
     */
    public function services()
    {
        return view('pages.services');
    }

    /**
     * Display the contact page.
     *
     * Public page with contact information and a
     * contact form (handled via frontend/API).
     */
    public function contact()
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        return back()->with('success', 'Mensaje enviado correctamente. Nos pondremos en contacto pronto.');
    }

}

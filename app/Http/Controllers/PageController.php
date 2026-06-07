<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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
        return view('pages.home');
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
}

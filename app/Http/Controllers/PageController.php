<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

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
    public function home(): Response
    {
        return Inertia::render('Home');
    }

    /**
     * Display the about page.
     *
     * Public informational page about the company.
     */
    public function about(): Response
    {
        return Inertia::render('About');
    }

    /**
     * Display the services page.
     *
     * Public page listing all available services offered
     * by the AutoScan workshop.
     */
    public function services(): Response
    {
        return Inertia::render('Services');
    }

    /**
     * Display the contact page.
     *
     * Public page with contact information and a
     * contact form (handled via frontend/API).
     */
    public function contact(): Response
    {
        return Inertia::render('Contact');
    }
}

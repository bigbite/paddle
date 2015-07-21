<?php

namespace App\Http\Controllers;

class FrontController extends Controller
{
    /**
     * Shows the home view.
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        return view('front.home');
    }

    /**
     * Shows the privacy view.
     *
     * @return \Illuminate\View\View
     */
    public function privacy()
    {
        return view('front.privacy');
    }

    /**
     * Shows the terms view.
     *
     * @return \Illuminate\View\View
     */
    public function terms()
    {
        return view('front.terms');
    }
}

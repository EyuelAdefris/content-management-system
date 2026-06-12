<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $pagesCount = 0;
        $postsCount = 0;
        $mediaCount = 0;
        $bannersCount = 0;

        return view('dashboard.index', compact('pagesCount', 'postsCount', 'mediaCount', 'bannersCount'));
    }
}


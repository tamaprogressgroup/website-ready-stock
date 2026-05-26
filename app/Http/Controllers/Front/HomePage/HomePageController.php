<?php

namespace App\Http\Controllers\Front\HomePage;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomePageController extends Controller
{
    public function __construct()
    {
        
    }
    public function index() : View
    {
        return view('');
    }
}
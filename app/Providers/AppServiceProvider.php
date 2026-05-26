<?php

namespace App\Providers;

use App\Http\ViewComposers\NavbarComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('front.layout.navbar', NavbarComposer::class);
    }
}

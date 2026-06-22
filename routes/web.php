<?php

use App\Http\Controllers\Back\AdminUser\AdminUserController;
use App\Http\Controllers\Back\LeadsController;
use App\Http\Controllers\Back\Auth\LoginController;
use App\Http\Controllers\Back\Auth\RegisterController;
use App\Http\Controllers\Back\Master\BannerController;
use App\Http\Controllers\Back\Master\ClusterController;
use App\Http\Controllers\Back\Master\PropertyConditionController;
use App\Http\Controllers\Back\Master\PropertyTypeController;
use App\Http\Controllers\Back\Master\TownshipController;
use App\Http\Controllers\Back\PasangProperty\PasangPropertyController;
use App\Http\Controllers\Back\Property\PropertyController;
use App\Http\Controllers\Back\PageSeoController;
use App\Http\Controllers\Front\AllProductController;
use App\Http\Controllers\Front\DetailProductController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\LeadController;
use App\Http\Controllers\Front\RedisController;
use Illuminate\Support\Facades\Route;

// ── Auth (unauthenticated) ─────────────────────────────────────────────────
Route::get('/login',     [LoginController::class,    'showLogin'])->name('back.login');
Route::post('/login',    [LoginController::class,    'login'])->name('back.login.post');
Route::post('/logout',   [LoginController::class,    'logout'])->name('back.logout');
Route::get('/register',  [RegisterController::class, 'showRegister'])->name('back.register');
Route::post('/register', [RegisterController::class, 'register'])->name('back.register.post');

// ── Front — explicit paths (must be declared before wildcard routes) ───────
Route::get('/',            [HomeController::class,   'index'])->name('front.home');
Route::get('/all-products',[AllProductController::class, 'index'])->name('front.all-products');
Route::get('/detail-product/{id}', [DetailProductController::class, 'index'])->name('front.detail');
Route::post('/lead',       [LeadController::class,  'store'])->name('front.lead.store');
Route::get('/thankyou',    [LeadController::class,  'thankyou'])->name('front.thankyou');

Route::get('/s/{code}', [\App\Http\Controllers\Front\ShortLinkController::class, 'redirect'])->name('short.redirect');

// ── Back — Protected ───────────────────────────────────────────────────────
Route::middleware('back.auth')->group(function () {
    Route::get('/customer', fn() => view('back/layout/dashboard'))->name('customer.dashboard');
    Route::get('/customer/leads', [LeadsController::class, 'index'])->name('customer.leads');

    // Property Saya
    Route::get('/customer/property',                  [PropertyController::class, 'index'])->name('customer.property');
    Route::get('/customer/property/create',           [PropertyController::class, 'create'])->name('customer.property.create');
    Route::post('/customer/property',                 [PropertyController::class, 'store'])->name('customer.property.store');
    Route::get('/customer/property/backfill-slugs',   [PropertyController::class, 'backfillSlugs'])->name('customer.property.backfill-slugs');
    Route::get('/customer/property/importable-list',  [PropertyController::class, 'importableList'])->name('customer.property.importable-list');
    Route::get('/customer/property/{id}/edit',        [PropertyController::class, 'edit'])->name('customer.property.edit');
    Route::get('/customer/property/{id}/import-data', [PropertyController::class, 'importData'])->name('customer.property.import-data');
    Route::put('/customer/property/{id}',             [PropertyController::class, 'update'])->name('customer.property.update');
    Route::delete('/customer/property/{id}',          [PropertyController::class, 'destroy'])->name('customer.property.destroy');
    Route::patch('/customer/property/{id}/status',    [PropertyController::class, 'updateStatus'])->name('customer.property.status');

    Route::get('/customer/pasang-property', fn() => redirect()->route('customer.property.create'));
    Route::post('/customer/pasang-property/simpan', [PasangPropertyController::class, 'store'])->name('properti.store');

    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('property-type',      PropertyTypeController::class)->except(['show']);
        Route::resource('property-condition', PropertyConditionController::class)->except(['show']);
        Route::resource('cluster',            ClusterController::class)->except(['show']);
        Route::resource('township',           TownshipController::class)->except(['show']);
        Route::resource('banner',             BannerController::class)->except(['show']);
    });

    Route::resource('admin-user', AdminUserController::class)->except(['show']);

    Route::get('/seo-pages',               [PageSeoController::class, 'index'])->name('back.seo-pages.index');
    Route::get('/seo-pages/{pageKey}/edit',[PageSeoController::class, 'edit'])->name('back.seo-pages.edit');
    Route::put('/seo-pages/{pageKey}',     [PageSeoController::class, 'update'])->name('back.seo-pages.update');

    Route::get('/customer/short-links',           [\App\Http\Controllers\Back\ShortLinkController::class, 'index'])->name('customer.short-links.index');
    Route::post('/customer/short-links',          [\App\Http\Controllers\Back\ShortLinkController::class, 'store'])->name('customer.short-links.store');
    Route::delete('/customer/short-links/{id}',   [\App\Http\Controllers\Back\ShortLinkController::class, 'destroy'])->name('customer.short-links.destroy');
});

// ── Embed API (CSRF-exempt via bootstrap/app.php) ─────────────────────────
Route::post('/api/embed/short-link', [\App\Http\Controllers\Api\EmbedShortLinkController::class, 'resolve'])->name('api.embed.short-link');

// ── Redis / Data API ───────────────────────────────────────────────────────
Route::prefix('api/data')->group(function () {
    Route::get('/banners',                     [RedisController::class, 'banners'])->name('data.banners');
    Route::get('/property-types/{lang?}',      [RedisController::class, 'propertyTypes'])->name('data.property-types');
    Route::get('/property-conditions/{lang?}', [RedisController::class, 'propertyConditions'])->name('data.property-conditions');
    Route::get('/tags',                        [RedisController::class, 'tags'])->name('data.tags');
    Route::get('/provinces',                   [RedisController::class, 'provinces'])->name('data.provinces');
    Route::get('/cities/{provinsiId}',         [RedisController::class, 'cities'])->name('data.cities');
    Route::get('/townships',                   [RedisController::class, 'townships'])->name('data.townships');
    Route::get('/clusters',                    [RedisController::class, 'clusters'])->name('data.clusters');
    Route::get('/property-units/{lang?}',      [RedisController::class, 'propertyUnits'])->name('data.property-units');
    Route::get('/property-unit/{id}/{lang?}',  [RedisController::class, 'propertyUnit'])->name('data.property-unit');
    Route::post('/refresh/{type}/{lang?}',     [RedisController::class, 'refresh'])->name('data.refresh');
    Route::post('/warm-up',                    [RedisController::class, 'warmUp'])->name('data.warm-up');
});

// ── SEO wildcard routes — MUST be last so explicit routes take priority ────
$slugPat = '[a-z0-9-]+';

// Thank you page: /{condition}/{type}/{kota}/{township}/{slug}/thankyou
Route::get('/{condition}/{type}/{kota}/{township}/{slug}/thankyou', [LeadController::class, 'thankyouSeo'])
    ->name('front.thankyou.seo')
    ->where(['condition' => $slugPat, 'type' => $slugPat, 'kota' => $slugPat, 'township' => $slugPat, 'slug' => $slugPat]);

// Property detail: /{condition}/{type}/{kota}/{township}/{slug}
Route::get('/{condition}/{type}/{kota}/{township}/{slug}', [DetailProductController::class, 'showBySlug'])
    ->name('front.detail.seo')
    ->where(['condition' => $slugPat, 'type' => $slugPat, 'kota' => $slugPat, 'township' => $slugPat, 'slug' => $slugPat]);

// Browse / filter pages (1–4 segments)
Route::get('/{condition}/{type}/{kota}/{township}', [AllProductController::class, 'browse'])
    ->name('front.browse.township')
    ->where(['condition' => $slugPat, 'type' => $slugPat, 'kota' => $slugPat, 'township' => $slugPat]);

Route::get('/{condition}/{type}/{kota}', [AllProductController::class, 'browse'])
    ->name('front.browse.kota')
    ->where(['condition' => $slugPat, 'type' => $slugPat, 'kota' => $slugPat]);

Route::get('/{condition}/{type}', [AllProductController::class, 'browse'])
    ->name('front.browse.type')
    ->where(['condition' => $slugPat, 'type' => $slugPat]);

Route::get('/{condition}', [AllProductController::class, 'browse'])
    ->name('front.browse.condition')
    ->where(['condition' => $slugPat]);

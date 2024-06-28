<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Moneda;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Facades\Module;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layouts.app', function ($view) {
            $moneda = Moneda::default()->first();
            $empresa = mi_empresa();
            $pricetype = $empresa ? getPricetypeAuth($empresa) : null;
            $categories = [];
            if (Module::isEnabled('Marketplace')) {
                $categories = Category::with(['image', 'subcategories'])
                    ->orderBy('orden', 'asc')->get();
            }

            $view->with([
                'moneda' => $moneda,
                'empresa' => $empresa,
                'pricetype' => $pricetype,
                'categories' => $categories
            ]);
        });
    }
}

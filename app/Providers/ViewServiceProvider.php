<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Moneda;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Facades\Module;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $empresa = null;
        if (Schema::hasTable('empresas')) {
            $empresa = mi_empresa();
            $moneda = Moneda::default()->first();
            $pricetype = $empresa ? getPricetypeAuth($empresa) : null;
            // Compartir los datos de la empresa con todas las vistas

            View::share('moneda', $moneda);
            View::share('pricetype', $pricetype);

            View::composer('layouts.app', function ($view) {
                $categories = [];
                if (Module::isEnabled('Marketplace')) {
                    $categories = Category::with(['image', 'subcategories'])
                        ->orderBy('orden', 'asc')->get();
                }

                $view->with([
                    'categories' => $categories
                ]);
            });
        }

        View::share('empresa', $empresa);
    }
}

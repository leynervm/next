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

        // view()->composer('*', function ($view) {
        $empresa = null;
        if (Schema::hasTable('empresas')) {
            $empresa = mi_empresa(false, ['telephones']);
            $moneda = Moneda::default()->first();
            $categories = [];
            if (Module::isEnabled('Marketplace')) {
                $categories = Category::query()->with(['image', 'subcategories'])->wherehas('productos', function ($query) {
                    $query->visibles()->publicados();
                })->orderBy('orden', 'asc')->get();
            }

            // Compartir los datos de la empresa con todas las vistas
            View::share('moneda', $moneda);
            View::share('categories', $categories);
            View::share('empresa', $empresa);
        }
        // });
    }
}

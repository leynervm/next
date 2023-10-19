<?php

namespace Modules\Almacen\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Livewire\Livewire;

class CustomLivewireServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->loadComponents();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    protected function loadComponents()
    {
        $modules = \Nwidart\Modules\Facades\Module::toCollection();

        $filesystem = new Filesystem();

        $modules->map(function ($module) use ($filesystem) {
            $modulePath = $module->getPath();

            $moduleName = $module->getName();

            $path = $modulePath . '/Http/Livewire';

            $files = collect($filesystem->isDirectory($path) ? $filesystem->allFiles($path) : []);

            $files->map(function ($file) use ($moduleName, $path) {
                $componentPath = \Illuminate\Support\Str::after($file->getRelativePathname(), $path . '/');
                // dd($file->getRelativePathname());
                $componentClassPath = strtr($componentPath, ['/' => '\\', '.php' => '']);
                // dd($componentClassPath);
                $componentName = $this->getComponentName($componentClassPath);
                // dd($componentName);
                $componentClassStr = "\\Modules\\{$moduleName}\\Http\\Livewire\\" . $componentClassPath;
                // dd($componentClassStr);


                $componentClass = get_class(new $componentClassStr);
                // dd($componentName, $componentClass);
                Livewire::component($componentName, $componentClass);
            });
        });
    }

    protected function getComponentName($componentClassPath, $moduleName = null)
    {
        $dirs = explode('\\', $componentClassPath);

        $componentName = '';

        foreach ($dirs as $dir) {
            $componentName .= \Illuminate\Support\Str::kebab(lcfirst($dir)) . '.';
        }

        $moduleNamePrefix = ($moduleName) ? \Illuminate\Support\Str::lower($moduleName) . '::' : null;

        return \Illuminate\Support\Str::substr($moduleNamePrefix . $componentName, 0, -1);
    }
}

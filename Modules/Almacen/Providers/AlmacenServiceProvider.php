<?php

namespace Modules\Almacen\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Livewire;
use Mhmiton\LaravelModulesLivewire\Providers\LivewireComponentServiceProvider;
use Modules\Almacen\Http\Livewire\Compras\ShowCompras;
use Nwidart\Modules\Facades\Module;
use ReflectionClass;
use Symfony\Component\Finder\SplFileInfo;


class AlmacenServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Almacen';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'almacen';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        // $this->registerConsoleCommands();
        $this->registerModulesLivewireComponents();
        // Livewire::component('almacen::compras.show-compras', ShowCompras::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        // $this->app->register(CustomLivewireServiceProvider::class);

    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
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

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }



    /**
     * Register wire console commands.
     *
     * @return void
     */
    private function registerConsoleCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([MakeCommand::class]);
        }
    }

    /**
     * Register modules Livewire components.
     *
     * @return void
     */
    private function registerModulesLivewireComponents(): void
    {
        $modules = Module::toCollection();
        $namespace = config('modules-livewire.namespace', 'Livewire');
        $modules->each(function ($module) use ($namespace) {
            $_directory = Str::of($module->getPath())
                ->append("/$namespace")
                ->replace('\\', '/')
                ->toString();

            $_namespace = config('modules.namespace', 'Modules') . "\\{$module->getName()}\\$namespace";
            $_aliasPrefix = "{$module->getLowerName()}.";
            // dd($_directory, $_namespace, $_aliasPrefix);
            $this->registerComponentDirectory($_directory, $_namespace, $_aliasPrefix);
        });
    }

    /**
     * Register component directory.
     *
     * @param string $directory
     * @param string $namespace
     * @param string $aliasPrefix
     *
     * @return void
     */
    protected function registerComponentDirectory(string $directory, string $namespace, string $aliasPrefix = ''): void
    {
        $filesystem = new Filesystem();

        /**
         * Directory doesn't existS.
         */
        if (!$filesystem->isDirectory($directory)) {
            return;
        }

        collect($filesystem->allFiles($directory))
            ->map(fn (SplFileInfo $file) => Str::of($namespace)
                ->append("\\{$file->getRelativePathname()}")
                ->replace(['/', '.php'], ['\\', ''])
                ->toString())
            ->filter(fn ($class) => (is_subclass_of($class, Component::class) && !(new ReflectionClass($class))->isAbstract()))
            ->each(fn ($class) => $this->registerSingleComponent($class, $namespace, $aliasPrefix));
    }

    /**
     * Register livewire single component.
     *
     * @param string $class
     * @param string $namespace
     * @param string $aliasPrefix
     *
     * @return void
     */
    private function registerSingleComponent(string $class, string $namespace, string $aliasPrefix): void
    {
        $alias = $aliasPrefix . Str::of($class)
            ->after($namespace . '\\')
            ->replace(['/', '\\'], '.')
            ->explode('.')
            ->map([Str::class, 'kebab'])
            ->implode('.');

        // dd($aliasPrefix . $class . " - " . $namespace . " DEBER SER = Modules\Almacen\Http\Livewire\Almacenareas\CreateAlmacenarea");


        Str::endsWith($class, ['\Index', '\index'])
            ? Livewire::component(Str::beforeLast($alias, '.index'), $class)
            : Livewire::component($alias, $class);
    }
}

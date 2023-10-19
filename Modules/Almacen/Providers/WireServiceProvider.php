<?php

namespace Modules\Almacen\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Livewire;
// use Modules\Wire\Console\MakeCommand;
use Nwidart\Modules\Facades\Module;
use ReflectionClass;
use Symfony\Component\Finder\SplFileInfo;

class WireServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->registerConsoleCommands();
        $this->registerModulesLivewireComponents();
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
            $_aliasPrefix = "{$module->getLowerName()}::";
            // dd($_directory, $namespace, $module->getLowerName().'::');
            $this->registerComponentDirectory($_directory, $_namespace);
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

        Str::endsWith($class, ['\Index', '\index'])
            ? Livewire::component(Str::beforeLast($alias, '.index'), $class)
            : Livewire::component($alias, $class);
    }
}

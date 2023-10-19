<?php

namespace Modules\Almacen\Http\Livewire\Compras;

use Illuminate\Filesystem\Filesystem;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Almacen\Entities\Compra;
use Illuminate\Support\Str;

class ShowCompras extends Component
{

    use WithPagination;

    public $search = '';
    public $date = '';

    protected $queryString  = [
        'search' => ['except' => '', 'as' => 'buscar'],
        'date' => ['except' => '', 'as' => 'fecha-compra'],
    ];

    public function render()
    {
        $compras = Compra::orderBy('created_at', 'desc');

        if (trim($this->search) !== '') {
            $compras->whereHas('proveedor', function ($query) {
                $query->where('document', 'ilike', '%' . $this->search . '%')
                    ->orWhere('name', 'ilike', '%' . $this->search . '%');
            })
                ->orWhere('referencia', 'ilike', '%' . $this->search . '%');
        }

        if ($this->date) {
            $compras->whereDate('date', $this->date);
        }

        $compras = $compras->paginate();
        return view('almacen::livewire.compras.show-compras', compact('compras'));
    }

    public function updatedDate($value)
    {
        $this->resetPage();
    }


    // public function registerModuleComponents()
    // {


    //     $modules = \Nwidart\Modules\Facades\Module::toCollection();
    //     $namespace = config('modules-livewire.namespace', 'Livewire');
    //     $modules->each(function ($module) use ($namespace) {
    //         $_directory = Str::of($module->getPath())
    //             ->append("/$namespace")
    //             ->replace('\\', '/')
    //             ->toString();

    //         // dd($namespace);

    //         $_namespace = config('modules.namespace', 'Modules') . "\\{$module->getName()}\\$namespace";
    //         $_aliasPrefix = "{$module->getLowerName()}::";

    //         // dd($_directory . " - " . $_namespace . " - " . $_aliasPrefix . " DEBER SER = Modules\Almacen\Http\Livewire\Almacenareas\CreateAlmacenarea");

    //         $this->registerComponentDirectory($_directory, $_namespace, $_aliasPrefix);
    //     });
    // }

    // public function registerComponentDirectory($directory, $namespace, $aliasPrefix = '')
    // {
    //     $filesystem = new Filesystem();
    //     if (!$filesystem->isDirectory($directory)) {
    //         return;
    //     }

    //     collect($filesystem->allFiles($directory))
    //         ->map(fn (\SplFileInfo $file) => Str::of($namespace)
    //             ->append("\\{$file->getRelativePathname()}")
    //             ->replace(['/', '.php'], ['\\', ''])
    //             ->toString())
    //         ->filter(fn ($class) => (is_subclass_of($class, Component::class) && !(new \ReflectionClass($class))->isAbstract()))
    //         ->each(fn ($class) => $this->registerSingleComponent($class, $namespace, $aliasPrefix));
    // }

    // private function registerSingleComponent(string $class, string $namespace, string $aliasPrefix): void
    // {
    //     // dd($aliasPrefix . $class . " - " . $namespace . " DEBER SER = Modules\Almacen\Http\Livewire\Almacenareas\CreateAlmacenarea");
    //     $alias = $aliasPrefix . Str::of($class)
    //         ->after($namespace . '\\')
    //         ->replace(['/', '\\'], '.')
    //         ->explode('.')
    //         ->map([Str::class, 'kebab'])
    //         ->implode('.');

    //     dd($alias . " - " . $class);

    //     Str::endsWith($class, ['\Index', '\index'])
    //         ? \Livewire\Livewire::component(Str::beforeLast($alias, '.index'), $class)
    //         : \Livewire\Livewire::component($alias, $class);
    // }
}

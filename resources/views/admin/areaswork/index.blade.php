<x-admin-layout>

    @can('admin.administracion.areaswork.create')
        <div class="mt-3">
            @livewire('admin.areaswork.create-areawork')
        </div>
    @endcan

    @can('admin.administracion.areaswork')
        <div class="mt-3">
            @livewire('admin.areaswork.show-areaswork')
        </div>
    @endcan
</x-admin-layout>

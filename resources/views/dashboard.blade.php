<x-admin-layout>
    @if (mi_empresa())
    @else
        <livewire:admin.empresas.configuracion-inicial />
    @endif
</x-admin-layout>

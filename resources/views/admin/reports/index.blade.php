<x-admin-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="REPORTES" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3v18h18" />
                    <rect width="4" height="7" x="7" y="10" rx="1" />
                    <rect width="4" height="12" x="15" y="5" rx="1" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <div class="grid grid-cols-[repeat(auto-fill,minmax(100px,1fr))] gap-1 self-start mt-2">

        <livewire:admin.reports.generar-reporte-caja wire:key="report_caja" />
        <livewire:admin.reports.generar-reporte-personal wire:key="report_employers" />
        <livewire:admin.reports.generar-reporte-ventas wire:key="report_ventas" />
        <livewire:admin.reports.generar-reporte-compras wire:key="report_compras" />
        <livewire:admin.reports.generar-reporte-productos wire:key="report_productos" />
        {{-- <livewire:admin.reports.generar-reporte-comprobantes wire:key="report_comprobantes" /> --}}
        {{-- <livewire:admin.reports.generar-reporte-guias wire:key="report_guias" /> --}}

    </div>
</x-admin-layout>

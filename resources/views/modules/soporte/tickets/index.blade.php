<x-admin-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="SOPORTE TÉCNICO" route="admin.soporte">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 10 2 2 4-4" />
                    <rect width="20" height="14" x="2" y="3" rx="2" />
                    <path d="M12 17v4" />
                    <path d="M8 21h8" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
        <x-link-breadcrumb text="TICKETS" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="size-5">
                    <path d="M13 15.5v-6.5a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v4" />
                    <path d="M18 8v-3a1 1 0 0 0 -1 -1h-13a1 1 0 0 0 -1 1v12a1 1 0 0 0 1 1h7" />
                    <path d="M16 9h2" />
                    <path d="M15 19l2 2l4 -4" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <div class="w-full flex">
        <x-link-next href="{{ route('admin.soporte.tickets.selectarea') }}" titulo="Registrar">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" x2="12" y1="5" y2="19" />
                <line x1="5" x2="19" y1="12" y2="12" />
            </svg>
        </x-link-next>
    </div>

    <div class="">
        @livewire('modules.soporte.tickets.show-tickets')
    </div>

    @if (session('saved-tickets'))
        <div class="fixed bottom-0 right-0 z-50 max-h-screen max-w-full md:p-2 rounded-lg overflow-hidden">
            <div class="flex md:flex-col gap-1.5 max-w-full overflow-auto max-h-[calc(100vh-108px)]">
                @foreach (session('saved-tickets') as $item)
                    <div
                        class="max-w-fit xs:max-w-xs overflow-hidden flex flex-col text-colorsubtitleform p-1 bg-fondominicard border border-b-4 border-r-4 border-next-500 rounded-lg">
                        <div class="w-full flex items-start gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                class="size-6 md:size-8 block">
                                <path
                                    d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                <path
                                    d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                <path d="M9 14l2 2l4 -4" />
                            </svg>

                            <div class="w-full flex-1 flex flex-col gap-1">
                                <div class="font-semibold leading-none text-xs">{{ $item['serie'] }}</div>
                                <p class="block w-full text-colorsubtitleform leading-tight text-[10px]">
                                    {{ $item['detalle'] }}
                                </p>
                            </div>
                        </div>
                        <div class="w-full flex justify-end items-end gap-2">
                            <a href="{{ route('admin.soporte.tickets.print.registro', $item['serie']) }}"
                                target="_blank"
                                class="inline-block group relative font-semibold text-[10px] text-next-500 p-1 rounded-lg hover:bg-next-500 focus:bg-next-500 hover:ring-2 hover:ring-next-300 focus:ring-2 focus:ring-next-300 hover:text-white focus:text-white disabled:opacity-25 transition ease-in duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="size-4 block scale-110">
                                    <path
                                        d="M15.646 13.09l-.868 -.868l3.113 -2.09a1.2 1.2 0 0 0 -.309 -2.228l-13.582 -3.904l3.904 13.563a1.2 1.2 0 0 0 2.228 .308l2.09 -3.093l.607 .607" />
                                    <path d="M16 22l5 -5" />
                                    <path d="M21 21.5v-4.5h-4.5" />
                                </svg>
                            </a>
                            <x-button-print href="{{ route('admin.soporte.tickets.print.registro', $item['serie']) }}"
                                target="_blank">
                                {{ __('Print') }}
                            </x-button-print>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</x-admin-layout>

<div>
    @if ($paginator->hasPages())
        @php(isset($this->numberOfPaginatorsRendered[$paginator->getPageName()]) ? $this->numberOfPaginatorsRendered[$paginator->getPageName()]++ : ($this->numberOfPaginatorsRendered[$paginator->getPageName()] = 1))

        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
            <div class="flex justify-between flex-1 sm:hidden">
                <span>
                    @if ($paginator->onFirstPage())
                        <span
                            class="relative uppercase inline-flex items-center p-3 text-xs font-medium text-colorsubtitleform bg-fondopagination border border-shadowminicard cursor-default leading-5 rounded-lg select-none opacity-40">
                            {{-- {!! __('pagination.previous') !!} --}}
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"
                                class="w-4 h-4 block">
                                <path d="M3.99982 11.9998L19.9998 11.9998" />
                                <path
                                    d="M8.99963 17C8.99963 17 3.99968 13.3176 3.99966 12C3.99965 10.6824 8.99966 7 8.99966 7" />
                            </svg>
                        </span>
                    @else
                        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            wire:loading.attr="disabled"
                            dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"
                            class="relative uppercase inline-flex items-center p-3 text-xs font-medium text-primary bg-fondopagination border border-shadowpagination leading-5 rounded-lg hover:text-coloractivepagination hover:bg-fondoactivepagination focus:outline-none focus:shadow-outline-blue focus:border-shadowpagination active:bg-fondoactivepagination active:text-coloractivepagination transition ease-in-out duration-150">
                            {{-- {!! __('pagination.previous') !!} --}}
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"
                                class="w-4 h-4 block">
                                <path d="M3.99982 11.9998L19.9998 11.9998" />
                                <path
                                    d="M8.99963 17C8.99963 17 3.99968 13.3176 3.99966 12C3.99965 10.6824 8.99966 7 8.99966 7" />
                            </svg>
                        </button>
                    @endif
                </span>

                <span>
                    @if ($paginator->hasMorePages())
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            wire:loading.attr="disabled"
                            dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"
                            class="relative uppercase inline-flex items-center p-3 ml-3 text-xs font-medium text-primary bg-fondopagination border border-shadowpagination leading-5 rounded-lg hover:text-coloractivepagination hover:bg-fondoactivepagination focus:outline-none focus:shadow-outline-blue focus:border-shadowpagination active:bg-fondoactivepagination active:text-coloractivepagination transition ease-in-out duration-150">
                            {{-- {!! __('pagination.next') !!} --}}
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="w-4 h-4 block">
                                <path d="M20.0001 11.9998L4.00012 11.9998" />
                                <path
                                    d="M15.0003 17C15.0003 17 20.0002 13.3176 20.0002 12C20.0002 10.6824 15.0002 7 15.0002 7" />
                            </svg>
                        </button>
                    @else
                        <span
                            class="relative uppercase inline-flex items-center p-3 ml-3 text-xs font-medium text-colorsubtitleform bg-fondopagination border border-shadowminicard cursor-default leading-5 rounded-lg select-none opacity-40">
                            {{-- {!! __('pagination.next') !!} --}}
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="w-4 h-4 block">
                                <path d="M20.0001 11.9998L4.00012 11.9998" />
                                <path
                                    d="M15.0003 17C15.0003 17 20.0002 13.3176 20.0002 12C20.0002 10.6824 15.0002 7 15.0002 7" />
                            </svg>
                        </span>
                    @endif
                </span>
            </div>

            <div class="hidden sm:flex-1 sm:flex sm:flex-col sm:items-center sm:justify-between">
                <div>
                    <span class="relative z-0 inline-flex gap-0.5">
                        <span>
                            {{-- Previous Page Link --}}
                            @if ($paginator->onFirstPage())
                                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                    <span
                                        class="bg-fondopagination opacity-40 text-colorsubtitleform border-shadowpagination relative inline-flex items-center px-2 py-2 text-xs font-medium border cursor-default rounded-lg leading-5"
                                        aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 p-1" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M21 12H3m0 0l8.5-8.5M3 12l8.5 8.5" />
                                        </svg>
                                    </span>
                                </span>
                            @else
                                <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                    dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                    rel="prev"
                                    class="bg-fondopagination text-colorpagination border-shadowpagination relative inline-flex items-center px-2 py-2 text-xs font-medium border rounded-lg leading-5 hover:text-coloractivepagination hover:bg-fondoactivepagination focus:z-10 focus:outline-none active:bg-fondoactivepagination active:text-coloractivepagination transition ease-in-out duration-150"
                                    aria-label="{{ __('pagination.previous') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 p-1" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M21 12H3m0 0l8.5-8.5M3 12l8.5 8.5" />
                                    </svg>
                                </button>
                            @endif
                        </span>

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span aria-disabled="true">
                                    <span
                                        class="bg-fondopagination text-colorpagination border-shadowpagination relative inline-flex items-center px-4 py-2 -ml-px text-xs font-medium border cursor-default leading-5 rounded-lg select-none">{{ $element }}</span>
                                </span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <span
                                        wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-page{{ $page }}">
                                        @if ($page == $paginator->currentPage())
                                            <span aria-current="page">
                                                <span
                                                    class="bg-fondoactivepagination text-coloractivepagination border-shadowpagination relative inline-flex items-center px-4 py-2 -ml-px text-xs font-medium border cursor-default leading-5 rounded-lg select-none">{{ $page }}</span>
                                            </span>
                                        @else
                                            <button type="button"
                                                wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                                class="bg-fondopagination text-colorpagination border-shadowpagination relative inline-flex items-center px-4 py-2 -ml-px text-xs font-medium border rounded-lg leading-5 hover:text-coloractivepagination hover:bg-fondoactivepagination focus:z-10 focus:outline-none active:bg-fondoactivepagination active:text-coloractivepagination transition ease-in-out duration-150"
                                                aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                                {{ $page }}
                                            </button>
                                        @endif
                                    </span>
                                @endforeach
                            @endif
                        @endforeach

                        <span>
                            {{-- Next Page Link --}}
                            @if ($paginator->hasMorePages())
                                <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                    dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                    rel="next"
                                    class="bg-fondopagination text-colorpagination border-shadowpagination relative inline-flex items-center px-2 py-2 -ml-px text-xs font-medium border rounded-lg leading-5 hover:text-coloractivepagination hover:bg-fondoactivepagination focus:z-10 focus:outline-none active:bg-fondoactivepagination active:text-coloractivepagination transition ease-in-out duration-150"
                                    aria-label="{{ __('pagination.next') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 p-1" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M3 12h18m0 0l-8.5-8.5M21 12l-8.5 8.5" />
                                    </svg>
                                </button>
                            @else
                                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                    <span
                                        class="bg-fondopagination opacity-40 text-colorsubtitleform border-shadowpagination relative inline-flex items-center px-2 py-2 -ml-px text-xs font-medium border cursor-default rounded-lg leading-5"
                                        aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 p-1"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 12h18m0 0l-8.5-8.5M21 12l-8.5 8.5" />
                                        </svg>
                                    </span>
                                </span>
                            @endif
                        </span>
                    </span>
                </div>

                <div class="w-full">
                    <p class="text-xs text-primary leading-5 text-center sm:text-end w-full text-descripcion-paginator">
                        <span>{!! __('Showing') !!}</span>
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        <span>{!! __('to') !!}</span>
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                        <span>{!! __('of') !!}</span>
                        <span class="font-medium">{{ $paginator->total() }}</span>
                        <span>{!! __('results') !!}</span>
                    </p>
                </div>
            </div>
        </nav>
    @endif
</div>

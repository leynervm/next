@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span
                    class="relative uppercase inline-flex items-center p-3 text-xs font-medium text-colorsubtitleform bg-fondopagination border border-shadowminicard cursor-default leading-5 rounded-lg select-none opacity-40">
                    {{-- {!! __('pagination.previous') !!} --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" fill="none" class="w-4 h-4 block">
                        <path d="M3.99982 11.9998L19.9998 11.9998" />
                        <path
                            d="M8.99963 17C8.99963 17 3.99968 13.3176 3.99966 12C3.99965 10.6824 8.99966 7 8.99966 7" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="relative uppercase inline-flex items-center p-3 text-xs font-medium text-primary bg-fondopagination border border-shadowpagination leading-5 rounded-lg hover:text-coloractivepagination hover:bg-fondoactivepagination focus:outline-none focus:shadow-outline-blue focus:border-shadowpagination active:bg-fondoactivepagination active:text-coloractivepagination transition ease-in-out duration-150">
                    {{-- {!! __('pagination.previous') !!} --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" fill="none" class="w-4 h-4 block">
                        <path d="M3.99982 11.9998L19.9998 11.9998" />
                        <path
                            d="M8.99963 17C8.99963 17 3.99968 13.3176 3.99966 12C3.99965 10.6824 8.99966 7 8.99966 7" />
                    </svg>
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="relative uppercase inline-flex items-center p-3 ml-3 text-xs font-medium text-primary bg-fondopagination border border-shadowpagination leading-5 rounded-lg hover:text-coloractivepagination hover:bg-fondoactivepagination focus:outline-none focus:shadow-outline-blue focus:border-shadowpagination active:bg-fondoactivepagination active:text-coloractivepagination transition ease-in-out duration-150">
                    {{-- {!! __('pagination.next') !!} --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 block">
                        <path d="M20.0001 11.9998L4.00012 11.9998" />
                        <path
                            d="M15.0003 17C15.0003 17 20.0002 13.3176 20.0002 12C20.0002 10.6824 15.0002 7 15.0002 7" />
                    </svg>
                </a>
            @else
                <span
                    class="relative uppercase inline-flex items-center p-3 ml-3 text-xs font-medium text-colorsubtitleform bg-fondopagination border border-shadowminicard cursor-default leading-5 rounded-lg select-none opacity-40">
                    {{-- {!! __('pagination.next') !!} --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 block">
                        <path d="M20.0001 11.9998L4.00012 11.9998" />
                        <path
                            d="M15.0003 17C15.0003 17 20.0002 13.3176 20.0002 12C20.0002 10.6824 15.0002 7 15.0002 7" />
                    </svg>
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:flex-col sm:items-center sm:justify-between">
            <div>
                <span class="relative z-0 inline-flex gap-0.5 rounded-lg shadow-sm">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span
                                class="bg-fondopagination opacity-40 text-colorsubtitleform border-borderminicard relative inline-flex items-center px-2 py-2 text-xs font-medium border cursor-default rounded-lg leading-5"
                                aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                            class="bg-fondopagination text-colorpagination border-shadowpagination relative inline-flex items-center px-2 py-2 text-xs font-medium border rounded-lg leading-5 hover:text-coloractivepagination hover:bg-fondoactivepagination focus:z-10 focus:outline-none active:bg-fondoactivepagination active:text-coloractivepagination transition ease-in-out duration-150"
                            aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

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
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span
                                            class="bg-fondoactivepagination text-coloractivepagination border-shadowpagination relative inline-flex items-center px-4 py-2 -ml-px text-xs font-medium border cursor-default leading-5 rounded-lg select-none">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                        class="bg-fondopagination text-colorpagination border-shadowpagination relative inline-flex items-center px-4 py-2 -ml-px text-xs font-medium border rounded-lg leading-5 hover:text-coloractivepagination hover:bg-fondoactivepagination focus:z-10 focus:outline-none active:bg-fondoactivepagination active:text-coloractivepagination transition ease-in-out duration-150"
                                        aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                            class="bg-fondopagination text-colorpagination border-shadowpagination relative inline-flex items-center px-2 py-2 -ml-px text-xs font-medium border rounded-lg leading-5 hover:text-coloractivepagination hover:bg-fondoactivepagination focus:z-10 focus:outline-none active:bg-fondoactivepagination active:text-coloractivepagination transition ease-in-out duration-150"
                            aria-label="{{ __('pagination.next') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span
                                class="bg-fondopagination opacity-40 text-colorsubtitleform border-borderminicard relative inline-flex items-center px-2 py-2 -ml-px text-xs font-medium border cursor-default rounded-lg leading-5"
                                aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>

            <div>
                <p class="text-xs text-primary leading-5">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>
        </div>
    </nav>
@endif

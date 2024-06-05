<x-admin-layout>

    @if (count($areas))
        <div class="flex flex-wrap gap-3">
            @foreach ($areas as $item)
                <x-link-next href="{{ route('order.create', $item) }}" size="xl" :titulo="$item->name"
                    classTitulo="font-bold text-xs mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h8" />
                        <path d="M10 19v-3.96 3.15" />
                        <path d="M7 19h5" />
                        <rect width="6" height="10" x="16" y="12" rx="2" />
                    </svg>
                </x-link-next>
            @endforeach
        </div>
    @endif

</x-admin-layout>
<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}

    <div
        class="h-24 h-28 h-32 h-36 h-40 w-24 w-28 w-32 w-36 w-40 text-nowrap disabled:opacity-25 rounded-xl w-40 p-8 p-5 px-5 py-12 px-8 m-1">
    </div>
    {{-- <div class="text-hovercolorlinknav bg-hoverlinknav">

    </div> --}}


    <x-title-next titulo="Titulo Prueba" />
    <div class="flex gap-2 w-full py-6">

        <x-minicard title="TITLE MINICARD" content="PAGABLE">
            <x-slot name="buttons">
                <x-button-edit></x-button-edit>
                <x-button-delete></x-button-delete>
            </x-slot>
        </x-minicard>
        <x-minicard title="TITLE MINICARD">
            <x-slot name="buttons">
                <x-button-edit></x-button-edit>
                <x-button-delete></x-button-delete>
            </x-slot>
        </x-minicard>
        <x-minicard title="TITLE MINICARD" content="PAGABLE">
        </x-minicard>

    </div>


    <x-title-next titulo="Buttons Notify Configuración" />
    <div class="flex gap-2 mt-3">

        <x-button-next titulo="Configurar Perfil Usuario" classTitulo="text-[10px] font-bold text-center">
            <svg class="text-amber-400 w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                fill="currentColor">
                <path fill-rule="evenodd"
                    d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                    clip-rule="evenodd" />
            </svg>
            <span class="absolute right-0 top-0 bg-orange-600 p-1 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                    class="w-3 h-3 text-white">
                    <path fill-rule="evenodd"
                        d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                        clip-rule="evenodd" />
                </svg>
            </span>
        </x-button-next>
        <x-button-next titulo="Configurar Perfil Empresa" classTitulo="text-[10px] font-bold text-center">
            <svg class="text-amber-400 w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                fill="currentColor">
                <path fill-rule="evenodd"
                    d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                    clip-rule="evenodd" />
            </svg>
            <span class="absolute right-0 top-0 bg-orange-600 p-1 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                    class="w-3 h-3 text-white">
                    <path fill-rule="evenodd"
                        d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                        clip-rule="evenodd" />
                </svg>
            </span>
        </x-button-next>
    </div>


    <x-title-next titulo="Buttons Card" />
    <div class="inline-flex gap-2 mt-3">
        <x-button-next size="md" titulo="Registrar">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" x2="12" y1="5" y2="19" />
                <line x1="5" x2="19" y1="12" y2="12" />
            </svg>
        </x-button-next>
        <x-button-next size="md" titulo="Listado de Ordenes">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <path d="m8 11 2 2 4-4" />
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
            </svg>
        </x-button-next>
    </div>

    <x-title-next titulo="Links Next" />
    <div class="inline-flex gap-2 py-6">

        <x-link-next size="xl" titulo="PLANTA INTERNA (SOPORTE TECNICO)" classTitulo="font-bold text-xs mt-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h8" />
                <path d="M10 19v-3.96 3.15" />
                <path d="M7 19h5" />
                <rect width="6" height="10" x="16" y="12" rx="2" />
            </svg>
        </x-link-next>

        <x-link-next size="xl" titulo="PLANTA EXTERNA" classTitulo="font-bold text-xs mt-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="2" />
                <path d="M12 2v4" />
                <path d="m6.8 15-3.5 2" />
                <path d="m20.7 7-3.5 2" />
                <path d="M6.8 9 3.3 7" />
                <path d="m20.7 17-3.5-2" />
                <path d="m9 22 3-8 3 8" />
                <path d="M8 22h8" />
                <path d="M18 18.7a9 9 0 1 0-12 0" />
            </svg>
        </x-link-next>
    </div>


    <x-title-next titulo="Cards Next" />
    <div class="mb-3 py-3">
        <x-card-next titulo="Información del equipo">
            <p class="text-xs">Lorem ipsum dolor sit amet consectetur adipisicing elit. Praesentium officiis sunt
                ipsum, aspernatur
                delectus asperiores iste aut tempora eligendi, ut quod dolorem? Suscipit, aliquam? Beatae corrupti autem
                nostrum cum consequatur?</p>
        </x-card-next>
    </div>

    <x-title-next titulo="Cards Radio" />
    <div class="py-6 flex flex-wrap gap-3">

        @php
            $especificaciones = [['descripcion' => 'Fast'], ['descripcion' => 'Velocidad'], ['descripcion' => '100 Mbp/s']];
        @endphp

        <x-card-radio id="rdo_card_1" name="prueba_card" text="INTERNET 1" value="radio 1" price="100.00"
            :especificaciones="$especificaciones" />
        <x-card-radio class="h-full" id="rdo_card_2" name="prueba_card" text="INTERNET 2" price="150.00"
            value="radio 2" />
        <x-card-radio id="rdo_card_3" name="prueba_card" text="INTERNET 3" price="220.00" value="radio 3" />

    </div>

    <x-title-next titulo="Cards Contactos" />
    <div class="py-6 flex flex-wrap gap-3">

        @php
            $phones = [['phone' => '928393901'], ['phone' => '928393901'], ['phone' => '928393901']];
            $phones1 = [['phone' => '928393901']];
        @endphp


        <x-cardcontacto-radio id="rdo_contact_1" name="card_contact" text="CONTACT 1" document="74495914"
            :phones="$phones" value="contacto 1" />
        <x-cardcontacto-radio id="rdo_contact_2" name="card_contact" text="CONTACT 2" document="74495915"
            value="contacto 2" />
        <x-cardcontacto-radio class="h-full" id="rdo_contact_3" name="card_contact" text="CONTACT 3"
            document="74495916" :phones="$phones1" value="contacto 3" />

    </div>


    <x-title-next titulo="Buttons Next" />
    <div class="py-6 flex gap-3">
        <x-button>
            Button Default
        </x-button>

        <x-button class="pr-7">
            Button Icon
            <x-slot name="icono">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="m12 5 7 7-7 7" />
                </svg>
            </x-slot>
        </x-button>

        <x-button-outline class="pr-7">
            Button Icon
            <x-slot name="icono">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="m12 5 7 7-7 7" />
                </svg>
            </x-slot>
        </x-button-outline>

        <x-button-add class="px-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 19a6 6 0 0 0-12 0" />
                <circle cx="8" cy="9" r="4" />
                <line x1="19" x2="19" y1="8" y2="14" />
                <line x1="22" x2="16" y1="11" y2="11" />
            </svg>
        </x-button-add>

        <x-button-add class="px-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
            </svg>
        </x-button-add>

        <div class="inline-flex gap-x-3 items-end">
            <x-button-delete></x-button-delete>
            <x-button-edit></x-button-edit>
        </div>

    </div>


    <x-title-next titulo="Input radio" />
    <div class="py-6 flex gap-3 items-start">
        <x-input-radio id="rdo_1" name="prueba" text="Radio Component 1" value="radio 1" />
        <x-input-radio id="rdo_2" name="prueba" text="Radio Component 2" value="radio 2" />
        <x-input-radio id="rdo_3" name="prueba" text="Radio Component 3" value="radio 3" />
    </div>

    <x-title-next titulo="Inputs Next" />
    <div class="py-6 flex gap-3">

        <x-label class="text-md">Label Example :</x-label>
        <x-label class="text-md" value="Label Example 2 :" />

        <x-input type="checkbox" />
        <x-input type="radio" />
        <x-input type="number" />
        <x-input placeholder="Ingrese texto" type="text" />
        <x-input type="date" />

        @php
            $options = [['id' => '1', 'value' => 'Item 1'], ['id' => '1', 'value' => 'Item 2']];
        @endphp
        <x-select id="selectprueba">
            <x-slot name="options">
                @foreach ($options as $item)
                    <option value="{{ $item['id'] }}">{{ $item['value'] }}</option>
                @endforeach
            </x-slot>
        </x-select>

        <x-select id="select2">
            <x-slot name="options">
                @foreach ($options as $item)
                    <option value="{{ $item['id'] }}">{{ $item['value'] }}</option>
                @endforeach
            </x-slot>
        </x-select>
    </div>


    <x-title-next titulo="Card Product Next" />
    <div class="py- flex gap-2">

        @php
            $almacens = [
                0 => ['id' => 1, 'name' => 'Almacen Jaén'],
                1 => ['id' => 2, 'name' => 'Almacen Trujilio'],
            ];
            
            $series = [
                0 => ['id' => 1, 'serie' => 'XXX-XXX'],
                1 => ['id' => 2, 'serie' => 'AAA-AAA'],
                2 => ['id' => 3, 'serie' => 'BBB-BBB'],
            ];
        @endphp

        <x-form-card-product :almacens="$almacens" :series="$series">
            <x-slot name="imagen"></x-slot>
            <x-slot name="name">Lorem ipsum dolor sit</x-slot>
            <x-slot name="price">S/. 100.00</x-slot>
            <x-slot name="cantidad">02 UND</x-slot>
            <x-slot name="increment">11%</x-slot>
            <x-slot name="cotizacion">COT 123</x-slot>
        </x-form-card-product>
        <x-form-card-product :almacens="$almacens" :series="$series">
            <x-slot name="imagen"></x-slot>
            <x-slot name="name">Lorem ipsum dolor sit</x-slot>
            <x-slot name="price">S/. 100.00</x-slot>
            <x-slot name="cantidad">02 UND</x-slot>
            <x-slot name="increment">11%</x-slot>
            <x-slot name="cotizacion">COT 123</x-slot>
        </x-form-card-product>
        <x-form-card-product :almacens="$almacens" :series="$series">
            <x-slot name="imagen"></x-slot>
            <x-slot name="name">Lorem ipsum dolor sit</x-slot>
            <x-slot name="price">S/. 100.00</x-slot>
            <x-slot name="cantidad">02 UND</x-slot>
            <x-slot name="increment">11%</x-slot>
            <x-slot name="cotizacion">COT 123</x-slot>
        </x-form-card-product>

    </div>


    {{-- <div class="p-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-jet-welcome />
            </div>
        </div>
    </div> --}}


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOMContent Loaded');
            $('#select2').select2();
        });
    </script>
</x-app-layout>

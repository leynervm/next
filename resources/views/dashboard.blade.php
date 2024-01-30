<x-app-layout>

    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}

    <div
        class="h-24 h-28 h-32 h-36 h-40 w-24 w-28 w-32 w-36 w-40 text-nowrap disabled:opacity-25 rounded-xl w-40 p-8 p-5 px-5 py-12 px-8 m-1 md:justify-start">
    </div>
    {{-- <div class="text-hovercolorlinknav bg-hoverlinknav">

    </div> --}}


    <div class="max-w-xs relative" x-data="{ selectedCity: '' }" x-init="select2Alpine" id="parentdemo">
        <x-select x-ref="select" id="demo" data-placeholder="Please Select" data-minimum-results-for-search="3">
            <x-slot name="options">
                <option></option>
                <option value="1">London</option>
                <option value="2">New York</option>
                <option value="3">London</option>
                <option value="4">New York</option>
                <option value="5">London</option>
                <option value="6">New York</option>
            </x-slot>
        </x-select>
        <x-icon-select />
        {{-- <p>Selected value (bound in Alpine.js): <code x-text="selectedCity"></code></p>
        <p><button @click="selectedCity = ''">Reset selectedCity</button></p>
        <p><button @click="selectedCity = 'London'">Trigger selection of London</button></p>
        <p><button @click="selectedCity = 'New York'">Trigger selection of New York</button></p> --}}
    </div>





    <div class="my-6 max-w-xs">
        <div class="relative text-gray-400" x-data="selectmenu(datalist())" @click.outside="close()">
            <input type="text" x-model="selectedkey" name="selectfield" id="selectfield" class="hidden">
            <span class="inline-block w-full rounded-md shadow-sm"
                @click="toggle(); $nextTick(() => $refs.filterinput.focus());">
                <button
                    class="relative z-0 w-full py-2 pl-3 pr-10 text-left transition duration-150 ease-in-out bg-transparent border border-next-300 rounded-xs cursor-default focus:outline-none focus:shadow-outline-blue focus:border-next-300 sm:text-sm sm:leading-5">
                    <span class="block truncate" x-text="selectedlabel ?? 'Please Select'"></span>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                            <path d="M7 7l3-3 3 3m0 6l-3 3-3-3" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                </button>
            </span>

            <div x-show="state" class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg p-2">
                <input type="text" class="w-full rounded-md py-1 px-2 mb-1 border border-gray-400" x-model="filter"
                    x-ref="filterinput" @keydown.enter.stop.prevent="selectOption()"
                    @keydown.arrow-up.prevent="focusPreviousOption()" @keydown.arrow-down.prevent="focusNextOption()"
                    @keydown.up.prevent="focusPreviousOption()" @keydown.down.prevent="focusNextOption()">
                <ul x-ref="listbox" @keydown.enter.stop.prevent="selectOption()"
                    @keydown.space.stop.prevent="selectOption()" @keydown.escape="onEscape()" role="listbox"
                    :aria-activedescendant="focusedOptionIndex ? name + 'Option' + focusedOptionIndex : null"
                    tabindex="-1"
                    class="py-1 overflow-auto text-base leading-6 rounded-md shadow-xs max-h-60 focus:outline-none sm:text-sm sm:leading-5">

                    <template x-for="(value, key) in getlist()" :key="key">
                        <li @click="select(value, key)"
                            :class="{ 'bg-gray-100': isselected(key), 'text-white bg-gray-300': focusedOptionIndex == key }"
                            class="relative py-1 pl-3 mb-1 text-gray-900 select-none pr-9 hover:bg-gray-100 cursor-pointer rounded-md">
                            <span x-text="value" class="block font-normal truncate"></span>
                            <span x-text="key" class="block font-normal truncate"></span>
                            <span x-show="isselected(key)"
                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-700">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function selectmenu(datalist) {
            return {
                state: false,
                filter: '',
                list: datalist,
                selectedkey: null,
                selectedlabel: null,
                focusedOptionIndex: null,
                toggle: function() {
                    this.state = !this.state;
                    this.filter = '';
                },
                close: function() {
                    this.state = false;
                },
                select: function(value, key) {
                    if (this.selectedkey == key) {
                        this.selectedlabel = null;
                        this.selectedkey = null;
                    } else {
                        this.selectedlabel = value;
                        this.selectedkey = key;
                        this.state = false;
                    }

                    this.focusedOptionIndex = Object.keys(this.list).indexOf(key);
                    console.log("Value : " + value + ", Key :" + key + ", Index : " + this.focusedOptionIndex);
                },
                isselected: function(key) {
                    return this.selectedkey == key;
                },
                getlist: function() {
                    if (this.filter == '') {
                        return this.list;
                    }
                    var filtered = Object.entries(this.list).filter(([key, value]) => value.toLowerCase().includes(this
                        .filter.toLowerCase()));

                    var result = Object.fromEntries(filtered);
                    return result;
                },
                focusNextOption: function() {
                    if (this.focusedOptionIndex === null) return this.focusedOptionIndex = Object.keys(this.list)
                        .length - 1;
                    if (this.focusedOptionIndex + 1 >= Object.keys(this.list).length) return;
                    this.focusedOptionIndex++;
                    this.$refs.listbox.children[this.focusedOptionIndex].scrollIntoView({
                        block: "center",
                    });

                    console.log(this.focusedOptionIndex);
                },
                focusPreviousOption: function() {
                    if (this.focusedOptionIndex === null) return this.focusedOptionIndex = 0;
                    if (this.focusedOptionIndex <= 0) return;
                    this.focusedOptionIndex--;
                    this.$refs.listbox.children[this.focusedOptionIndex].scrollIntoView({
                        block: "center",
                    });
                },
                onEscape: function() {
                    this.close();
                    this.$refs.filterinput.focus();
                },
                selectOption: function() {
                    if (!this.state) return this.toggle();
                    this.selectedkey = Object.keys(this.list)[this.focusedOptionIndex];
                    this.selectedlabel = this.list[this.selectedkey];
                    this.state = false;
                },
            };
        }

        function datalist() {
            return {
                AF: 'Afghanistan',
                AX: 'Aland Islands',
                AL: 'Albania',
                DZ: 'Algeria',
                AS: 'American Samoa',
                AD: 'Andorra',
                AO: 'Angola',
                AI: 'Anguilla',
            };
        }
    </script>


    <div class="w-full max-w-xs mt-6">
        <div class="space-y-1" x-data="Components.customSelect({ open: false, value: 1, selected: 1 })" x-init="init()">
            <label class="block text-sm leading-5 font-medium text-gray-100">Assigned
                to</label>
            <div class="relative">
                <span class="inline-block w-full rounded-md shadow-sm">
                    <button x-ref="button" @click="onButtonClick()" type="button" aria-haspopup="listbox"
                        :aria-expanded="open"
                        class="cursor-default relative w-full rounded-xs border border-gray-300 bg-white pl-3 pr-10 py-2 text-left focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                        <div class="flex items-center space-x-3">
                            <span class="block truncate text-gray-300">Seleccionar user</span>
                        </div>
                        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                    clip-rule="evenodd" fill-rule="evenodd"></path>
                            </svg>
                        </span>
                    </button>
                </span>
                <div x-show="open" @focusout="onEscape()" @click.away="open = false"
                    x-description="Select popover, show/hide based on select state."
                    x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="absolute mt-1 w-full rounded-md bg-white shadow-lg z-50"
                    style="display: none;">
                    <ul @keydown.enter.stop.prevent="onOptionSelect()" @keydown.space.stop.prevent="onOptionSelect()"
                        @keydown.escape="onEscape()" @keydown.arrow-up.prevent="onArrowUp()"
                        @keydown.arrow-down.prevent="onArrowDown()" x-ref="listbox" tabindex="-1" role="listbox"
                        aria-labelledby="assigned-to-label" :aria-activedescendant="activeDescendant"
                        class="max-h-56 rounded-md py-1 text-base leading-6 shadow-xs overflow-auto focus:outline-none sm:text-sm sm:leading-5">
                        <li id="assigned-to-option-1" role="option" @click="choose(1)" @mouseenter="selected = 1"
                            @mouseleave="selected = null"
                            :class="{ 'text-white bg-next-600': selected === 1, 'text-gray-900': !(selected === 1) }"
                            class="text-gray-900 cursor-default select-none relative py-2 pl-4 pr-9">
                            <div class="flex items-center space-x-3">
                                <span :class="{ 'font-semibold': value === 1, 'font-normal': !(value === 1) }"
                                    class="font-normal block truncate">
                                    Devon Webb
                                </span>
                            </div>
                            <span x-show="value === 1"
                                :class="{ 'text-white': selected === 1, 'text-next-600': !(selected === 1) }"
                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600"
                                style="display: none;">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </li>
                        <li id="assigned-to-option-2" role="option" @click="choose(2)" @mouseenter="selected = 2"
                            @mouseleave="selected = null"
                            :class="{ 'text-white bg-next-600': selected === 2, 'text-gray-900': !(selected === 2) }"
                            class="bg-next-600 text-white cursor-default select-none relative py-2 pl-4 pr-9">
                            <div class="flex items-center space-x-3">
                                <span :class="{ 'font-semibold': value === 2, 'font-normal': !(value === 2) }"
                                    class="font-semibold block truncate">
                                    Tom Cook
                                </span>
                            </div>
                            <span x-show="value === 2"
                                :class="{ 'text-white': selected === 2, 'text-next-600': !(selected === 2) }"
                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-white">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>



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
        document.addEventListener("alpine:init", () => {
            Alpine.data("select", () => ({
                open: false,
                language: "",

                toggle() {
                    this.open = !this.open;
                },

                setLanguage(val) {
                    this.language = val;
                    this.open = false;
                },
            }));
        });


        function select2Alpine() {
            this.select2 = $(this.$refs.select).select2();
            this.select2.on("select2:select", (event) => {
                this.selectedCity = event.target.value;
            });
            this.$watch("selectedCity", (value) => {
                this.select2.val(value).trigger("change");
            });
        }

        window.Components = {
            customSelect(options) {
                return {
                    init() {
                        this.$refs.listbox.focus()
                        this.optionCount = this.$refs.listbox.children.length
                        this.$watch('selected', value => {
                            if (!this.open) return

                            if (this.selected === null) {
                                this.activeDescendant = ''
                                return
                            }

                            this.activeDescendant = this.$refs.listbox.children[this.selected - 1].id
                        })
                    },
                    activeDescendant: null,
                    optionCount: null,
                    open: false,
                    selected: null,
                    value: 1,
                    choose(option) {
                        this.value = option
                        this.open = false
                    },
                    onButtonClick() {
                        if (this.open) return
                        this.selected = this.value
                        this.open = true
                        this.$nextTick(() => {
                            this.$refs.listbox.focus()
                            this.$refs.listbox.children[this.selected - 1].scrollIntoView({
                                block: 'nearest'
                            })
                        })
                    },
                    onOptionSelect() {
                        if (this.selected !== null) {
                            this.value = this.selected
                        }
                        this.open = false
                        this.$refs.button.focus()
                    },
                    onEscape() {
                        this.open = false
                        this.$refs.button.focus()
                    },
                    onArrowUp() {
                        this.selected = this.selected - 1 < 1 ? this.optionCount : this.selected - 1
                        this.$refs.listbox.children[this.selected - 1].scrollIntoView({
                            block: 'nearest'
                        })
                    },
                    onArrowDown() {
                        this.selected = this.selected + 1 > this.optionCount ? 1 : this.selected + 1
                        this.$refs.listbox.children[this.selected - 1].scrollIntoView({
                            block: 'nearest'
                        })
                    },
                    ...options,
                }
            },
        }

        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOMContent Loaded');
            $('#select2').select2();
        });
    </script>
</x-app-layout>

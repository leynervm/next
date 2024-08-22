@props(['apiEndpoint', 'enableSearch' => true, 'minChars' => 3])
<div x-data="data('{{ $apiEndpoint }}', {{ $enableSearch }}, {{ $minChars }})">
    <div class="flex w-full flex-col" x-on:keydown="handleKeydownOnOptions($event)"
        x-on:keydown.esc.window="isOpen = false, openedWithKeyboard = false">
        <x-label value="Seleccionar producto :" />
        <div class="relative">
            <button type="button"
                class="inline-flex w-full items-center justify-between gap-2 border border-next-300 rounded-lg px-3 pr-6 py-1.5 text-sm font-medium tracking-wide text-colorinput  focus:outline-none transition"
                role="combobox" aria-controls="statesList" aria-haspopup="listbox" x-on:click="isOpen = ! isOpen"
                x-on:keydown.down.prevent="openedWithKeyboard = true"
                x-on:keydown.enter.prevent="openedWithKeyboard = true"
                x-on:keydown.space.prevent="openedWithKeyboard = true"
                x-bind:aria-expanded="isOpen || openedWithKeyboard"
                x-bind:aria-label="value ? selectedOption.text : 'SELECCIONAR'">
                <span class="text-sm leading-normal w-full text-left truncate font-normal"
                    :class="value ? 'text-colorlabel' : 'text-colorsubtitleform'"
                    x-text="value ? selectedOption.text : 'SELECCIONAR...'"></span>
                <x-icon-select />
            </button>

            <input id="state" name="state" autocomplete="off" x-ref="hiddenTextField" hidden="" />
            <div style="display: none;" x-cloak x-show="isOpen || openedWithKeyboard" id="statesList"
                class="absolute left-0 top-0 z-10 w-full overflow-hidden bg-fondoselect2 rounded-lg mt-10 shadow-lg shadow-shadowselect2"
                role="listbox" aria-label="states list" x-on:click.outside="isOpen = false, openedWithKeyboard = false"
                x-on:keydown.down.prevent="$focus.wrap().next()" x-on:keydown.up.prevent="$focus.wrap().previous()"
                x-transition x-trap="openedWithKeyboard">

                <div class="p-1">
                    <div class="relative mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="absolute left-2 top-1/2 w-5 h-5 -translate-y-1/2 text-colorsubtitleform">
                            <path d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <x-input class="w-full block p-2 pl-9 pr-4" name="search" aria-label="Search"
                            @input="getFilteredOptions(search)" x-ref="search" x-model="search" placeholder="Search"
                            autocomplete="off" />
                    </div>

                    <ul class="flex max-h-60 flex-col overflow-y-auto soft-scrollbar">
                        <li class="hidden px-4 py-2 text-sm text-colorlabel " x-ref="noResultsMessage">
                            <span>No se encontraron resultados.</span>
                        </li>

                        <li class="w-full flex items-center rounded-md cursor-pointer p-1.5 min-h-[34px] text-colorlabel hover:bg-fondohoverselect2 focus-visible:border-none focus-visible:bg-fondohoverselect2 focus-visible:outline-none"
                            role="option" x-on:click="reset" :class="(value == '') ? '!bg-fondoactiveselect2' : ''">

                            <div class="w-full text-[10px] sm:text-xs">
                                <p class="leading-normal" x-text="'SELECCIONAR...'">
                                </p>
                            </div>
                        </li>

                        <template x-for="(item, index) in filteredData" x-bind:key="item.value">
                            <li class="w-full flex items-center rounded-md cursor-pointer p-1.5 min-h-[34px] text-colorlabel hover:bg-fondohoverselect2 focus-visible:border-none focus-visible:bg-fondohoverselect2 focus-visible:outline-none"
                                role="option" x-on:click="setSelectedOption(item)"
                                x-on:keydown.enter="setSelectedOption(item)" x-bind:id="'option-' + index"
                                tabindex="0" :class="(value == item.value) ? '!bg-fondoactiveselect2' : ''">

                                <div class="w-full text-[10px] sm:text-xs">
                                    <p class="leading-normal" x-text="item.text" {{-- x-bind:class="value == item.value ? 'font-bold' : null" --}}>
                                    </p>
                                    {{-- <p class="text-colorsubtitleform text-[10px] font-semibold" x-text="item.text">
                                        </p> --}}
                                    <span class="sr-only" x-text="value == item.value ? 'selected' : null"></span>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script>
        function data(apiEndpoint, enableSearch, minChars) {
            return {
                search: '',
                selectedOption: null,
                value: null,
                isOpen: false,
                openedWithKeyboard: false,
                data: null,
                filteredData: [],
                error: '',
                enableSearch: enableSearch,
                minChars: minChars,

                init() {
                    console.log("{{ $attributes->wire('model')->value() }}")
                    this.fetchData();
                },
                fetchData() {
                    this.error = '',
                        fetch(apiEndpoint, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                search: this.search
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data);
                            if (data.error) {
                                this.error = data.error;
                            } else {
                                this.data = data;
                                this.filteredData = data;
                            }
                        })
                        .catch(() => {
                            this.error = 'There was an error processing your request.';
                        });
                },
                setSelectedOption(option) {
                    this.selectedOption = option
                    this.value = option.value
                    this.isOpen = false
                    this.openedWithKeyboard = false
                    this.$refs.hiddenTextField.value = option.value
                },
                getFilteredOptions(query) {
                    this.filteredData = this.data.filter((product) =>
                        product.text.toLowerCase().includes(query.toLowerCase()));

                    if (this.filteredData.length === 0) {
                        this.$refs.noResultsMessage.classList.remove('hidden');
                    } else {
                        this.$refs.noResultsMessage.classList.add('hidden');
                    }
                },
                handleKeydownOnOptions(event) {
                    // if the user presses backspace or the alpha-numeric keys, focus on the search field
                    if ((event.keyCode >= 65 && event.keyCode <= 90) || (event.keyCode >= 48 &&
                            event.keyCode <= 57) || event.keyCode === 8) {
                        this.$refs.search.focus()
                    }
                },
                reset() {
                    this.selectedOption = null
                    this.value = null
                    this.isOpen = false
                    // this.openedWithKeyboard = false
                    // this.$refs.hiddenTextField.value = option.value
                }
            }
        }
    </script>
</div>

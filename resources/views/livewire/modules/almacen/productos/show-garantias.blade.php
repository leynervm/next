<div class="w-full flex flex-col gap-8">
    <x-form-card titulo="GARANTÍAS" subtitulo="Agregar garantías de proteccion del producto." class="relative">
        <div class="w-full flex flex-wrap lg:flex-nowrap gap-3" x-data="{ loading: false }">
            <div x-show="loading" wire:loading.flex wire:target="save, delete, render, " class="loading-overlay rounded">
                <x-loading-next />
            </div>
            <div class="w-full lg:w-80 xl:w-96 lg:flex-shrink-0 bg-body p-3 rounded">
                <form wire:submit.prevent="save" class="w-full" x-data="datagarantia">
                    <div class="w-full">
                        <x-label value="Garantías disponibles :" />
                        <div class="relative" id="parenttypegarantia_id" wire:ignore>
                            <x-select class="block w-full select2" x-ref="select" id="typegarantia_id"
                                data-placeholder="null">
                                <x-slot name="options">
                                    @if (count($typegarantias))
                                        @foreach ($typegarantias as $item)
                                            <option value="{{ $item->id }}" data-time="{{ $item->time }}">
                                                {{ $item->name }} -
                                                @if ($item->datecode == 'MM')
                                                    MESES
                                                @else
                                                    AÑOS
                                                @endif
                                            </option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="typegarantia_id" />
                    </div>

                    <div class="w-full mt-2">
                        <x-label value="Tiempo garantía :" />
                        <x-input class="block w-full" x-model="time" type="number" placeholder="Meses garantía..." />
                        <x-jet-input-error for="time" />
                    </div>

                    <div class="w-full pt-4 flex justify-end">
                        <x-button type="submit" wire:loading.atrr="disabled">
                            REGISTRAR
                        </x-button>
                    </div>
                </form>
            </div>
            @if (count($producto->garantiaproductos))
                <div class="w-full flex flex-wrap gap-2">
                    @foreach ($producto->garantiaproductos as $item)
                        @php
                            if ($item->typegarantia->datecode == 'MM') {
                                $timestring = $item->time > 1 ? ' MESES' : ' MES';
                            } else {
                                $timestring = $item->time > 1 ? ' AÑOS' : ' AÑO';
                            }
                        @endphp

                        <x-minicard size="lg" :title="$item->typegarantia->name" :content="$item->time . $timestring" class="!bg-body">
                            {{-- <span class="absolute right-2 -top-3 w-4 h-4 block text-next-500 ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 320 512"
                                    fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M310.6 246.6l-127.1 128C176.4 380.9 168.2 384 160 384s-16.38-3.125-22.63-9.375l-127.1-128C.2244 237.5-2.516 223.7 2.438 211.8S19.07 192 32 192h255.1c12.94 0 24.62 7.781 29.58 19.75S319.8 237.5 310.6 246.6z" />
                                </svg>
                            </span>

                            <span
                                class="absolute right-0 -top-8 bg-next-500 text-white text-[10px] font-semibold tracking-widest p-1.5 px-2 rounded-sm">
                                {{ $item->time . $timestring }}</span> --}}

                            <span class="block mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-colorlinknav"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M10.014 2C6.23617 2 4.34725 2 3.17362 3.17157C2 4.34315 2 6.22876 2 10C2 13.7712 2 15.6569 3.17362 16.8284C4.34725 18 6.23617 18 10.014 18H14.021C17.7989 18 19.6878 18 20.8614 16.8284C21.671 16.0203 21.9221 14.8723 22 13" />
                                    <path d="M12 18V22" />
                                    <path d="M8 22H16" />
                                    <path d="M11 15H13" />
                                    <path
                                        d="M17.4991 2C16.0744 2 15.1506 2.90855 14.0581 3.23971C13.6138 3.37436 13.3917 3.44168 13.3018 3.53659C13.2119 3.6315 13.1856 3.77019 13.133 4.04756C12.5696 7.0157 13.801 9.75979 16.7375 10.8279C17.053 10.9426 17.2108 11 17.5007 11C17.7906 11 17.9484 10.9426 18.2639 10.8279C21.2002 9.75978 22.4304 7.01569 21.8669 4.04756C21.8142 3.77014 21.7879 3.63143 21.698 3.53652C21.6081 3.44161 21.386 3.37432 20.9418 3.23974C19.8488 2.90862 18.9239 2 17.4991 2Z" />
                                </svg>
                            </span>

                            <x-slot name="buttons">
                                <x-button-delete
                                    wire:click="$emit('producto.confirmDeleteGarantia',{{ $item }})"
                                    wire:loading.attr="disabled" />
                            </x-slot>
                        </x-minicard>
                    @endforeach
                </div>
            @endif
        </div>
    </x-form-card>

    @if (mi_empresa()->uselistprice)
        <x-form-card titulo="PRECIOS VENTA" subtitulo="Personalizar precios de venta según su preferencia.">
            <div class="w-full flex flex-wrap gap-2">
                @if (count($pricetypes))
                    @foreach ($pricetypes as $lista)
                        @php
                            $precios = \App\Helpers\GetPrice::getPriceProducto($producto, $lista->id, mi_empresa()->tipocambio)->getData();
                            // var_dump($precios);
                        @endphp

                        <div
                            class="w-full xs:w-48 flex flex-col items-center justify-between bg-body rounded-lg shadow p-1">
                            <div class="text-center">
                                <x-span-text :text="$lista->name" class="leading-3 !tracking-normal" />

                                <div class="text-center relative pt-1">
                                    @if ($precios->pricemanual)
                                        <x-span-text :text="'SUGERIDO : S/.' .
                                            number_format($precios->oldPrice, $lista->decimals, '.', ', ')"
                                            class="bg-red-100 !text-red-500 font-semibold leading-3" />
                                    @endif

                                    @if ($precios->pricemanual)
                                        <p class="text-green-500 text-sm">
                                            S/. {{ number_format($precios->pricemanual, 2, '.', ', ') }}</p>
                                    @else
                                        @if ($precios->existsrango)
                                            <p class="text-green-500 text-sm">
                                                S/. {{ number_format($precios->pricesale, 2, '.', ', ') }}</p>
                                        @else
                                            <p class="mx-auto">
                                                <x-span-text text="RANGO DE PRECIO NO DISPONIBLE"
                                                    class="!tracking-normal leading-3" type="red" />
                                            </p>
                                        @endif
                                    @endif
                                </div>

                                @if ($precios->existsrango)
                                    @if (mi_empresa()->viewpricedolar)
                                        @if (mi_empresa()->tipocambio > 0)
                                            <div class="text-center">
                                                <x-span-text :text="'$. ' .
                                                    number_format(
                                                        $precios->pricewithdescountDolar ?? $precios->priceDolar,
                                                        $lista->decimals,
                                                        '.',
                                                        ', ',
                                                    )"
                                                    class="font-semibold leading-3 !tracking-normal" type="blue" />
                                            </div>
                                        @else
                                            <div class="text-center">
                                                <x-span-text text="TIPO CAMBIO NO CONFIGURADO"
                                                    class="leading-3 !tracking-normal" type="red" />
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            </div>

                            <div class="w-full flex justify-end">
                                <x-button-edit wire:click="cambiarprecioventa({{ $lista->id }})"
                                    wire:loading.attr="disabled" />
                            </div>
                        </div>
                    @endforeach
                @else
                    <x-span-text text="NO EXISTEN LISTAS DE PRECIOS REGISTRADOS..." />
                @endif
            </div>
        </x-form-card>
    @endif

    {{-- {{ $producto->detalleproducto->descripcion }} --}}

    <x-form-card titulo="DETALLE PRODUCTO">
        <div class="w-full">
            <form wire:submit.prevent="savedetalle" class="w-full">
                <div wire:ignore>
                    <x-textarea class="w-full" id="descripcionproducto" wire:model.defer="descripcion" rows="6">
                    </x-textarea>
                </div>

                <x-jet-input-error for="descripcion" />
                <x-jet-input-error for="producto.id" />

                <div class="mt-3 flex justify-end">
                    <x-button type="submit" wire:loading.atrr="disabled">
                        @if ($producto->detalleproducto)
                            ACTUALIZAR
                        @else
                            REGISTRAR
                        @endif
                    </x-button>
                </div>
            </form>
        </div>
    </x-form-card>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Cambiar precio venta') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="saveprecioventa" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Lista precio :" />
                    <x-disabled-text :text="$pricetype->name" />
                    <x-jet-input-error for="pricetype.id" />
                </div>

                <div>
                    <x-label value="Precio venta sugerido :" />
                    <x-disabled-text :text="$priceold ?? '0.00'" />
                </div>

                <div>
                    <x-label value="Precio venta manual :" />
                    <x-input class="block w-full" wire:model.defer="newprice" type="number" min="0"
                        step="0.01" />
                    <x-jet-input-error for="newprice" />
                    <x-jet-input-error for="producto.id" />
                </div>

                <div class="mt-3 flex gap-2 justify-end">
                    @if ($pricemanual)
                        <x-button wire:click="deletepricemanual" wire:key="deletepricemanual{{ $producto->id }}"
                            wire:loading.attr="disabled">
                            ELIMINAR PRECIO MANUAL</x-button>
                    @endif
                    <x-button type="submit" wire:loading.atrr="disabled">
                        ACTUALIZAR
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script src="{{ asset('assets/ckeditor5/ckeditor5_38.1.1_super-build_ckeditor.js') }}"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('datagarantia', () => ({
                typegarantia_id: @entangle('typegarantia_id').defer,
                time: @entangle('time').defer,
                
                init() {
                    this.select2GarantiaAlpine();
                    window.addEventListener('created', () => {
                        this.select2.val(this.typegarantia_id).trigger("change");
                    })
                },
                select2GarantiaAlpine() {
                    this.select2 = $(this.$refs.select).select2();
                    this.select2.val(this.typegarantia_id).trigger("change");
                    this.select2.on("select2:select", (event) => {
                        this.typegarantia_id = event.target.value;
                        this.getTimegarantia(event.target);
                    }).on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    });
                },
                getTimegarantia(target) {
                    let time = target.options[target.selectedIndex].getAttribute('data-time');
                    this.time = time;
                },
            }));
        })

        document.addEventListener("livewire:load", () => {

            Livewire.on("producto.confirmDeleteGarantia", data => {
                swal.fire({
                    title: 'Eliminar garantía del producto !',
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.delete(data);
                        // Livewire.emitTo('almacen.productos.show-garantias', 'delete', data);
                    }
                })
            });
           
            CKEDITOR.ClassicEditor.create(document.getElementById("descripcionproducto"), {
                toolbar: {
                    items: [
                        'undo', 'redo', '|',
                        'exportPDF', 'exportWord', '|',
                        'findAndReplace', 'selectAll', '|',
                        'heading', '|',
                        'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript',
                        'superscript', 'removeFormat', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'outdent', 'indent', '|',
                        // '-',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight',
                        '|',
                        'alignment', '|',
                        'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed',
                        'codeBlock',
                        'htmlEmbed', '|',
                        'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                        'textPartLanguage', '|',
                        'sourceEditing'
                    ],
                    shouldNotGroupWhenFull: true
                },
                language: 'es',
                list: {
                    properties: {
                        styles: true,
                        startIndex: true,
                        reversed: true
                    }
                },
                heading: {
                    options: [{
                            model: 'paragraph',
                            title: 'Paragraph',
                            class: 'ck-heading_paragraph'
                        },
                        {
                            model: 'heading1',
                            view: 'h1',
                            title: 'Heading 1',
                            class: 'ck-heading_heading1'
                        },
                        {
                            model: 'heading2',
                            view: 'h2',
                            title: 'Heading 2',
                            class: 'ck-heading_heading2'
                        },
                        {
                            model: 'heading3',
                            view: 'h3',
                            title: 'Heading 3',
                            class: 'ck-heading_heading3'
                        },
                        {
                            model: 'heading4',
                            view: 'h4',
                            title: 'Heading 4',
                            class: 'ck-heading_heading4'
                        },
                        {
                            model: 'heading5',
                            view: 'h5',
                            title: 'Heading 5',
                            class: 'ck-heading_heading5'
                        },
                        {
                            model: 'heading6',
                            view: 'h6',
                            title: 'Heading 6',
                            class: 'ck-heading_heading6'
                        }
                    ]
                },
                placeholder: 'Ingresar contenido...',
                fontFamily: {
                    options: [
                        'default',
                        'Arial, Helvetica, sans-serif',
                        'Courier New, Courier, monospace',
                        'Georgia, serif',
                        'Lucida Sans Unicode, Lucida Grande, sans-serif',
                        'Tahoma, Geneva, sans-serif',
                        'Times New Roman, Times, serif',
                        'Trebuchet MS, Helvetica, sans-serif',
                        'Verdana, Geneva, sans-serif'
                    ],
                    supportAllValues: true
                },
                fontSize: {
                    options: [8, 9, 10, 11, 12, 13, 14, 'default', 18, 20, 22],
                    supportAllValues: true
                },
                htmlSupport: {
                    allow: [{
                        name: /.*/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }]
                },
                htmlEmbed: {
                    showPreviews: true
                },
                link: {
                    decorators: {
                        addTargetToExternalLinks: true,
                        defaultProtocol: 'https://',
                        toggleDownloadable: {
                            mode: 'manual',
                            label: 'Downloadable',
                            attributes: {
                                download: 'file'
                            }
                        }
                    }
                },
                mention: {
                    feeds: [{
                        marker: '@',
                        feed: [
                            '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy',
                            '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                            '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake',
                            '@gingerbread', '@gummi', '@ice', '@jelly-o',
                            '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum',
                            '@pudding', '@sesame', '@snaps', '@soufflé',
                            '@sugar', '@sweet', '@topping', '@wafer'
                        ],
                        minimumCharacters: 1
                    }]
                },
                // The "super-build" contains more premium features that require additional configuration, disable them below.
                // Do not turn them on unless you read the documentation and know how to configure them and setup the editor.
                removePlugins: [
                    // These two are commercial, but you can try them out without registering to a trial.
                    // 'ExportPdf',
                    // 'ExportWord',
                    'CKBox',
                    'CKFinder',
                    'EasyImage',
                    // This sample uses the Base64UploadAdapter to handle image uploads as it requires no configuration.
                    // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/base64-upload-adapter.html
                    // Storing images as Base64 is usually a very bad idea.
                    // Replace it on production website with other solutions:
                    // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/image-upload.html
                    // 'Base64UploadAdapter',
                    'RealTimeCollaborativeComments',
                    'RealTimeCollaborativeTrackChanges',
                    'RealTimeCollaborativeRevisionHistory',
                    'PresenceList',
                    'Comments',
                    'TrackChanges',
                    'TrackChangesData',
                    'RevisionHistory',
                    'Pagination',
                    'WProofreader',
                    // Careful, with the Mathtype plugin CKEditor will not load when loading this sample
                    // from a local file system (file://) - load this site via HTTP server if you enable MathType.
                    'MathType',
                    // The following features are part of the Productivity Pack and require additional license.
                    'SlashCommand',
                    'Template',
                    'DocumentOutline',
                    'FormatPainter',
                    'TableOfContents'
                ],

                locale: {
                    dateTimeFormat: date => format(date, 'dd/MM/yyyy')
                },

                table: {
                    // Agregar el filtro
                    addClassToAllCells: true, // Para agregar la clase a todas las celdas de la tabla
                    class: 'w-full bg-next-500' // Nombre de la clase que se agregará a las tablas
                }

                // height: '500px',

            }).then(function(editor) {
                // editor.config.height = '500px';
                // editor.ui.view.editable.element.style.height = '500px';
                editor.model.document.on("change:data", () => {
                    @this.set('descripcion', editor.getData());
                });

                editor.setData(@this.get('descripcion'));
                //PARA ESCUCHAR EVENTOS CON EMIT
                // Livewire.on("resetCKEditor", () => {
                //     console.log("Reset CKEditor");
                // });


            }).catch(error => {
                console.log(error);
            });

            
        })
    </script>
</div>

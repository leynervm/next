<div class="w-full flex flex-col gap-8">
    <x-form-card titulo="GARANTÍAS" subtitulo="Agregar garantías de proteccion del producto.">
        <div class="w-full flex flex-wrap lg:flex-nowrap gap-3 relative" x-data="{ loading: false }">
            <div x-show="loading" wire:loading.flex wire:target="save, delete, render, " class="loading-overlay rounded">
                <x-loading-next />
            </div>
            <div class="w-full lg:w-80 xl:w-96 lg:flex-shrink-0 bg-body p-3 rounded">
                <form wire:submit.prevent="save" class="w-full">
                    <div class="w-full">
                        <x-label value="Garantías disponibles :" />
                        <div class="relative" x-data="{ typegarantia_id: @entangle('typegarantia_id') }" x-init="select2GarantiaAlpine" id="parenttypegarantia_id"
                            wire:ignore>
                            <x-select class="block w-full select2" x-ref="select" id="typegarantia_id">
                                <x-slot name="options">
                                    @if (count($typegarantias))
                                        @foreach ($typegarantias as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="typegarantia_id" />
                    </div>

                    <div class="w-full mt-2">
                        <x-label value="Tiempo garantía (Meses) :" />
                        <x-input class="block w-full" wire:model.defer="time" type="number" />
                        <x-jet-input-error for="time" />
                        <x-jet-input-error for="producto.id" />
                    </div>

                    <div class="w-full pt-4 flex justify-end">
                        <x-button type="submit" wire:loading.atrr="disabled">
                            REGISTRAR
                        </x-button>
                    </div>
                </form>
            </div>
            @if (count($producto->garantiaproductos))
                <div class="w-full flex flex-wrap gap-3 bg-body p-3 rounded">
                    @foreach ($producto->garantiaproductos as $item)
                        <x-minicard size="lg" :title="$item->typegarantia->name">
                            <span class="absolute right-2 -top-3 w-4 h-4 block text-red-500 ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 320 512"
                                    fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M310.6 246.6l-127.1 128C176.4 380.9 168.2 384 160 384s-16.38-3.125-22.63-9.375l-127.1-128C.2244 237.5-2.516 223.7 2.438 211.8S19.07 192 32 192h255.1c12.94 0 24.62 7.781 29.58 19.75S319.8 237.5 310.6 246.6z" />
                                </svg>
                            </span>
                            <span
                                class="absolute right-0 -top-8 bg-red-500 text-white text-[10px] font-semibold tracking-widest p-1.5 px-2 rounded-sm">
                                {{ $item->time }}{{ $item->time > 1 ? 'MESES' : 'MES' }}</span>

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

    <x-form-card titulo="PRECIOS VENTA" subtitulo="Personalizar precios de venta según su preferencia.">
        <div class="w-full flex flex-wrap gap-2">
            @if (count($pricetypes))
                @foreach ($pricetypes as $lista)
                    @php
                        $precios = \App\Helpers\GetPrice::getPriceProducto($producto, $lista->id)->getData();
                    @endphp

                    <div
                        class="w-full xs:w-48 flex flex-col items-center justify-between bg-body rounded-lg shadow p-1">
                        <div class="text-center">
                            <x-span-text :text="$lista->name" />

                            <div class="text-center relative pt-1">
                                @if ($precios->pricemanual)
                                    <x-span-text :text="'SUGERIDO : S/.' . number_format($precios->oldPrice, 2, '.', ', ')"
                                        class="bg-red-100 !text-red-500 font-semibold leading-3" />
                                @endif
                                <p class="text-green-500 text-sm">
                                    S/. {{ number_format($precios->pricesale, 2, '.', ', ') }}</p>
                            </div>
                        </div>

                        <div class="w-full flex justify-end">
                            <x-button-edit wire:click="cambiarprecioventa({{ $lista->id }})"
                                wire:loading.attr="disabled" />
                        </div>
                    </div>
                @endforeach
            @else
                <small class="text-red-500 bg-red-50 text-xs p-0.5 rounded font-semibold inline-block mt-1">
                    Configurar lista de precios
                    <a class="underline px-1" href="#">REGISTRAR</a>
                </small>
            @endif
        </div>
    </x-form-card>

    <x-form-card titulo="DESCRIPCIÓN PRODUCTO">
        <div class="w-full">
            <form wire:submit.prevent="savedetalle" class="w-full">
                <div>
                    <x-label value="Título :" />
                    <x-input class="block w-full" wire:model.defer="titulo" />
                    <x-jet-input-error for="titulo" />
                </div>

                <div wire:ignore>
                    <x-label value="Descripción :" class="mt-2" />
                    <x-textarea class="w-full" id="descripcionproducto" wire:model.defer="descripcion" rows="6">
                    </x-textarea>
                </div>

                <x-jet-input-error for="descripcion" />
                <x-jet-input-error for="producto.id" />

                <div class="mt-3 flex justify-end">
                    <x-button type="submit" wire:loading.atrr="disabled">
                        REGISTRAR
                    </x-button>
                </div>
            </form>

            @if (count($producto->detalleproductos))
                <div class="w-full lg:grid lg:grid-cols-2 gap-3 mt-3">
                    @foreach ($producto->detalleproductos as $item)
                        <div class="w-full rounded shadow border border-borderminicard hover:shadow-lg">
                            <p
                                class="font-semibold p-1 text-xs text-center bg-fondospancardproduct text-textspancardproduct">
                                {{ $item->title }}</p>
                            <div class="">
                                {!! $item->descripcion !!}
                            </div>

                            <div class="flex gap-1 justify-end mt-1">
                                <x-button-edit></x-button-edit>
                                <x-button-delete></x-button-delete>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
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
                    <x-disabled-text :text="$priceold" />
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
        function select2GarantiaAlpine() {

            this.select2 = $(this.$refs.select).select2();
            this.select2.val(this.typegarantia_id).trigger("change");
            this.select2.on("select2:select", (event) => {
                this.select2.attr('disabled', true);
                this.typegarantia_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch('typegarantia_id', (value) => {
                this.select2.val(value).trigger("change");
            });
        }

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
                    class: 'w-full' // Nombre de la clase que se agregará a las tablas
                }

                // height: '500px',

            }).then(function(editor) {
                // editor.config.height = '500px';
                // editor.ui.view.editable.element.style.height = '500px';
                editor.model.document.on("change:data", () => {
                    @this.set('descripcion', editor.getData());
                });

                //PARA ESCUCHAR EVENTOS CON EMIT
                Livewire.on("resetCKEditor", () => {
                    console.log("Reset CKEditor");
                    editor.setData('');
                });

                //PARA ESCUCHAR EVENTOS CON DISPATCHBROWSEREVENT
                // window.addEventListener("resetCKEditor", () => {
                //     console.log("Reset CKEditor");
                //     editor.setData('');
                // });
            }).catch(error => {
                console.log(error);
            });
        })
    </script>
</div>

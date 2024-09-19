<div id="cookies" class="fixed bottom-0 p-2 inset-x-0 z-[199] w-full overflow-hidden" style="display: none;">
    <div class="w-full bg-neutral-800 bg-opacity-90 p-3 rounded-lg">
        <div class="w-full contenedor flex gap-2 items-center">
            <div class="w-full flex-1">
                <p class="text-neutral-300 leading-normal text-xs">
                    Al hacer clic en “Aceptar todas las cookies”, usted acepta que las cookies se guarden en su
                    dispositivo para mejorar la navegación del sitio, analizar el uso del mismo, y colaborar con
                    nuestros estudios para marketing.</p>
            </div>
            <div class="w-full sm:w-[40%]">
                <div class="w-full flex items-center gap-5 justify-end">
                    <a class="inline-block uppercase p-2.5 text-[10px] rounded-lg bg-transparent ring-1 ring-white text-white font-medium"
                        href="{{ route('policy.show') }}">Administrar Cookies</a>
                    <button
                        class="inline-block uppercase p-2.5 text-[10px] rounded-lg bg-transparent ring-1 ring-white bg-white font-medium"
                        @click.prevent="aceptar">Aceptar todo</button>
                </div>
            </div>
        </div>
    </div>
</div>

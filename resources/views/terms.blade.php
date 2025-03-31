<x-app-layout>
    <div class="min-h-screen flex flex-col items-center pt-0 md:py-8 sm:pt-0">

        {{-- @if ($empresa->logo)
            <div class="w-full max-w-60 py-3">
                <img class="block w-full h-auto object-scale-down"
                    src="{{ getLogoEmpresa($empresa->logo, request()->isSecure() ? true : false) }}" alt="">
            </div>
        @endif --}}

        <div
            class="w-full text-sm text-colorsubtitleform sm:max-w-4xl p-2 sm:p-6 lg:bg-fondominicard lg:shadow-md overflow-hidden sm:rounded-lg prose">
            {{-- {!! $terms !!} --}}

            <h1 class="text-primary text-sm md:text-xl text-center">
                Términos y Condiciones de Uso
            </h1>

            <h1 class="text-primary text-sm md:text-xl text-center">
                Aceptación de los Términos
            </h1>

            <p class="text-justify">
                Al acceder y utilizar el sitio web www.next.net.pe (en adelante, "el Sitio"), propiedad de
                <b> NEXT TECHNOLOGIES E.I.R.L.</b> (en adelante, "la Empresa"), aceptas cumplir con estos Términos y
                Condiciones, así como con nuestra Política de Privacidad.
            </p>
            <p class="text-justify">
                Si no estás de acuerdo con alguno de los términos establecidos, te pedimos que no utilices nuestro
                Sitio.
            </p>

            <h1 class="text-primary text-sm md:text-xl text-center">
                Descripción del Servicio
            </h1>

            <p class="text-justify">
                El Sitio ofrece la venta de productos electrónicos, tales como Laptops, Pc's de Escritorios,
                Impresoras, Acesorios, Cámaras de seguridad, Servicio y reparación de equipos electrónicos, etc, a
                través de una plataforma de comercio electrónico. Los usuarios pueden realizar compras, revisar
                información sobre los productos y utilizar nuestra pasarela de pagos para efectuar transacciones.
            </p>

            <h1 class="text-primary text-sm md:text-xl text-center">
                Uso del Sitio Web
            </h1>

            <p class="text-justify">
                El Sitio está destinado exclusivamente para realizar compras de productos electrónicos en línea. Al
                utilizar el Sitio, te comprometes a no realizar ningún acto que interfiera con su funcionamiento o
                que viole la ley.
            </p>

            <h1 class="text-primary text-sm md:text-xl text-center">
                Registro de Usuario y Cuenta
            </h1>

            <p class="text-justify">
                Para realizar compras en el Sitio, es posible que se requiera crear una cuenta. Al registrarte,
                debes proporcionar información precisa y actualizada, como tu nombre, dirección de correo
                electrónico y datos de pago. Eres responsable de mantener la confidencialidad de tu cuenta y la
                información relacionada.
            </p>
            <p class="text-justify">
                La Empresa no se hace responsable por actividades no autorizadas en tu cuenta.
            </p>

            <h1 class="text-primary text-sm md:text-xl text-center">
                Productos y Precios
            </h1>

            <ul class="list-disc">
                <li>
                    Todos los productos listados en el Sitio están sujetos a disponibilidad.
                </li>
                <li>
                    Los precios están disponibles en SOLES.
                </li>
                <li>
                    incluyen impuestos aplicables (si los hay), y pueden estar sujetos a cambios sin previo aviso.
                </li>
                <li>
                    La Empresa se reserva el derecho de modificar precios, características y disponibilidad de
                    productos en cualquier momento.
                </li>
            </ul>

            <h1 class="text-primary text-sm md:text-xl text-center">
                Proceso de Compra
            </h1>

            <p class="text-justify">
                Al realizar una compra en el Sitio, el usuario acepta que los productos seleccionados serán
                procesados para su envío de acuerdo con las políticas de la Empresa. El proceso de compra se
                realizará a través de la pasarela de pagos segura, la cual procesará tu información de pago de
                acuerdo con los estándares de seguridad.
            </p>

            <h1 class="text-primary text-sm md:text-xl text-center">
                Métodos de Pago
            </h1>

            <p class="text-justify">
                La Empresa acepta diversos métodos de pago a través de su pasarela de pagos, incluyendo tarjetas de
                crédito y débito, y otros métodos electrónicos habilitados en el sitio. Al realizar el pago, el
                usuario autoriza a la Empresa a procesar la transacción de acuerdo con la información proporcionada.
            </p>

            <h1 class="text-primary text-sm md:text-xl text-center">
                Envío y Entrega
            </h1>

            <p class="text-justify">
                Una vez confirmada la compra y procesado el pago, la Empresa procederá al envío de los productos
                adquiridos. Los tiempos de entrega pueden variar dependiendo de la ubicación y el tipo de producto.
                Los gastos de envío se calcularán durante el proceso de compra.
            </p>

            <h1 class="text-primary text-sm md:text-xl text-center">
                Política de Devoluciones y Reembolsos
            </h1>

            <p class="text-justify">
                Si no quedas satisfecho con tu compra, puedes devolver los productos de acuerdo con nuestra Política
                de Devoluciones y Reembolsos. Para poder realizar una devolución, los productos deben encontrarse en
                su estado original, sin haber sido utilizados ni dañados. Las devoluciones estarán sujetas a ciertas
                condiciones y plazos establecidos por la Empresa.
            </p>

            <h1 class="text-primary text-sm md:text-xl text-center">
                Propiedad Intelectual
            </h1>

            <p class="text-justify">
                Todo el contenido del Sitio, incluidos textos, gráficos, logos, imágenes, videos y otros materiales,
                está protegido por derechos de autor y es propiedad exclusiva de la Empresa o de sus proveedores. El
                uso no autorizado de estos materiales está estrictamente prohibido.
            </p>

            <h1 class="text-primary text-sm md:text-xl text-center">
                Protección de Datos Personales
            </h1>

            <p class="text-justify">
                La Empresa recopila y procesa los datos personales de los usuarios conforme a nuestra Política de
                Privacidad. Esta política explica cómo se recopilan, usan y protegen tus datos cuando utilizas el
                Sitio para realizar compras.
            </p>

            <h1 class="text-primary text-sm md:text-xl text-center">
                Enlaces a Sitios de Terceros
            </h1>

            <p class="text-justify">
                El Sitio puede contener enlaces a sitios web de terceros para ofrecer servicios adicionales o
                información. La Empresa no se hace responsable por el contenido de esos sitios ni por sus políticas
                de privacidad. Te recomendamos leer los términos y condiciones de los sitios de terceros antes de
                utilizarlos.
            </p>

            <h1 class="text-primary text-sm md:text-xl text-center">
                Limitación de Responsabilidad
            </h1>

            <p class="text-justify">
                La Empresa no será responsable por daños directos, indirectos, incidentales o consecuentes que
                puedan surgir del uso del Sitio o de la imposibilidad de acceder o usar los servicios, incluyendo,
                pero no limitado a, la pérdida de datos, ingresos o interrupciones en el negocio.
            </p>

            <h1 class="text-primary text-sm md:text-xl text-center">
                Modificaciones de los Términos
            </h1>

            <p class="text-justify">
                La Empresa se reserva el derecho de modificar estos Términos y Condiciones en cualquier momento. Los
                cambios se harán efectivos cuando sean publicados en el Sitio. Es recomendable revisar regularmente
                estos Términos para estar al tanto de cualquier actualización.
            </p>
        </div>
    </div>
</x-app-layout>

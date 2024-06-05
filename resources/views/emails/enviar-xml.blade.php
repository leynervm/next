<!doctype html>
<html lang="es">

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Enviar correo electrónico</title>
    <style>
        @font-face {
            font-family: "Ubuntu";
            src: url("/assets/fonts/Ubuntu/Ubuntu.eot");
            src: url("/assets/fonts/Ubuntu/Ubuntu.eot?#iefix") format("woff2"),
                url("/assets/fonts/Ubuntu/Ubuntu.woff") format("woff"),
                url("/assets/fonts/Ubuntu/Ubuntu.ttf") format("truetype"),
                url("/assets/fonts/Ubuntu/Ubuntu.svg#Ubuntu") format("svg");
        }

        * {
            font-family: 'Ubuntu';
        }

        body {
            width: 100%;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            font-family: 'Ubuntu';
        }

        .color-next {
            color: #0FB9B9;
        }

        .text-enlace {
            text-decoration: none;
            padding: 10px;
        }

        .thank-you {
            letter-spacing: 0.05rem;
        }

        .text-sm {
            font-size: 10px;
        }

        .text-md {
            font-size: 12px;
        }

        .color-dark {
            color: #606060;
        }


        table {
            border-collapse: collapse;
        }

        td#logo {
            margin: 0 auto;
        }

        img {
            border: none;
            display: block;
        }

        .container-buttons {
            width: 100%;
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: center;
        }

        .btn-email {
            display: inline-flex;
            flex-direction: column;
            justify-content: center;
            gap: 4px;
            align-items: center;
            border-radius: 15px;
            border: 1px solid #a3a3a3;
            padding: 32px 16px;
            font-size: 10px;
            font-weight: bold;
            color: #a3a3a3;
            text-decoration: none;
            text-align: center;
            transition: all ease-in-out .3s;
        }

        .btn-email svg {
            display: block;
            width: 32px;
            height: 32px;
            margin: 0 auto;
        }

        .btn-email:hover {
            border-color: #0FB9B9;
            background-color: #0FB9B9;
            color: #fff;
        }

        .border-complete {
            border-top: 1px solid #dadada;
            border-left: 1px solid #dadada;
            border-right: 1px solid #dadada;
        }

        .border-lr {
            border-left: 1px solid #dadada;
            border-right: 1px solid #dadada;
        }

        #banner-txt {
            color: #fff;
            padding: 15px 32px 0px 32px;
            font-size: 13px;
            text-align: center;
        }

        h2#coupons {
            color: #0FB9B9;
            text-align: center;
            margin-top: 30px;
        }

        p#coupons {
            color: #AAA;
            text-align: center;
            font-size: 12px;
            text-align: justify;
            padding: 0 32px;
        }

        .p-4 {
            padding: 0 12px;
        }

        .m-3 {
            margin: 3px 0;
        }

        .mt-6 {
            margin-top: 9px;
        }

        .tex-left {
            text-align: left;
        }

        .tex-center {
            text-align: center;
        }


        #socials {
            padding-top: 12px;
        }

        p#footer-txt {
            text-align: center;
            color: #FFF;
            font-size: 10px;
            padding: 0 32px;
        }

        #social-icons {
            width: 28%;
        }

        .flex {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: space-between;
            align-content: space-around;
        }



        @media only screen and (max-width: 640px) {
            body[yahoo] .deviceWidth {
                width: 440px !important;
                padding: 0;
            }

            body[yahoo] .center {
                text-align: center !important;
            }

            #social-icons {
                width: 40%;
            }
        }

        @media only screen and (max-width: 479px) {
            body[yahoo] .deviceWidth {
                width: 280px !important;
                padding: 0;
            }

            body[yahoo] .center {
                text-align: center !important;
            }

            #social-icons {
                width: 60%;
            }
        }
    </style>
</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' yahoo='fix'>
    <table width='100%' border='0' cellpadding='0' cellspacing='0' align='center'>
        <tr>
            <td style='text-align: center;'>
                <h4 class='color-next p-4'>
                    DESCARGAR ARCHIVOS AJUNTOS
                    <b>{{ $comprobante->seriecompleta }}</b>
                </h4>

                <div class="container-buttons">
                    <a href="{{ route('download.xml', ['comprobante' => $comprobante, 'type' => 'xml']) }}"
                        class="btn-email">
                        DESCARGAR XML
                        <svg class="" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" fill="none">
                            <path
                                d="M7 13L8.64706 15.5M8.64706 15.5L10.2941 18M8.64706 15.5L10.2941 13M8.64706 15.5L7 18M21 18H20.1765C19.4 18 19.0118 18 18.7706 17.7559C18.5294 17.5118 18.5294 17.119 18.5294 16.3333V13M12.3529 17.9999L12.6946 13.8346C12.7236 13.4813 12.7381 13.3046 12.845 13.2716C12.9518 13.2386 13.0613 13.3771 13.2801 13.6539L14.1529 14.7579C14.2716 14.9081 14.331 14.9831 14.4102 14.9831C14.4893 14.9831 14.5487 14.9081 14.6674 14.7579L15.5407 13.6533C15.7594 13.3767 15.8687 13.2384 15.9755 13.2713C16.0824 13.3042 16.097 13.4807 16.1262 13.8338L16.4706 17.9999" />
                            <path
                                d="M15 22H10.7273C7.46607 22 5.83546 22 4.70307 21.2022C4.37862 20.9736 4.09058 20.7025 3.8477 20.3971C3 19.3313 3 17.7966 3 14.7273V12.1818C3 9.21865 3 7.73706 3.46894 6.55375C4.22281 4.65142 5.81714 3.15088 7.83836 2.44135C9.09563 2 10.6698 2 13.8182 2C15.6173 2 16.5168 2 17.2352 2.2522C18.3902 2.65765 19.3012 3.5151 19.732 4.60214C20 5.27832 20 6.12494 20 7.81818V10" />
                            <path
                                d="M3 12C3 10.1591 4.49238 8.66667 6.33333 8.66667C6.99912 8.66667 7.78404 8.78333 8.43137 8.60988C9.00652 8.45576 9.45576 8.00652 9.60988 7.43136C9.78333 6.78404 9.66667 5.99912 9.66667 5.33333C9.66667 3.49238 11.1591 2 13 2" />
                        </svg>
                    </a>
                    <a href="{{ route('download.xml', ['comprobante' => $comprobante, 'type' => 'cdr']) }}"
                        class="btn-email">
                        DESCARGAR CDR
                        <svg class="" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" fill="none">
                            <path
                                d="M12.5 2H12.7727C16.0339 2 17.6645 2 18.7969 2.79784C19.1214 3.02643 19.4094 3.29752 19.6523 3.60289C20.5 4.66867 20.5 6.20336 20.5 9.27273V11.8182C20.5 14.7814 20.5 16.2629 20.0311 17.4462C19.2772 19.3486 17.6829 20.8491 15.6616 21.5586C14.4044 22 12.8302 22 9.68182 22C7.88275 22 6.98322 22 6.26478 21.7478C5.10979 21.3424 4.19875 20.4849 3.76796 19.3979C3.5 18.7217 3.5 17.8751 3.5 16.1818V12" />
                            <path
                                d="M20.5 12C20.5 13.8409 19.0076 15.3333 17.1667 15.3333C16.5009 15.3333 15.716 15.2167 15.0686 15.3901C14.4935 15.5442 14.0442 15.9935 13.8901 16.5686C13.7167 17.216 13.8333 18.0009 13.8333 18.6667C13.8333 20.5076 12.3409 22 10.5 22" />
                            <path
                                d="M4.5 7.5C4.99153 8.0057 6.29977 10 7 10M9.5 7.5C9.00847 8.0057 7.70023 10 7 10M7 10L7 2" />
                        </svg>
                    </a>
                </div>
            </td>
        </tr>
    </table>

    <table width='100%' border='0' cellpadding='0' cellspacing='0' align='center' class="mt-6">
        <table width='400' border='0' cellpadding='0' cellspacing='0' align='center'
            class='border-complete deviceWidth' bgcolor='#FFF'>

            <tr>
                <td id='logo' align='center'>
                    <img src='https://next.net.pe/img/login.png' alt='' border='0' />
                </td>
            </tr>
        </table>

        <table width='400' border='0' cellpadding='0' cellspacing='0' align='center'
            class='border-lr deviceWidth' bgcolor='#fff'>
            <tr>
                <td style='text-align: center;'>
                    <h3 class='p-4 color-dark'>{{ $comprobante->sucursal->empresa->name }}</h3>
                    <p class='p-4 m-3 mt-6 text-sm color-dark'>{{ $comprobante->sucursal->empresa->document }}</p>
                    <p class='p-4 m-3 text-sm color-dark'>
                        {{ $comprobante->sucursal->direccion }} -
                        {{ $comprobante->sucursal->ubigeo->region }} -
                        {{ $comprobante->sucursal->ubigeo->provincia }} -
                        {{ $comprobante->sucursal->ubigeo->distrito }}
                    </p>
                    <p class='p-4 m-3 text-sm color-dark'>
                        TELEF. : 984641828

                    </p>
                </td>
            </tr>

            <tr>
                <td style='text-align: center;'>
                    <h3 class='m-3 color-dark'>{{ $comprobante->seriecomprobante->typecomprobante->descripcion }}</h3>
                    <h4 class='mt-6 color-dark'>{{ $comprobante->seriecompleta }}</h4>
                </td>
            </tr>
            <tr>
                <td style='text-align: left;'>
                    <h4 class='p-4 m-3 text-md color-dark'>FECHA EMISIÓN:
                        {{ formatDate($comprobante->date, 'DD-MM-YYYY hh:mm A') }}</h4>
                    <h4 class='p-4 m-3 text-md color-dark'>CLIENTE: {{ $comprobante->client->name }}</h4>
                    <h4 class='p-4 m-3 text-md color-dark'>TIPO Y N° DOC. : {{ $comprobante->client->document }}</h4>
                    <h4 class='p-4 m-3 text-md color-dark'>
                        @if (trim(strlen($comprobante->client->document)) == 8)
                            DOC.NACIONAL DE IDENTIDAD (DNI)
                        @else
                            REG.UNICO DE CONTRIBUYENTES (RUC)
                        @endif
                    </h4>
                    <h4 class='p-4 m-3 text-md color-dark'>DIRECCION: {{ $comprobante->direccion }}</h4>
                    <h4 class='p-4 m-3 text-md color-dark'>
                        FORMA DE PAGO: {{ $comprobante->typepayment->name }}</h4>
                    @if ($comprobante->seriecomprobante->typecomprobante->code == '07')
                        <h4 class='p-4 m-3 text-md color-dark'>DOCUMENTO QUE MODIFICA: {{ $comprobante->referencia }}
                        </h4>
                    @endif
                </td>
            </tr>
        </table>

        <table width='400' border='0' cellpadding='0' cellspacing='0' align='center'
            class='border-lr deviceWidth' bgcolor='#fff'>
            <tr>
                <td width='30' class='color-dark text-sm p-4' style='text-align: center;'>CANT.</td>
                <td class='color-dark text-sm p-4 m-3' style=''>DESCRIPCIÓN</td>
                <td class='color-dark text-sm p-4 m-3' style='text-align: right;'>PRECIO</td>
                <td class='color-dark text-sm p-4 m-3' style='text-align: right;'>IMPORTE</td>
            </tr>


            @foreach ($comprobante->facturableitems as $item)
                <tr>
                    <td class='color-dark text-sm p-4 m-3' style='text-align: center;'>
                        {{ formatDecimalOrInteger($item->cantidad) }}</td>
                    <td class='color-dark text-sm p-4 m-3' style='text-align: justify;'>
                        {{ $item->descripcion }}</td>
                    <td class='color-dark text-sm p-4 m-3' style='text-align: right;'>
                        {{ formatDecimalOrInteger($item->price) }}</td>
                    <td class='color-dark text-sm p-4 m-3' style='text-align: right;'>
                        {{ formatDecimalOrInteger($item->price * $item->cantidad, 2, '.', ', ') }}</td>
                </tr>
            @endforeach


        </table>

        <table width='400' border='0' cellpadding='0' cellspacing='0' align='center'
            class='border-lr deviceWidth' bgcolor='#fff'>

            <tr>
                <td style='text-align: right;'>
                    <h4 class='p-4 m-3 mt-6 text-md color-dark'>OP. GRAVADA S/. : {{ $comprobante->gravado }}</h4>
                    <h4 class='p-4 m-3 mt-6 text-md color-dark'>OP. EXONERADAS S/. : {{ $comprobante->exonerado }}
                    </h4>
                    <h4 class='p-4 m-3 mt-6 text-md color-dark'>I.G.V S/. : {{ $comprobante->igv }}</h4>
                    <h4 class='p-4 m-3 mt-6 text-md color-dark'>TOTAL S/. : {{ $comprobante->total }}</h4>
                </td>
            </tr>

            <tr>
                <td style='text-align: center;'>
                    <h4 class='p-4 text-md color-dark'>{{ $comprobante->leyenda }}</h4>
                </td>
            </tr>

            <tr>
                <td style='text-align: justify;'>
                    <p class='p-4 text-sm color-dark'>Representación impresa del comprobante de venta electrónico,
                        este puede ser consultado en www.sunat.gob.pe. Autorizado
                        mediante resolución N° 155-2017/Sunat.</p>
                </td>
            </tr>

            <tr>
                <td style='text-align: center;'>
                    <h5 class='p-4 text-md color-dark'>
                        @if ($comprobante->exonerado > 0)
                            BIENES TRANSFERIDOS EN LA AMAZONÍA REGIÓN SELVA PARA SER CONSUMIDOS EN LA MISMA
                        @endif
                    </h5>
                </td>
            </tr>

            <tr>
                <td style='text-align: justify;'>
                    <p class='p-4 text-sm color-dark'>Estimado cliente, no hay devolución de dinero. Todo cambio de
                        mercadería solo podrá realizarse dentro de las 48 horas, previa
                        presentacion del comprobante.</p>
                </td>
            </tr>

            <tr>
                <td style='text-align: center;'>
                    <a href="{{ $comprobante->sucursal->empresa->web }}" align='center'
                        class='p-4 m-3 text-md color-next text-enlace'>{{ $comprobante->sucursal->empresa->web }}</a>
                    <h5 class='p-4 m-3 text-md color-dark thank-you'>GRACIAS POR SU COMPRA</h5>
                </td>
            </tr>
        </table>

        <table width='400' border='0' cellpadding='0' cellspacing='0' align='center'
            class='border-lr deviceWidth' bgcolor='#000'>
            <tr>
                <td style='text-align: center;'>
                    <p id='footer-txt'>
                        <b>© {{ $comprobante->sucursal->empresa->name }}2012</b> - Todos los derechos reservados.
                    </p>
                </td>
            </tr>
        </table>
    </table>
</body>

</html>

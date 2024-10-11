<div {{ $attributes->merge() }}
    style="z-index: 999999; background-color:rgba(255,255,255,.85);position: fixed;top: 0;left: 0; width: 100%;height: 100vh;display: flex;flex-direction:column;justify-content: center;align-items: center;">
    <style>
        @keyframes spin3D {
            from {
                transform: rotate3d(.5, .5, .5, 360deg);
            }

            to {
                transform: rotate3d(0deg);
            }
        }

        .spinner-box {
            width: 300px;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: transparent;
            margin: auto;
        }

        .leo {
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
        }

        .blue-orbit {
            width: 165px;
            height: 165px;
            border: 1px solid #91daff;
            -webkit-animation: spin3D 3s linear .2s infinite;
        }

        .green-orbit {
            width: 120px;
            height: 120px;
            border: 1px solid #0fb9b9;
            -webkit-animation: spin3D 2s linear 0s infinite;
        }

        .red-orbit {
            width: 90px;
            height: 90px;
            border: 1px solid #0fb9b9;
            -webkit-animation: spin3D 1s linear 0s infinite;
        }

        .white-orbit {
            width: 60px;
            height: 60px;
            border: 2px solid #c5c5c5;
            -webkit-animation: spin3D 5s linear 0s infinite;
        }

        .w1 {
            transform: rotate3D(1, 1, 1, 90deg);
        }

        .w2 {
            transform: rotate3D(1, 2, .5, 90deg);
        }

        .w3 {
            transform: rotate3D(.5, 1, 2, 90deg);
        }
    </style>

    <div class="spinner-box">
        <div class="blue-orbit leo">
        </div>
        <div class="green-orbit leo">
        </div>
        <div class="red-orbit leo">
        </div>
        <div class="white-orbit w1 leo">
        </div>
        <div class="white-orbit w2 leo">
        </div>
        <div class="white-orbit w3 leo">
        </div>
    </div>

    {{-- <p class="animate-pulse text-[8px] leading-3 font-semibold tracking-widest text-next-500">
        CARGANDO...</p> --}}
</div>

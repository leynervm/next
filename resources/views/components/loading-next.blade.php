<div {{ $attributes->merge(['class' => 'w-full text-center py-2 ']) }}>
    <svg class="w-24 h-16 block mx-auto text-next-600" xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 30" stroke="currentColor"
        xml:space="preserve">
        <rect x="0" y="10" class="w-1 h-2" fill="currentColor" opacity="0.2">
            <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0s" dur="0.6s"
                repeatCount="indefinite" />
            <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0s" dur="0.6s"
                repeatCount="indefinite" />
            <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0s" dur="0.6s"
                repeatCount="indefinite" />
        </rect>
        <rect x="8" y="10" class="w-1 h-2" fill="currentColor" opacity="0.2">
            <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0.15s" dur="0.6s"
                repeatCount="indefinite" />
            <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0.15s" dur="0.6s"
                repeatCount="indefinite" />
            <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0.15s" dur="0.6s"
                repeatCount="indefinite" />
        </rect>
        <rect x="16" y="10" class="w-1 h-2" fill="currentColor" opacity="0.2">
            <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0.3s" dur="0.6s"
                repeatCount="indefinite" />
            <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0.3s" dur="0.6s"
                repeatCount="indefinite" />
            <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0.3s" dur="0.6s"
                repeatCount="indefinite" />
        </rect>
    </svg>

    {{-- <svg class="w-20 h-20 block mx-auto animate-bounce" xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 240 240">
        <path fill="#fefefe"
            d="M 29.5,-0.5 C 94.5,-0.5 159.5,-0.5 224.5,-0.5C 240.152,3.65237 249.985,13.6524 254,29.5C 254.667,94.8333 254.667,160.167 254,225.5C 249.985,241.348 240.152,251.348 224.5,255.5C 159.5,255.5 94.5,255.5 29.5,255.5C 13.8333,251.167 3.83333,241.167 -0.5,225.5C -0.5,160.167 -0.5,94.8333 -0.5,29.5C 3.83333,13.8333 13.8333,3.83333 29.5,-0.5 Z" />

        <path fill="#090909"
            d="M 122.5,38.5 C 134.36,36.8788 144.693,40.0455 153.5,48C 168.018,63.6839 182.351,79.5172 196.5,95.5C 193.126,99.8755 189.126,103.542 184.5,106.5C 173.177,94.3419 162.01,82.0086 151,69.5C 144.702,61.6888 136.702,56.6888 127,54.5C 122.422,55.3718 118.255,57.2051 114.5,60C 108.946,65.0512 103.946,70.5512 99.5,76.5C 117.185,95.6839 134.685,115.017 152,134.5C 149.505,139.501 145.672,143.501 140.5,146.5C 119.677,124.176 99.0107,101.676 78.5,79C 86.3426,67.149 95.6759,56.4823 106.5,47C 111.493,43.339 116.827,40.5057 122.5,38.5 Z" />

        <path fill="#6cb5b6"
            d="M 19.5,82.5 C 21.028,86.5591 22.8613,90.5591 25,94.5C 55.5,127.667 86,160.833 116.5,194C 123.139,197.277 129.806,197.277 136.5,194C 168.5,165.333 200.5,136.667 232.5,108C 237.034,103.801 241.201,99.3006 245,94.5C 246.599,103.747 244.099,111.58 237.5,118C 201.535,149.63 165.868,181.63 130.5,214C 123.452,218.489 116.452,218.489 109.5,214C 79,180.833 48.5,147.667 18,114.5C 15.7179,110.821 14.5513,106.821 14.5,102.5C 15.7564,95.7308 17.4231,89.0641 19.5,82.5 Z" />
    </svg> --}}

    <p class="animate-pulse text-[8px] leading-3 font-semibold tracking-widest text-next-500">
        CARGANDO...</p>
</div>

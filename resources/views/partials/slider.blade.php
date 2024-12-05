<div class="w-full max-w-full p-0">
    <div class="relative {{ count($sliders) > 1 ? '' : '' }}">
        <div class="relative w-full overflow-hidden h-0 pt-0 min-h-[480px] xs:min-h-[620px] sm:min-h-[720px] md:pt-[29%] md:min-h-[290px] {{-- mt-[108px] xl:mt-20 --}}"
            id="slider">
            @foreach ($sliders as $item)
                <div class="carousel-item {{ $loop->first ? 'activo' : '' }}">
                    <div class="h-full flex relative efecto-slider">
                        <div class="lazyload-wrapper">
                            <picture>
                                {{-- <source media="(width >= 900px)" srcset="logo-large.png"> --}}
                                <source media="(min-width : 768px)" srcset="{{ pathURLSlider($item->url) }}">
                                <source srcset="{{ pathURLSlider($item->urlmobile) }}">
                                <img src="{{ pathURLSlider($item->url) }}" alt="{{ pathURLSlider($item->url) }}"
                                    class="absolute w-full h-full object-cover">
                            </picture>
                        </div>
                        <div class="carousel-item-link">
                            @if ($item->link)
                                <a href="{{ $item->link }}"></a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @if (count($sliders) > 1)
            <ol class="flex justify-center items-center gap-3 mt-3" id="indice-slider">
                @foreach ($sliders as $item)
                    <li class="indicador-slider {{ $loop->first ? 'activo' : '' }}"></li>
                @endforeach
            </ol>
        @endif

        @if (count($sliders) > 1)
            <button type="button" id="previusslider"
                class="absolute w-auto h-12 rounded-r m-auto top-0 left-0 bottom-0 z-[5] text-next-700 text-center opacity-40 flex justify-center items-center hover:opacity-60 transition-opacity ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 349 512" xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor" stroke-linecap="round" stroke-linejoin="round" class="w-full h-full block p-1">
                    <path
                        d="M1.843 262.032 170.39 509.5c1.088 1.605 2.564 2.5 4.11 2.5h168.548c2.348 0 4.476-2.081 5.37-5.264.902-3.191.402-6.861-1.26-9.301L182.718 256l164.44-241.434c1.661-2.44 2.161-6.11 1.26-9.3-.894-3.183-3.021-5.265-5.37-5.265H174.5c-1.546 0-3.022.896-4.11 2.5L1.842 249.968c-2.272 3.336-2.272 8.729 0 12.065z" />
                </svg>
                <span class="sr-only">Previous</span>
            </button>
            <button type="button" id="nextslider"
                class="absolute w-auto h-12 rounded-l m-auto top-0 right-0 bottom-0 z-[5] text-next-700 text-center opacity-40 flex justify-center items-center hover:opacity-60 transition-opacity ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 349 512" xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor" stroke-linecap="round" stroke-linejoin="round" class="w-full h-full block p-1">
                    <path
                        d="m347.01895,249.967l-168.54792,-247.467c-1.08703,-1.604 -2.56296,-2.5 -4.10905,-2.5l-168.5486,0c-2.34774,0 -4.47548,2.082 -5.37044,5.265c-0.90109,3.191 -0.40117,6.861 1.26139,9.301l164.43955,241.434l-164.43955,241.433c-1.66187,2.441 -2.1618,6.11 -1.26139,9.301c0.89496,3.183 3.02202,5.265 5.37044,5.265l168.5486,0c1.54609,0 3.02202,-0.896 4.10905,-2.5l168.5486,-247.467c2.27213,-3.336 2.27213,-8.729 -0.00068,-12.065z" />
                </svg>
                <span class="sr-only">Next</span>
            </button>
        @endif
    </div>

    @if (count($sliders) > 1)
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const slider = document.getElementById('slider');
                const indice_slider = document.getElementById('indice-slider');
                const idicador_items = indice_slider.querySelectorAll('.indicador-slider');
                const items = slider.querySelectorAll('.carousel-item');
                const nextButton = document.getElementById('nextslider');
                const prevButton = document.getElementById('previusslider');
                let currentIndex = 0;
                let autoSlideInterval;

                const showSlide = (index, direction) => {
                    if (direction === 'next') {
                        // items[index].classList.add('entrante');
                        // items[currentIndex].classList.remove('saliente');
                    } else if (direction === 'prev') {
                        // items[currentIndex].classList.add('entrante');
                        // items[index].classList.add('saliente');
                    }

                    changeImageSlider(index);
                };

                const nextSlide = () => {
                    currentIndex = (currentIndex + 1) % items.length;
                    showSlide(currentIndex, 'next');
                    resetAutoSlide();
                };

                const prevSlide = () => {
                    currentIndex = (currentIndex - 1 + items.length) % items.length;
                    showSlide(currentIndex, 'prev');
                    resetAutoSlide();
                };

                const changeImageSlider = (index) => {
                    items.forEach((item, i) => {
                        if (i === index) {
                            item.classList.add('activo');
                        } else {
                            item.classList.remove('activo');
                        }
                    });

                    idicador_items.forEach((item, i) => {
                        if (i === index) {
                            item.classList.add('activo');
                        } else {
                            item.classList.remove('activo');
                        }
                    });

                    currentIndex = index;
                }

                const startAutoSlide = () => {
                    autoSlideInterval = setInterval(nextSlide, 5000);
                };

                const stopAutoSlide = () => {
                    clearInterval(autoSlideInterval);
                };

                const resetAutoSlide = () => {
                    stopAutoSlide();
                    startAutoSlide();
                };

                idicador_items.forEach((button, e) => {
                    button.addEventListener('click', function(e) {
                        changeImageSlider($(this).index());
                        resetAutoSlide();
                    })
                })

                if (nextButton) {
                    nextButton.addEventListener('click', nextSlide);
                }

                if (prevButton) {
                    prevButton.addEventListener('click', prevSlide);
                }
                slider.addEventListener('mouseover', stopAutoSlide);
                slider.addEventListener('mouseout', startAutoSlide);

                showSlide(currentIndex);
                startAutoSlide();

            });
        </script>
    @endif
</div>

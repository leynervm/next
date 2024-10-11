<div class="flex w-full h-full p-3 gap-3 flex-wrap justify-center items-center" {{ $attributes }}>
    <svg class="pl1 w-24 h-24" viewBox="0 0 128 128">
        <defs>
            <linearGradient id="pl-grad" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#0fb9b9" />
                <stop offset="100%" stop-color="#ff" />
            </linearGradient>
            <mask id="pl-mask">
                <rect x="0" y="0" width="128" height="128" fill="url(#pl-grad)" />
            </mask>
        </defs>
        <g fill="#ffffff">
            <g class="pl1__g">
                <g transform="translate(20,20) rotate(0,44,44)">
                    <g class="pl1__rect-g">
                        <rect class="pl1__rect" rx="8" ry="8" width="40" height="40" />
                        <rect class="pl1__rect" rx="8" ry="8" width="40" height="40"
                            transform="translate(0,48)" />
                    </g>
                    <g class="pl1__rect-g" transform="rotate(180,44,44)">
                        <rect class="pl1__rect" rx="8" ry="8" width="40" height="40" />
                        <rect class="pl1__rect" rx="8" ry="8" width="40" height="40"
                            transform="translate(0,48)" />
                    </g>
                </g>
            </g>
        </g>
        <g fill="#0fb9b9" mask="url(#pl-mask)">
            <g class="pl1__g">
                <g transform="translate(20,20) rotate(0,44,44)">
                    <g class="pl1__rect-g">
                        <rect class="pl1__rect" rx="8" ry="8" width="40" height="40" />
                        <rect class="pl1__rect" rx="8" ry="8" width="40" height="40"
                            transform="translate(0,48)" />
                    </g>
                    <g class="pl1__rect-g" transform="rotate(180,44,44)">
                        <rect class="pl1__rect" rx="8" ry="8" width="40" height="40" />
                        <rect class="pl1__rect" rx="8" ry="8" width="40" height="40"
                            transform="translate(0,48)" />
                    </g>
                </g>
            </g>
        </g>
    </svg>
</div>

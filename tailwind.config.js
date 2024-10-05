const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
    purge: {
        enabled: false,
    },
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            colors: {
                body: "var(--body)",
                sidebar: "var(--sidebar)",
                iconmenu: "var(--icon-menu)",

                colorlabel: "var(--color-label)",
                colorinput: "var(--color-input)",

                fondolinknav: "var(--fondo-link-nav)",
                colorlinknav: "var(--color-link-nav)",
                hoverlinknav: "var(--hover-link-nav)",
                hovercolorlinknav: "var(--hover-color-link-nav)",
                ringlinknav: "var(--ring-link-nav)",

                fondodropdown: "var(--fondo-dropdown)",
                colordropdown: "var(--color-dropdown)",
                fondohoverdropdown: "var(--fondo-hover-dropdown)",
                textohoverdropdown: "var(--texto-hover-dropdown)",

                primary: "var(--color-primary)",
                colortitle: "var(--color-title)",

                colorbuttonclose: "var(--color-button-close)",
                fondobuttonclose: "var(--fondo-button-close)",
                colorhoverbuttonclose: "var(--color-hover-button-close)",
                fondohoverbuttonclose: "var(--fondo-hover-button-close)",
                ringbuttonclose: "var(--ring-button-close)",


                colorbutton: "var(--color-button)",
                fondobutton: "var(--fondo-button)",
                colorhoverbutton: "var(--color-hover-button)",
                fondohoverbutton: "var(--fondo-hover-button)",
                ringbutton: "var(--ring-button)",

                colorbuttontheme: "var(--color-button-theme)",
                fondobuttontheme: "var(--fondo-button-theme)",

                fondominicard: "var(--fondo-minicard)",
                colorminicard: "var(--color-minicard)",
                shadowminicard: "var(--shadow-minicard)",
                borderminicard: "var(--border-minicard)",

                fondotitlecardnext: "var(--fondo-titlecardnext)",
                colortitlecardnext: "var(--color-titlecardnext)",
                textcardnext: "var(--text-cardnext)",

                textcardproduct: "var(--text-cardproduct)",
                fondospancardproduct: "var(--fondo-spancardproduct)",
                textspancardproduct: "var(--text-spancardproduct)",

                fondoheadermodal: "var(--fondo-header-modal)",
                colorheadermodal: "var(--color-header-modal)",
                fondobodymodal: "var(--fondo-body-modal)",

                textselect2: "var(--color-select2)",
                fondoselect2: "var(--fondo-select2)",
                texthoverselect2: "var(--color-hover-select2)",
                fondohoverselect2: "var(--fondo-hover-select2)",
                textactiveselect2: "var(--color-active-select2)",
                fondoactiveselect2: "var(--fondo-active-select2)",
                shadowselect2: "var(--shadow-select2)",
                subtitleselect2: "var(--color-subtitle-select2)",
                subtitleactiveselect2: "var(--color-active-subtitle-select2)",

                textheadertable: "var(--text-header-table)",
                fondoheadertable: "var(--fondo-header-table)",
                textbodytable: "var(--text-body-table)",
                fondobodytable: "var(--fondo-body-table)",
                texthovertable: "var(--text-hover-table)",
                fondohovertable: "var(--fondo-hover-table)",
                dividetable: "var(--divide-table)",
                textspantable: "var(--text-span-table)",
                fondospantable: "var(--fondo-span-table)",
                linktable: "var(--link-table)",
                hoverlinktable: "var(--hover-link-table)",

                fondoinputdisabled: "var(--fondo-input-disabled)",
                colorinputdisabled: "var(--color-input-disabled)",

                colorerror: "var(--color-error)",

                fondopagination: "var(--fondo-pagination)",
                colorpagination: "var(--color-pagination)",
                fondoactivepagination: "var(--fondo-active-pagination)",
                coloractivepagination: "var(--color-active-pagination)",
                shadowpagination: "var(--shadow-pagination)",

                fondoloading: "var(--fondo-loading)",
                colorloading: "var(--color-loading)",

                fondoform: "var(--fondo-form)",
                colortitleform: "var(--color-title-form)",
                colorsubtitleform: "var(--color-subtitle-form)",
                shadowform: "var(--shadow-form)",


                fondofooter: "var(--fondo-footer)",
                colorlinkfooter: "var(--color-link-footer)",
                hoverlinkfooter: "var(--hover-link-footer)",
                
                colorbuttonprint: "var(--color-button-print)",
                fondobuttonprint: "var(--fondo-button-print)",
                colorhoverbuttonprint: "var(--color-hover-button-print)",
                fondohoverbuttonprint: "var(--fondo-hover-button-print)",
                ringbuttonprint: "var(--ring-button-print)",


                fondoheadermarketplace: "var(--fondo-header-marketplace)",
                colorheadermarketplace: "var(--color-header-marketplace)",
                
                fondosearchmarketplace: "var(--fondo-search-marketplace)",
                colorsearchmarketplace: "var(--color-search-marketplace)",
                fondobuttonsearchmarketplace: "var(--fondo-button-search-marketplace)",
                colorbuttonsearchmarketplace: "var(--color-button-search-marketplace)",
                ringbuttonsearchmarketplace: "var(--ring-button-search-marketplace)",
                
                fondobadgemarketplace: "var(--fondo-badge-marketplace)",
                colorbadgemarketplace: "var(--color-badge-marketplace)",


                next: {
                    50: "#f0f9f9",
                    100: "#ebf5f5",
                    200: "#d3e6e6",
                    300: "#b2d6d6",
                    400: "#0ecfcf",
                    500: "#0FB9B9",
                    600: "#10adad",
                    700: "#109999",
                    800: "#108282",
                    900: "#0e7e7e",
                    950: "#065b5b",
                },
            },

            fontFamily: {
                sans: ["Ubuntu", ...defaultTheme.fontFamily.sans],
            },
            screens: {
                "3xl": "1600px",
            },
        },
        screens: {
            xs: "475px",
            ...defaultTheme.screens,
        },
    },
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};

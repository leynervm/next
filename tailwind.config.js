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

                fondolinknav: "var(--fondo-link-nav)",
                colorlinknav: "var(--color-link-nav)",
                hoverlinknav: "var(--hover-link-nav)",
                hovercolorlinknav: "var(--hover-color-link-nav)",
                ringlinknav: "var(--ring-link-nav)",

                fondodropdown: "var(--fondo-dropdown)",
                colordropdown: "var(--color-dropdown)",
                primary: "var(--color-primary)",

                colortitle: "var(--color-title)",

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

                textselect2: "var(--color-select2)",
                fondoselect2: "var(--fondo-select2)",
                shadowselect2: "var(--shadow-select2)",

                textheadertable: "var(--text-header-table)",
                fondoheadertable: "var(--fondo-header-table)",
                textbodytable: "var(--text-body-table)",
                fondobodytable: "var(--fondo-body-table)",
                dividetable: "var(--divide-table)",
                textspantable: "var(--text-span-table)",
                fondospantable: "var(--fondo-span-table)",
                linktable: "var(--link-table)",
                hoverlinktable: "var(--hover-link-table)",

                fondoinputdisabled: "var(--fondo-input-disabled)",
                colorinputdisabled: "var(--color-input-disabled)",

                colorerror: "var(--color-error)",

                next: {
                    50: "f0f9f9",
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
        },
    },
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};

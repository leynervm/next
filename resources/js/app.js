import "./bootstrap";

import Alpine from "alpinejs";
import mask from '@alpinejs/mask'
import focus from '@alpinejs/focus'

Alpine.plugin(focus)
Alpine.plugin(mask);

window.Alpine = Alpine;

Alpine.start();



document.addEventListener('DOMContentLoaded', () => {
    getTheme();
    const buttonsTheme = document.querySelectorAll('.theme-switcher-button');
    buttonsTheme.forEach((button) => {
        button.addEventListener('click', () => {
            setTheme(button);
            getTheme();
        });
    })

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        getTheme(e);
    });

    function setActive(theme) {
        const themeSwitcherButtons = document.querySelectorAll('.theme-switcher-button');
        themeSwitcherButtons.forEach((button) => {
            button.classList.remove('theme-active');
            if (button.getAttribute('theme') == theme) {
                button.classList.add('theme-active');
            }
        })
    }

    function getTheme() {
        let localTheme = localStorage.theme;
        if (localTheme == null || localTheme == undefined) {
            if (window.matchMedia('(prefers-color-scheme:dark)').matches) {
                localTheme = 'theme-darknext';
            } else {
                localTheme = "{{ config('app.theme') }}";
            }
            localStorage.theme = localTheme;
        }
        setActive(localTheme);
        let classes = document.body.className.split(' ');
        let themeClasses = classes.filter(cls => cls.startsWith('theme-'));
        let isEqualThem = false;
        themeClasses.forEach(themeClass => {
            if (localTheme == themeClass) {
                isEqualThem = true;
            } else {
                document.body.classList.remove(themeClass);
            }
        });

        if (!isEqualThem) {
            document.body.classList.add(localTheme);
        }
    }

    function setTheme(event) {
        localStorage.theme = event.getAttribute('theme');
    }
})
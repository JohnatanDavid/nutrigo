/** @type {import('tailwindcss').Config} */
export default {
    content: ["./resources/**/*.blade.php", "./resources/**/*.js"],
    theme: {
        extend: {
            colors: {
                sunshine: "#FFC926",
                tomato: "#D52518",
                cream: "#F3E8CC",
                carrot: "#F96015",
                kiwi: "#9ABC05",
                forest: "#18542A",

                // Backward-compatible aliases (used by existing exported Figma CSS)
                "ng-cream": "#F3E8CC",
                "ng-red": "#D52518",
                "ng-dark-green": "#18542A",
                "ng-orange": "#F96015",
            },
            fontFamily: {
                sans: ["Inter", "sans-serif"],
            },
        },
    },
    plugins: [],
};

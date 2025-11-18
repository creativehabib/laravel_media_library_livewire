/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
        "./resources/css/**/*.css",
        "./storage/framework/views/*.php",

        // 👇 তোমার প্যাকেজের ভিউগুলো
        "./packages/Habib/MediaManager/resources/views/**/*.blade.php",
    ],
    theme: {
        extend: {},
    },
    plugins: [],
};

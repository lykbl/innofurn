/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  content: [
    "./resources/**/*.blade.php",
  ],
  theme: {
    extend: {},
  },
  corePlugins: {
    // preflight: false,
  },
  plugins: [require("rippleui")],
  /** @type {import('rippleui').Config} */
  rippleui: {
    // defaultStyle: false,
  },
}

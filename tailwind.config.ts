module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
    './resources/js/**/*.js',
    './resources/js/**/*.ts',
  ],
 theme: {
    extend: {
      colors: {
        echo: {
          dark: "#080008",       // optional
        }
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    // Add other plugins here if needed
  ],
}
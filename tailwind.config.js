const colors = require('tailwindcss/colors')

module.exports = {
    content: ['./resources/**/*.blade.php','./resources/js/**/*.vue', './vendor/filament/**/*.blade.php', "./node_modules/flowbite/**/*.js"],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                danger: colors.rose,
                primary: colors.blue,
                secondary: colors.gray,
                success: colors.green,
                warning: colors.yellow,
                'eastwest': {  DEFAULT: '#03B2AD',  50: '#70FDF9',  100: '#5CFCF8',  200: '#34FCF6',  300: '#0CFBF4',  400: '#04DAD4',  500: '#03B2AD',  600: '#027B77',  700: '#014442',  800: '#000C0C',  900: '#000000',  950: '#000000'},
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('flowbite/plugin')
    ],
}

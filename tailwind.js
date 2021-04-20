// Example `tailwind.js` file

module.exports = {
  important: true,
  purge: false,
  darkMode: 'class',
  theme: {
    fontFamily: {
    },
    extend: {
      colors: {
        cyan: '#9cdbff',
        "dark-bg": '#1d1d1d',
        "dark-text": '#eee',
      },
      margin: {
        '96': '24rem',
        '128': '32rem',
      },
    }
  },
  variants: {
    opacity: ['responsive', 'hover']
  }
}
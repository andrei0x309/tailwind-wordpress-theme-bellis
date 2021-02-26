// Example `tailwind.js` file

module.exports = {
  important: true,
  purge: false,
  theme: {
    fontFamily: {
    },
    extend: {
      colors: {
        cyan: '#9cdbff',
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
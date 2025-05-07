
module.exports = {
  important: true,
  darkMode: 'class',
  content: [
    './**/*.{js,jsx,ts,tsx,php}',
  ],
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
      width:{
        '80p': '80%',
      }
    }
  },
  variants: {
    opacity: ['responsive', 'hover']
  }
}


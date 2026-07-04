/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        teal: {
          50: '#E8F5F0', 100: '#C5E4DA', 200: '#9ECFC2', 300: '#71B8A5',
          400: '#4FBFA6', 500: '#0E6B5C', 600: '#0B584C', 700: '#08453B',
          800: '#06201A', 900: '#03120E',
        },
        gold: {
          50: '#FDF4E0', 100: '#FBE8BD', 200: '#F7D68A', 300: '#F0BE5E',
          400: '#E8A93B', 500: '#D4942A', 600: '#B0781E', 700: '#8A5E16',
          800: '#6B4810', 900: '#4D330C',
        },
        crimson: {
          50: '#FDE8E8', 100: '#F9C5C5', 200: '#F49797', 300: '#E5766B',
          400: '#C43D3D', 500: '#A83232', 600: '#8A2828', 700: '#6E1F1F',
          800: '#521717', 900: '#2A0E0C',
        },
        cream: '#FBF6EC',
        surface: {
          DEFAULT: '#FFFFFF',
          dark: '#10302B',
          2: '#EAF2E9',
        },
      },
      fontFamily: {
        display: ['Fraunces', 'Noto Serif Bengali', 'serif'],
        mono: ['IBM Plex Mono', 'ui-monospace', 'monospace'],
        body: ['Inter', 'Hind Siliguri', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [],
}

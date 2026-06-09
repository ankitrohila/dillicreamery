import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './app/Livewire/**/*.php',
    './app/Filament/**/*.php',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', ...defaultTheme.fontFamily.sans],
        display: ['"Playfair Display"', ...defaultTheme.fontFamily.serif],
        script: ['"Dancing Script"', 'cursive'],
      },
      colors: {
        brand: {
          50: '#f0faf7',
          100: '#dcf2eb',
          200: '#b9e4d7',
          300: '#87cfbc',
          400: '#5B9B8A',
          500: '#3d7d6c',
          600: '#2d6357',
          700: '#265148',
          800: '#22423b',
          900: '#1A2B2A',
        },
        gold: {
          50: '#fdf8ec',
          100: '#faefd0',
          200: '#f5dea1',
          300: '#eec76a',
          400: '#C9A84C',
          500: '#c08a2a',
          600: '#a56e1e',
          700: '#87541c',
          800: '#71451e',
          900: '#5f3b1c',
        },
        cream: {
          50: '#FFF8F0',
          100: '#fef0de',
          200: '#fdddb8',
          300: '#fcc87e',
        }
      },
      backgroundImage: {
        'gradient-dairy': 'linear-gradient(135deg, #5B9B8A 0%, #C9A84C 100%)',
        'gradient-hero': 'linear-gradient(180deg, #E8F5F1 0%, #FFF8F0 100%)',
        'gradient-dark': 'linear-gradient(135deg, #1A2B2A 0%, #2d6357 100%)',
      },
      animation: {
        'float': 'float 6s ease-in-out infinite',
        'float-delayed': 'float 6s ease-in-out 2s infinite',
        'float-delayed-2': 'float 6s ease-in-out 4s infinite',
        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'spin-slow': 'spin 20s linear infinite',
        'bounce-slow': 'bounce 3s infinite',
        'fade-up': 'fadeUp 0.6s ease-out forwards',
        'fade-in': 'fadeIn 0.5s ease-out forwards',
        'slide-right': 'slideRight 0.5s ease-out forwards',
        'counter': 'counter 2s ease-out forwards',
        'shimmer': 'shimmer 2s infinite',
      },
      keyframes: {
        float: {
          '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
          '33%': { transform: 'translateY(-15px) rotate(1deg)' },
          '66%': { transform: 'translateY(-8px) rotate(-1deg)' },
        },
        fadeUp: {
          '0%': { opacity: '0', transform: 'translateY(40px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideRight: {
          '0%': { opacity: '0', transform: 'translateX(-40px)' },
          '100%': { opacity: '1', transform: 'translateX(0)' },
        },
        shimmer: {
          '0%': { backgroundPosition: '-200% 0' },
          '100%': { backgroundPosition: '200% 0' },
        },
      },
      boxShadow: {
        'brand': '0 4px 30px rgba(91, 155, 138, 0.3)',
        'brand-lg': '0 8px 50px rgba(91, 155, 138, 0.4)',
        'gold': '0 4px 30px rgba(201, 168, 76, 0.3)',
        '3d': '0 20px 60px rgba(0,0,0,0.15), 0 8px 20px rgba(0,0,0,0.1)',
        'card': '0 2px 20px rgba(0,0,0,0.06)',
        'card-hover': '0 12px 40px rgba(0,0,0,0.12)',
        'glass': '0 4px 30px rgba(0,0,0,0.1)',
        'inner-brand': 'inset 0 2px 10px rgba(91, 155, 138, 0.1)',
      },
      backdropBlur: {
        xs: '2px',
      },
      transitionTimingFunction: {
        'bounce-in': 'cubic-bezier(0.68, -0.55, 0.265, 1.55)',
      },
    },
  },
  plugins: [forms, typography],
}

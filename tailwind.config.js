module.exports = {
  content: [
    "./app/modules/Front/**/*.latte",
    "./app/modules/Front/**/*.js",
    "./dev/front/js/**/*.js",
  ],
  safelist: [],
  theme: {
    fontFamily: {
      primary: ["'Merriweather Sans', sans-serif"],
      secondary: ["'Hemi', sans-serif"]
    },
    fontSize: {
      "t14": [pxToRem(14), pxToRem(24)],
      "t16": [pxToRem(16), pxToRem(32)],
      "t20": [pxToRem(20), pxToRem(36)],
      "t24": [pxToRem(24), pxToRem(40)],
      "t32": [pxToRem(32), pxToRem(56)],
      "h5": [pxToRem(16), pxToRem(24)],
      "h4": [pxToRem(24), pxToRem(32)],
      "h3": [pxToRem(40), pxToRem(56)],
      "h2": [pxToRem(48), pxToRem(58)],
      "h1": [pxToRem(56), pxToRem(72)],
      "h-landing": [pxToRem(64), pxToRem(80)]
    },
    extend: {
      colors: {
        blue: {
          DEFAULT: "#243c6c"
        },
        orange: {
          DEFAULT: "#FCA311"
        },
        "grey": {
          DEFAULT: "#E5E5E5"
        },
      }
    }
  },
  corePlugins: {
    container: false,
  },
  plugins: [
    require("@tailwindcss/aspect-ratio"),
    require("@tailwindcss/line-clamp"),
  ],
}

function pxToRem(pixels) {
  return `${pixels / 16}rem`
}
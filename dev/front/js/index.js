import Alpine from "alpinejs"

import "../css/index.css"

Alpine.data("ajax", (initState = {}) => ({
  loading: false,
  interactive: false,
  state: initState,
}))

window.Alpine = Alpine
Alpine.start()

window.addEventListener(`DOMContentLoaded`, () => {
  console.log("DOM loaded")
})

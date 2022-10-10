import Alpine from "alpinejs"

import "../css/index.css"

import initBurger from "../../../app/modules/Front/components/Burger/Burger";

Alpine.data("ajax", (initState = {}) => ({
  loading: false,
  interactive: false,
  state: initState,
}))

window.Alpine = Alpine
Alpine.start()

window.addEventListener(`DOMContentLoaded`, () => {
  console.log("DOM loaded")

  initBurger()
})

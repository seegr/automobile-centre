import "./Burger.scss"

export default function initBurger() {
  const burger = document.querySelector(".burger-button")

  burger.addEventListener(
    "click",
    (e) => {
      e.target.closest(".burger-button").classList.toggle("open")
    },
    false
  )
}

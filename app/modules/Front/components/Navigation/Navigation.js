import "./Navigation.scss"

export default function initNavigation() {
  const nav = document.getElementById("main-nav")
  const submenuWrap = nav.querySelector(".submenu-wrap")
  const activeClass = "active"
  let activeSubmenu = null

  nav.addEventListener("mouseover", (event) => {
    nav.classList.add(activeClass)
  })
  nav.addEventListener("mouseout", (event) => {
    nav.classList.remove(activeClass)
  })

  nav.querySelectorAll(".submenu-parent").forEach((item) => {
    item.addEventListener("mouseover", (event) => {
      activeSubmenu = item.querySelector(".submenu")
      activeSubmenu.classList.add(activeClass)
      submenuWrap.classList.add(activeClass)
    })
    item.addEventListener("mouseout", (event) => {
      activeSubmenu.classList.remove(activeClass)
      submenuWrap.classList.remove(activeClass)
    })
  })
}

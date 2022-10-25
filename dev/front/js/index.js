import Alpine from "alpinejs"

import "../css/index.css"

import initBurger from "../../../app/modules/Front/components/Burger/Burger";

Alpine.data("ajax", (initState = {}) => ({
  loading: false,
  interactive: false,
  state: initState,
  getBody() {
    let formData = null
    if (this.$el instanceof HTMLFormElement) formData = new FormData(this.$el)
    if (this.$root instanceof HTMLFormElement)
      formData = new FormData(this.$root)
    // needed for isSubmittedBy nette method
    if (formData && this.$el instanceof HTMLButtonElement)
      formData.append(this.$el.name, this.$el.value)
    return formData
  },
  getUrl() {
    if (this.$el instanceof HTMLFormElement) return this.$el.action
    if (this.$root instanceof HTMLFormElement) return this.$root.action
    if (this.$el instanceof HTMLAnchorElement) return this.$el.href
    return initState.url
  },
  applySnippets(snippets) {
    for (const [id, html] of Object.entries(snippets)) {
      const element = document.getElementById(id)
      if (element) {
        if (element.dataset.ajaxAppend !== undefined) {
          element.innerHTML = element.innerHTML + html
        } else {
          element.innerHTML = html
        }
      }
    }
  },
  request(body = null) {
    this.loading = true
    return fetch(this.getUrl(), {
      method: body || this.getBody() ? "POST" : "GET",
      headers: { "X-Requested-With": "XMLHttpRequest" },
      body:
          body instanceof InputEvent ||
          body instanceof PointerEvent ||
          body instanceof MouseEvent ||
          body instanceof SubmitEvent
              ? this.getBody()
              : body || this.getBody(),
    })
        .then((response) => {
          console.log(response)
          if (response.redirected) location.href = response.url
          return response.json()
        })
        .then(({ snippets, redirect, url }) => {
          if (redirect) {
            location.href = redirect
          }
          console.log(snippets);
          if (snippets) {
            if (url) {
              window.history.pushState(snippets, "", url)
            }
            this.applySnippets(snippets)
            this.interactive = true
            this.loading = false
          }
        })
        .catch((e) => {
          console.warn(e)
          this.loading = false
        })
  },
}))

window.Alpine = Alpine
Alpine.start()

window.addEventListener(`DOMContentLoaded`, () => {
  console.log("DOM loaded")

  initBurger()
})

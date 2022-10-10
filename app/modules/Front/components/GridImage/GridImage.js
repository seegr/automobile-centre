import "./GridImage.scss"

export default function initGridImage() {
  const images = document.querySelectorAll(".grid-image-wrap.animate")
  const showClass = "show"
  const imagesObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.querySelector(".grid-image").classList.add(showClass)
        } else {
          // entry.target.classList.remove(showClass, entry.isIntersecting)
        }
      })
    },
    {
      threshold: 0.7,
    }
  )

  images.forEach((image) => {
    imagesObserver.observe(image)
  })
}

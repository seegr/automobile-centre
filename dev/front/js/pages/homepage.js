import anime from "animejs"

export default function initHomepage() {
  const boxesTarget = document.getElementById("about-boxes")
  // console.log(boxes)

  const boxesObserver = new IntersectionObserver(
    (entries) => {
      if (window.innerWidth <= 1024) {
        return
      }

      const boxes = entries[0]
      const boxesEl = boxes.target

      let ticking = false

      const bubble =
        boxesEl.parentElement.parentElement.querySelector(".bubble")
      const trigger =
        boxesEl.parentElement.parentElement.querySelector(".bubble-trigger")

      const animateBubble = moveBubble(bubble)
      // const animateBoxes = moveBoxes(boxesEl)

      const boxesPosition = () => {
        if (!ticking) {
          // console.log((scrollPercent2(trigger) / 100) * animateBubble.duration)
          window.requestAnimationFrame(() => {
            animateBubble.seek(
              (scrollPercent2(trigger) / 100 + 0.2) * animateBubble.duration
            )
            // animateBoxes.seek(scrollPercent(trigger) * animateBoxes.duration)
            ticking = false
          })

          ticking = true
        }
      }

      if (boxes.isIntersecting) {
        document.addEventListener("scroll", boxesPosition)
      } else {
        document.removeEventListener("scroll", boxesPosition)
      }
    },
    {
      // rootMargin: "200px",
    }
  )

  boxesObserver.observe(boxesTarget)
}

function scrollPercent(element) {
  const rect = element.getBoundingClientRect()
  const calculation = (rect.top + rect.height / 2) / window.innerHeight

  const percent = Math.round((calculation + Number.EPSILON) * 10000) / 10000
  // console.log(percent)

  return percent
}

function scrollPercent2(el) {
  const rect = el.getBoundingClientRect()
  const scrollTop = window.pageYOffset || document.documentElement.scrollTop
  const top = rect.top + scrollTop - window.innerHeight
  const bottom = top + rect.height + window.innerHeight
  const scrollPos = window.pageYOffset || document.documentElement.scrollTop
  const scrollEl = bottom - scrollPos

  const percent = Math.round(((scrollPos - top) / (bottom - top)) * 100)

  return percent
}

function moveBubble(target) {
  return anime({
    targets: target,
    top: ["20%", "40%"],
    width: ["120%", "200%"],
    elasticity: 200,
    easing: "linear",
    autoplay: false,
    duration: 3000,
  })
}

function moveBoxes(target) {
  return anime({
    targets: target,
    top: ["-300px", "-280px"],
    elasticity: 200,
    easing: "linear",
    autoplay: false,
    duration: 3000,
  })
}

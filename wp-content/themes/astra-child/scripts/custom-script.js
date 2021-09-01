/**
 * Animation on Scroll
 */

let slidingBlocks = document.querySelectorAll(".slide")

let options = {
    root: null,
    rootMargin: '0px',
    threshold: 0.1
}

let slider = (entries, observer) => {
    entries.forEach(entry => {
        if(entry.isIntersecting){
            entry.target.classList.toggle("push-center");
            observer.unobserve(entry.target)
        }
    })
}

let scrollObserver = new IntersectionObserver(slider, options)
slidingBlocks.forEach(slide => scrollObserver.observe(slide))

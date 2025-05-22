import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { TextPlugin } from 'gsap/TextPlugin'

gsap.registerPlugin(ScrollTrigger, TextPlugin)

export default function initScrollAnimations() {
  
    gsap.utils.toArray('.scale-in').forEach((el) => {
        gsap.from(el, {
            scrollTrigger: {
                trigger: el, start: el.dataset.start || 'top 90%', end: el.dataset.end || 'top 30%',
                toggleActions: 'play none none none', scrub: true
            },
            opacity: 0, scale: 0.2, ease: "back.out(1.7)"
        })
    })


    gsap.utils.toArray('.rotate-in').forEach((el) => {
        gsap.from(el, {
            scrollTrigger: {
                trigger: el, start: el.dataset.start || 'top 90%', end: el.dataset.end || 'top 30%',
                toggleActions: 'play none none none', scrub: true
            },
            opacity: 0, scale: 0, rotate: 90, ease: "back.out(1.7)"
        })
    })


    gsap.utils.toArray('.text-in').forEach((el) => {
        gsap.to(el, {
            scrollTrigger: {
                trigger: el, start: el.dataset.start || 'top 100%', end: el.dataset.end || 'top 30%',
                toggleActions: 'play none none none',
                scrub: true,
            },
            text: {value:el.textContent, oldClass:'opacity-hide', newClass:'opacity-show'}, duration: 1
        })
    })


    gsap.utils.toArray('.fade-in').forEach((el) => {
        gsap.from(el, {
            scrollTrigger: {
                trigger: el, start: el.dataset.start || 'top 90%', end: el.dataset.end || 'top 30%',
                toggleActions: 'play none none none', scrub: true
            },
            opacity: 0
        })
    })

    gsap.utils.toArray('.slide-up').forEach((el) => {
        gsap.from(el, {
            scrollTrigger: {
                trigger: el, start: el.dataset.start || 'top 90%', end: el.dataset.end || 'top 30%',
                toggleActions: 'play none none none', scrub: true
            },
            y: (i, el) => el.offsetHeight, opacity: 0.5, ease: "power2.out"
        })
    })

    gsap.utils.toArray('.herodown').forEach((el) => {
        gsap.to(el, {
            scrollTrigger: {
                trigger: el, start: el.dataset.start || 'top 0%',
                toggleActions: 'play none none none', scrub: true
            },
            y: '100%', ease: "power2.out"
        })
    })

    ScrollTrigger.refresh();

}
let Particles = require('particlesjs');


/* -------------------------- Main Page Application ------------------------- */
window.mainApp = new Vue({
    el: "#main",
    data: {
        loaded: Boolean(false),
        imgUrl: "{{ asset('/images/test/usb_product.png') }}",
        bgColor: String("bisque"),
        salesImgUrl: "{{ asset('/images/test/usb_product.png') }}",
        active: false,
        particles: null,
        section2Viewed: false,

        product1: {
            company: "Lazyboy Industries",
            name: "Lazyboy",
            description: "Desc Here",
            bgColor: "Pink",
            price: 50.00,
            availability: true,
        },

        triggerResumeView: function () {
            document.getElementById('resumeBtn').click()
        },

        created() {

        }
    },

    methods: {
        onLoad: onLoad,
        observeSkillView: observeSkillView,
    }
});


/* Delays UI output and initialization until resources are loaded */
function onLoad() {
    this.loaded = true;
    this.particles = Particles.init({
        selector: '.bg-particle',
        color: '#ffffff',
        connectParticles: true,
        maxParticles:
            100
        ,

        // options for breakpoints
        responsive: [
            {
                breakpoint: 768,
                options: {
                    maxParticles: 60,
                    connectParticles: true,
                }
            }, {
                breakpoint: 425,
                options: {
                    maxParticles: 25,
                    connectParticles: true,
                }
            }, {
                breakpoint:320,
                options: {
                    maxParticles:10,
                    connectParticles: true,
                }
            }
        ],
    });

    this.observeSkillView();
}


/* Page 2 Animation Trigger */
function observeSkillView() {
    const onIntersectionCallback = (entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                if (!this.section2Viewed) {
                    this.section2Viewed = true;
                    this.$refs.skill1.classList.add('fade-1s');
                    this.$refs.skill2.classList.add('fade-2s');
                    this.$refs.skill3.classList.add('fade-3s');
                }
            }
        });
    }

    const pageOnViewDetector = new IntersectionObserver(onIntersectionCallback, {
        root: document.querySelector(null),
        rootMargin: '0px',
        threshold: 0.3
    });

    pageOnViewDetector.observe(this.$refs.skillContainer);
}



/* -------------------------- Full Page Scroll App -------------------------- */
window.scrollApp = new fullScroll({
    mainElement: "mainView",
    displayDots: true,
    dotsPosition: "left",
    animateTime: 0.7,
    animateFunction: "ease",
    transitionItems: []
});



/* ---------------------------- Page Init Routine --------------------------- */

/* UI update routine when resources are loaded */
window.onload = () => {
    window.mainApp.onLoad();
    window.navbarApp.navColor = 'transparent !important';
    setTimeout(function () { window.dispatchEvent(new Event('resize')); }, 100);
}

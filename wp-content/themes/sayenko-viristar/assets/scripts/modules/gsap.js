import $ from 'jquery';
import { gsap } from "gsap";

import { ScrollTrigger } from "gsap/ScrollTrigger";

export default {
	init() {
		"use strict";


		gsap.registerPlugin(ScrollTrigger);


		if ($('.acf-block-scrolling-sections').length !== 0) {

			let steps = gsap.utils.toArray("[data-step]");
			

			const links = gsap.utils.toArray("nav a");

			//responsive
			let mm = gsap.matchMedia();

			mm.add("(min-width: 1025px)", () => {

				gsap.set(steps.slice(1), { autoAlpha: 0 }); // hide all but the first section

				ScrollTrigger.create({
					trigger: '.scrolled-view-wrapper',
					start: 'top top',
					//start: () => `top ${$(".site-header").outerHeight(true)}`,
					pin: '.pin-me',
					end: "+=" + (steps.length * 100) + "%",
					markers: false
				});

				steps.forEach((step, i) => {
					ScrollTrigger.create({
						trigger: ".scrolled-view-wrapper",
						start: "top " + (i * -100) + "%",
						end: "+=100%",
						markers: false,
						// when the step is active, fade it in, otherwise fade it out. 
						onToggle: self => {
							if (self.isActive) {
								gsap.to(step, {
									autoAlpha: 1,
									overwrite: true
								});
								let element = document.querySelector('a[href="#' + step.getAttribute("id") + '"]');
								console.log(element);
								setActive(element);
							} else {
								// The FIRST one should never fade out.
								if (i === 0) return;

								gsap.to(step, {
									autoAlpha: 0,
									overwrite: true
								})
							}
						}
					});
				});

			});

			mm.add("(max-width: 1024px)", () => {

				gsap.set(steps.slice(1), { autoAlpha: 1 });
			  
			  });


			function setActive(link) {
				links.forEach((el) => el.classList.remove("active"));
				link.classList.add("active");
			}

		}

		

		if ($('.acf-block-hero-container').length !== 0) {


			gsap.utils.toArray(".acf-block-hero-container__background-image").forEach(function(container) {
				let image = container.querySelector("img");
				
				gsap.to(image, {
					y: () => image.offsetHeight - container.offsetHeight,
					ease: "none",
					scrollTrigger: {
					  trigger: container,
					  scrub: true,
					  pin: false,
					  markers: false,
					  invalidateOnRefresh: true
					},
				  }); 
			});

			if($('body').hasClass('home')) { 
				

				let hero = gsap.utils.selector(".acf-block-hero-container");

				if(gsap.getProperty(".acf-block-hero-container #wave", "height")) {
					gsap.to("#wave", {
						yPercent: -100, // or y
						opacity: 1,
						scrollTrigger: {
						  trigger: ".acf-block-hero-container",
						  pin: false,
						  start: "top top",
						  end: gsap.getProperty(".acf-block-hero-container", "height"),
						  //end: "+=" + gsap.getProperty(".acf-block-hero-container", "height"),
						  scrub: true
						}
					  });
				}

				
			}
		}

	},
};

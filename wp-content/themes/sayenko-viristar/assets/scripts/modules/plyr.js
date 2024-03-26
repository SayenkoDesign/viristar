// /* global $ */

import Plyr from 'plyr'

const defaults = {
	autoplay: false,
	volume: 1,
	muted: false,
	loop: {
		active: true,
	},
	storage: {
		enabled: false,
		key: 'plyr',
	},
	controls: [
		'play', // Play/pause playback
	],
	youtube: {
		noCookie: false,
		rel: 0,
		showinfo: 0,
		iv_load_policy: 3,
		modestbranding: 1,
	},
}

export default {
	init () {

		const plyrs = Array.from(document.getElementsByClassName('plyr'))

		plyrs.forEach(el => {
			const source = el.getAttribute('data-plyr-config') || []
			const plyr = new Plyr(el, Object.assign(defaults, source))

			const control = document.getElementById(el.getAttribute('data-play-pause-id'))

			if (control) {
				control.addEventListener('click', e => {
					e.preventDefault();
					plyr.togglePlay();

					if (plyr.playing) {
						e.target.src = e.target.getAttribute('data-src-play')
						e.target.parentElement.classList.remove('playing')
						e.target.parentElement.classList.add('paused')
					} else {
						e.target.src = e.target.getAttribute('data-src-pause')
						e.target.parentElement.classList.remove('paused')
						e.target.parentElement.classList.add('playing')
						
					}
					return false
				})
			}
		})
	},
}

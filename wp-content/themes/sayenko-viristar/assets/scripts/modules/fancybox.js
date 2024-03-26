import { Fancybox } from "@fancyapps/ui";

export default {
	init () {

		Fancybox.defaults.Hash = false;
    Fancybox.defaults.touch = false;

    //$.fancybox.defaults.Thumbs = { autoStart : true };
    Fancybox.defaults.Thumbs = { autoStart : false };
    
    Fancybox.bind("[data-fancybox]", {
      Thumbs: {
        autoStart: false,
      },
      Carousel: {
        Panzoom: {
          touch: false,
        },
      },
    });

	},
}
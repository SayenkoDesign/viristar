import $ from 'jquery';
// jquery match height NMP
import jqueryMatchHeight from 'jquery-match-height';

export default {
  init() {




    $('.flex-grid-3 h3').matchHeight();
    $('.flex-grid-4 h3').matchHeight();
    $('.flex-grid-3 h4').matchHeight();
    $('.flex-grid-4 h4').matchHeight();
    $('.grid-3 h3').matchHeight();
    $('.grid-4 h3').matchHeight();
    $('.grid-3 h4').matchHeight();
    $('.grid-4 h4').matchHeight();


    $('.post-image a').attr('aria-hidden', 'true');

  },
};

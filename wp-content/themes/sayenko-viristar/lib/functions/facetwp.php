<?php

if (!function_exists('FWP')) {
    return;
}

add_action( 'wp_footer', function() {
    ?>
    <script>
    document.addEventListener('facetwp-loaded', function() {
        var tpl = fUtil('.wp-block-acf-find-a-course');
        if ('' != FWP.buildQueryString()) {
            tpl.addClass('facetwp-visible');  
        } else {
            tpl.removeClass('facetwp-visible');
        }
    });


    /* (function($) {
            $(function() {
              if ('undefined' !== typeof FWP && document.querySelector('.wp-block-acf-find-a-course')) {
                FWP.auto_refresh = false;
              }
            });
          })(fUtil); */
    </script>
    <?php
    }, 100 );

add_filter( 'facetwp_facet_dropdown_show_counts', function( $return, $params ) {
    return false;
}, 10, 2 );
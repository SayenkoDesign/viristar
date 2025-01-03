<?php

/**
 * Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or it's parent block.
 */

use \App\ACF_Block;

$block = new ACF_Block($block, $content, $is_preview, $context);

/* $block->add_render_attribute(
'block', 'class', [
'alignfull',
]
);
 */

// Placeholder
if ($is_preview) {
    printf('<div class="acf-block-placeholder"><div id="map"><h3>%s</h3><p>Click to Edit</p></div></div>', $block->get_title());
    return;
}

$block->add_render_attribute(
    'class', [
        'frontend',
    ]
);

// Open the block
echo $block->before_render();

$google_api_key = GOOGLE_API_KEY;
?>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_api_key; ?>"></script>

<?php
$location_id = get_field('location'); // This returns the location post ID

if ($location_id) {
    $map = get_field('map', $location_id); // Get the 'map' field using the location ID

    if ($map): ?>
        <div id="map" style="height: 400px; width: 100%;"></div>
        <script>
        function initMap() {
            var location = {
                lat: <?php echo $map['lat']; ?>,
                lng: <?php echo $map['lng']; ?>
            };
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: <?php echo isset($map['zoom']) ? $map['zoom'] : 15; ?>,
                center: location
            });
            var marker = new google.maps.Marker({
                position: location,
                map: map
            });

            // Add click event listener to the map
            /* map.addListener('click', function() {
                // Create Google Maps directions URL
                var googleMapsUrl = 'https://www.google.com/maps/dir/?api=1&destination=' +
                    location.lat + ',' + location.lng;
                // Open in new tab
                window.open(googleMapsUrl, '_blank');
            }); */

            // Add click event listener to the marker
            marker.addListener('click', function() {
                var googleMapsUrl = 'https://www.google.com/maps/dir/?api=1&destination=' +
                    location.lat + ',' + location.lng;
                window.open(googleMapsUrl, '_blank');
            });
        }
        </script>
        <script>
        google.maps.event.addDomListener(window, 'load', initMap);
        </script>
    <?php endif;
}

// close the block
echo $block->after_render();

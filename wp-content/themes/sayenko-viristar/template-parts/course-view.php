<?php
$list_view = _s_get_icon(
    [
        'icon' => 'list-view',
        'group' => 'theme',
        'class' => '',
        'width' => 35,
        'height' => 24,
        'label' => false,
    ]
);

$grid_view = _s_get_icon(
    [
        'icon' => 'grid-view',
        'group' => 'theme',
        'class' => '',
        'width' => 22,
        'height' => 22,
        'label' => false,
    ]
);
?>
<div class="course-view">
<div class="course-view-wrap">
	<span class="course-view__list"><?php echo $list_view;?> List View</span>
	<span class="course-view__grid active"><?php echo $grid_view;?> Grid View</span>
</div>
</div>

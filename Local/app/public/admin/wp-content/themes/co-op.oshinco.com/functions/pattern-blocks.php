<?php
add_action('init', function () {
  register_block_pattern_category(
    'cpa-category',
    array('label' => __('SAMPLE', 'sample-plugin'))
  );
});

if (function_exists('pattern_block_add')) {
  add_action('init', 'pattern_block_add');
}
/**
 * Pattern Block Add
 */
function pattern_block_add()
{
  register_block_pattern(
    'sample-plugin/my-pattern-notification',
    array(
      'title' => __('告知要素', 'cpa-plugin'),
      'content' => '<!-- wp:group {"className":"is-notification","layout":{"type":"constrained"}} -->
        <div class="wp-block-group is-notification"><!-- wp:paragraph -->
        <p>ここは告知要素になります。</p>
        <!-- /wp:paragraph --></div>
        <!-- /wp:group -->',
      'categories' => array('cpa-category'),
    )
  );
}

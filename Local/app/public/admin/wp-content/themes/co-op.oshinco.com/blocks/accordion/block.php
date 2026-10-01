<?php
$accordion_label = (get_field('accordion_label')) ? get_field('accordion_label') : '';
$accordion_title = (get_field('accordion_title')) ? get_field('accordion_title') : '';
$accordion_anchor = (get_field('accordion_anchor')) ? get_field('accordion_anchor') : '';
$accordion_disable = (get_field('accordion_disable')) ? get_field('accordion_disable') : '';
$template = [
  [
    'core/paragraph',
    ['placeholder' => 'ここに段落内容がはいります。'],
  ],
];
$allow_blocks = [
  'core/columns',
  'core/group',
  'core/heading',
  'core/paragraph',
  'core/list',
  'core/image',
  'core/buttons',
  'core/button',
  'core/html',
  'core/separator',
  'core/embed',
  'flexible-table-block/table',
  'core/reusable-blocks',
  'core/block',
];
$render_appender = [
  'InnerBlocks.ButtonBlockAppender',
];
?>
<dl class="p-accordion<?php if ($accordion_disable) {
                        echo ' is-disable';
                      } ?>" <?php if ($accordion_anchor) {
                                                                                  echo ' id="' . $accordion_anchor . '"';
                                                                                } ?>>
  <dt class="<?php if ($accordion_disable) {
                echo 'is-disable';
              } else {
                echo 'js-acco';
              } ?><?php if (is_admin()) {
                                                                                                echo ' open';
                                                                                              } ?>">
    <?php if ($accordion_label) { ?><span class="p-accordion__label"><?php echo esc_html($accordion_label); ?></span><?php } ?>
    <?php if ($accordion_title) { ?><span><?php echo esc_html($accordion_title); ?></span><?php } ?>
  </dt>
  <dd class="<?php if (!$accordion_disable) {
                echo 'js-cont';
              } ?>" <?php if (is_admin()) {
                                                                      echo ' style="display: block";';
                                                                    } ?>>
    <InnerBlocks
      renderAppender="<?php echo esc_attr(wp_json_encode($render_appender)); ?>"
      template="<?php echo esc_attr(wp_json_encode($template)); ?>"
      allowedBlocks="<?php echo esc_attr(wp_json_encode($allow_blocks)); ?>"
      templateLock="" , />
  </dd>
</dl>
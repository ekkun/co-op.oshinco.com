<?php
/**
 * WordPress template hierarchy fallback.
 * Template implementation lives under /templates.
 */
$template_name = ('portfolio' === get_post_type()) ? 'portfolio' : 'default';
require get_template_directory() . '/templates/single/' . $template_name . '.php';

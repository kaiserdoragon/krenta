<?php
function add_my_assets_to_block_editor() {
    wp_enqueue_style( 'block-style', get_stylesheet_directory_uri() . '/block/block_style.css' );
    wp_enqueue_script( 'block-custom', get_stylesheet_directory_uri() . '/block/block_custom.js',array(), "", true);
}
add_action( 'enqueue_block_editor_assets', 'add_my_assets_to_block_editor' );

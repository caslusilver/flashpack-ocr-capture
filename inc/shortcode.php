<?php
if (!defined('ABSPATH')) exit;

function flashpack_ocr_shortcode() {
    ob_start();
    include plugin_dir_path(__FILE__) . '../templates/ocr-camera-template.php';
    return ob_get_clean();
}

add_shortcode('camera_ocr_test', 'flashpack_ocr_shortcode');

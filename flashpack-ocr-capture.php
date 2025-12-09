<?php
/**
 * Plugin Name: FlashPack OCR Capture
 * Plugin URI: https://github.com/caslusilver/flashpack-ocr-capture
 * Description: Captura de imagem com molduras, crop automático, envio ao webhook e modal de confirmação. Ideal para OCR de etiquetas em condomínios.
 * Version: 0.4.0
 * Author: Lucas Andrade
 * Author URI: https://github.com/caslusilver
 * License: GPL2
 * Text Domain: flashpack-ocr-capture
 */

if (!defined('ABSPATH')) exit;

/**
 * Load CSS + JS
 */
add_action('wp_enqueue_scripts', function() {

    wp_enqueue_style(
        'flashpack-ocr-style',
        plugin_dir_url(__FILE__) . 'assets/css/ocr-style.css',
        [],
        '0.4.0'
    );

    wp_enqueue_script(
        'flashpack-ocr-script',
        plugin_dir_url(__FILE__) . 'assets/js/ocr-script.js',
        [],
        '0.4.0',
        true
    );
});


/**
 * Load shortcode
 */
require_once plugin_dir_path(__FILE__) . 'inc/shortcode.php';

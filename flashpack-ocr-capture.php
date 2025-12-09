<?php
/**
 * Plugin Name: FlashPack OCR Capture
 * Plugin URI: https://github.com/caslusilver/flashpack-ocr-capture
 * Description: Captura de imagem com molduras, crop assistido e envio ao webhook com OCR.
 * Version: 0.4.5
 * Author: Lucas Andrade
 * Author URI: https://github.com/caslusilver
 * License: GPL2
 * Text Domain: flashpack-ocr-capture
 *
 * GitHub Plugin URI: caslusilver/flashpack-ocr-capture
 * Primary Branch: main
 */

if (!defined('ABSPATH')) exit;

/**
 * Retorna a versão atual do plugin lendo o cabeçalho.
 * Isso garante compatibilidade total com auto-tag, auto-release, Git Updater, etc.
 */
function flashpack_get_plugin_version() {
    if (!function_exists('get_file_data')) {
        require_once ABSPATH . 'wp-includes/functions.php';
    }

    $plugin_data = get_file_data(__FILE__, [
        'Version' => 'Version',
    ]);

    return $plugin_data['Version'];
}

/**
 * Load CSS + JS
 * Agora usando a versão automática do plugin
 */
add_action('wp_enqueue_scripts', function () {

    $version = flashpack_get_plugin_version(); // <- versão automática

    wp_enqueue_style(
        'flashpack-ocr-style',
        plugin_dir_url(__FILE__) . 'assets/css/ocr-style.css',
        [],
        $version // <- agora SEMPRE atualiza quando a versão mudar no cabeçalho
    );

    wp_enqueue_script(
        'flashpack-ocr-script',
        plugin_dir_url(__FILE__) . 'assets/js/ocr-script.js',
        [],
        $version, // idem no JS
        true
    );
});

/**
 * Load shortcode
 */
require_once plugin_dir_path(__FILE__) . 'inc/shortcode.php';

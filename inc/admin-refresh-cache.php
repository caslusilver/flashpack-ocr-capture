<?php
/**
 * Ajax Refresh Cache for Git Updater — integrado ao FlashPack
 */

if ( ! defined('ABSPATH') ) exit;

/**
 * Adiciona o botão na linha do plugin
 */
add_filter('plugin_row_meta', function($links, $file, $data, $status){

    // ajuste para o slug correto do seu plugin
    $plugin_slug = plugin_basename( dirname(__DIR__) . '/flashpack-ocr-capture.php' );

    if ($file !== $plugin_slug) {
        return $links;
    }

    $nonce = wp_create_nonce('flashpack_refresh_cache_nonce');

    $links[] = '
        <a href="#" 
           class="flashpack-refresh-cache-btn" 
           data-nonce="' . $nonce . '"
           style="font-weight:bold;color:#2271b1;">
           Atualizar Cache
        </a>
        <span class="flashpack-spinner" style="display:none;margin-left:6px;">
            <img src="' . esc_url(admin_url("images/spinner.gif")) . '" style="width:16px;height:16px;">
        </span>
    ';

    return $links;

}, 10, 4);


/**
 * Registra e carrega JS no painel de plugins
 */
add_action('admin_enqueue_scripts', function($hook){

    if ($hook !== 'plugins.php') return;

    wp_enqueue_script(
        'flashpack-refresh-js',
        plugin_dir_url(__DIR__) . 'assets/js/admin-refresh.js',
        ['jquery'],
        '1.0.0',
        true
    );

    wp_localize_script('flashpack-refresh-js', 'FlashPackRefresh', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
});


/**
 * Endpoint AJAX
 */
add_action('wp_ajax_flashpack_refresh_cache', function(){

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Permissão negada.']);
    }

    check_ajax_referer('flashpack_refresh_cache_nonce');

    if (!class_exists('\Fragen\Singleton')) {
        wp_send_json_error(['message' => 'Git Updater não está ativo.']);
    }

    $settings = \Fragen\Singleton::get_instance(
        'Fragen\Git_Updater\Settings',
        new stdClass()
    );

    if (!method_exists($settings, 'delete_all_cached_data')) {
        wp_send_json_error(['message' => 'Função delete_all_cached_data() não encontrada.']);
    }

    $settings->delete_all_cached_data();

    wp_send_json_success(['message' => 'Cache atualizado com sucesso!']);
});

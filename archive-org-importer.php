<?php
/*
Plugin Name: Archive.org İçerik Aktarma
Plugin URI: www.hicabierdem.com
Description: Archive.org üzerinden içerikleri WordPress'e aktarmanızı sağlar
Version: 1.0
Author: Hicabi Erdem
*/

// Güvenlik kontrolü
if (!defined('ABSPATH')) {
    exit;
}

// Gerekli sınıfları dahil et
require_once(plugin_dir_path(__FILE__) . 'includes/class-archive-scraper.php');
require_once(plugin_dir_path(__FILE__) . 'includes/class-wp-poster.php');

// Admin menüsünü oluştur
function aoi_admin_menu() {
    add_menu_page(
        'Archive.org İçeri Aktar',
        'Archive İçeri Aktar',
        'manage_options',
        'archive-org-importer',
        'aoi_admin_page',
        'dashicons-download'
    );
}
add_action('admin_menu', 'aoi_admin_menu');

// Admin sayfası içeriği
function aoi_admin_page() {
    require_once(plugin_dir_path(__FILE__) . 'admin/admin-page.php');
}

// AJAX işleyicileri
add_action('wp_ajax_fetch_archive_content', 'aoi_fetch_archive_content');
add_action('wp_ajax_import_archive_items', 'aoi_import_archive_items');

function aoi_fetch_archive_content() {
    if (!isset($_POST['url'])) {
        wp_send_json_error('URL gerekli');
    }
    
    $file_types = isset($_POST['file_types']) ? $_POST['file_types'] : ['mp3'];
    $scraper = new ArchiveScraper($_POST['url'], $file_types);
    $items = $scraper->fetch_content();
    
    wp_send_json_success($items);
}

function aoi_import_archive_items() {
    if (!isset($_POST['items'])) {
        wp_send_json_error('Öğeler seçilmedi');
    }
    
    $poster = new WPPoster();
    $results = [];
    
    foreach ($_POST['items'] as $item) {
        $post_id = $poster->create_post(
            $item['title'],
            $item['link'],
            $item['content'],
            $item['category'] ? [$item['category']] : []
        );
        
        if ($post_id) {
            $results[] = $post_id;
        }
    }
    
    wp_send_json_success($results);
}

// Admin scripts
function aoi_admin_scripts() {
    wp_enqueue_script('jquery');
}
add_action('admin_enqueue_scripts', 'aoi_admin_scripts'); 

<?php
/**
 * Plugin Name: Jap
 * Plugin URI: https://www.wordpress.org/jap
 * Description: This plugin is for only Jap
 * Version: 1.0
 * Requires at least: 5.6
 * Author: Digital Connection
 * Author URI: https://www.digitalconnection.pt
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: jap
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('Jap')) {
    class Jap
    {
        function __construct()
        {
            $this->define_constants();

            // Controller for Admin
            require_once(JAP_PATH . 'functions/functions.php');


            require_once( JAP_PATH . "route/class.jap.route.php" );
            $KIA_REQUEST_Route = new Jap_Route();

            require_once( JAP_PATH . "helper.php" );
            // Include scripts for dashboard
            add_action( 'admin_init', array( $this, 'jap_css_js' ));
        }

        public function define_constants()
        {


            define('JAP_PATH', plugin_dir_path(__FILE__));
            define('JAP_URL', plugin_dir_url(__FILE__));
            define('JAP_VERSION', '1.0.3');
            global $wpdb;
            define("TABLE_COMPETICAO", $wpdb->prefix . 'competicao');
            define("TABLE_MOMENTO", $wpdb->prefix . 'momentos');
            define("TABLE_MOMENTO_META", $wpdb->prefix . 'momento_meta');
            define("TABLE_EQUIPAS", $wpdb->prefix . 'equipas');
            define("TABLE_EQUIPAS_MOMENTO", $wpdb->prefix . 'equipas_momento');
            define("TABLE_GROUPO", $wpdb->prefix . 'groupo');
            define("TABLE_JURIS_EQUIPAS_RANK", $wpdb->prefix . 'juris_equipas_rank');
            define("TABLE_JURIS_MOMENTO", $wpdb->prefix . 'juris_momento');
            define("TABLE_USERS", $wpdb->prefix . 'users');

        }

        public static function activate()
        {
            update_option('rewrite_rules', '');

            /** Creating custom table */
            require_once(JAP_PATH . 'table/table.php');
        }

        public static function deactivate()
        {
            flush_rewrite_rules();
            delete_option('jap_db_version');
        }

        public static function uninstall()
        {
            // Handle uninstallation if needed
        }

        function jap_css_js()
        {

            wp_register_style('jap_fontawesome', "https://pro.fontawesome.com/releases/v5.10.0/css/all.css", __FILE__);
            wp_enqueue_style('jap_fontawesome');
            wp_enqueue_style( 'custom_style_css', JAP_URL . 'assets/css/style.css', array(), JAP_VERSION, 'all' );

            wp_register_script( 'jap_custom_js', JAP_URL . 'assets/js/custom.js', array( 'jquery' ), JAP_VERSION, true );
            wp_enqueue_script( 'jap_custom_js' );

            wp_register_style( 'jap_datatables_css', JAP_URL . 'assets/css/datatable.css',  __FILE__,JAP_VERSION );
            wp_register_script( 'jap_datatables_js', JAP_URL . 'assets/js/datatable.js', array( 'jquery' ), JAP_VERSION, true );



        }

    }
}

if (class_exists('Jap')) {
    register_activation_hook(__FILE__, array('Jap', 'activate'));
    register_deactivation_hook(__FILE__, array('Jap', 'deactivate'));
    register_uninstall_hook(__FILE__, array('Jap', 'uninstall'));

    $jap = new Jap();
}
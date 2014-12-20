<?php
/*
 * Plugin Name: Third Wunder Projects Plugin
 * Version: 1.0
 * Plugin URI: http://www.thirdwunder.com/
 * Description: Third Wunder slides CPT plugin
 * Author: Mohamed Hamad
 * Author URI: http://www.thirdwunder.com/
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: tw-projects-plugin
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Mohamed Hamad
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-tw-projects-plugin.php' );
require_once( 'includes/class-tw-projects-plugin-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/class-tw-projects-plugin-admin-api.php' );
require_once( 'includes/lib/class-tw-projects-plugin-post-type.php' );
require_once( 'includes/lib/class-tw-projects-plugin-taxonomy.php' );

if(!class_exists('AT_Meta_Box')){
  require_once("includes/My-Meta-Box/meta-box-class/my-meta-box-class.php");
}

/**
 * Returns the main instance of TW_Projects_Plugin to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object TW_Projects_Plugin
 */
function TW_Projects_Plugin () {
	$instance = TW_Projects_Plugin::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = TW_Projects_Plugin_Settings::instance( $instance );
	}

	return $instance;
}

TW_Projects_Plugin();
$prefix = 'tw_';

$projects_category = get_option('wpt_tw_project_category') ? get_option('wpt_tw_project_category') : "off";
$projects_tag      = get_option('wpt_tw_project_tag') ? get_option('wpt_tw_project_tag') : "off";

TW_Projects_Plugin()->register_post_type(
                        'tw_project',
                        __( 'Projects',     'tw-projects-plugin' ),
                        __( 'Project',      'tw-projects-plugin' ),
                        __( 'Projects CPT', 'tw-projects-plugin'),
                        array(
                          'menu_icon'=>plugins_url( 'assets/img/cpt-icon-project.png', __FILE__ ),
                        )
                    );

if($projects_category=='on'){
  TW_Projects_Plugin()->register_taxonomy( 'tw_project_category', __( 'Project Categories', 'tw-projects-plugin' ), __( 'Project Category', 'tw' ), 'tw_project', array('hierarchical'=>true) );
}

if($projects_tag=='on'){
 TW_Projects_Plugin()->register_taxonomy( 'tw_project_tag', __( 'Project Tags', 'tw-projects-plugin' ), __( 'Project Tag', 'tw-projects-plugin' ), 'tw_project', array('hierarchical'=>false) );
}
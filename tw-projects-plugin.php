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

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

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

$project_slug = get_option('wpt_tw_project_slug') ? get_option('wpt_tw_project_slug') : "project";
$project_search = get_option('wpt_tw_project_search') ? true : false;
$project_archive = get_option('wpt_tw_project_archive') ? true : false;

$project_category = (get_option('wpt_tw_project_category')=='on') ? true : false;
$project_tag      = (get_option('wpt_tw_project_tag')=='on') ? true : false;

$project_testimonials = (get_option('wpt_tw_project_testimonials')=='on') ? true : false;
$project_client       = (get_option('wpt_tw_project_client')=='on') ? true : false;

TW_Projects_Plugin()->register_post_type(
                        'tw_project',
                        __( 'Projects',     'tw-projects-plugin' ),
                        __( 'Project',      'tw-projects-plugin' ),
                        __( 'Projects CPT', 'tw-projects-plugin'),
                        array(
                          'menu_icon'=>plugins_url( 'assets/img/cpt-icon-project.png', __FILE__ ),
                          'rewrite' => array('slug' => $project_slug),
                          'exclude_from_search' => $project_search,
                          'has_archive'     => $project_archive,
                        )
                    );

if($project_category=='on'){
  TW_Projects_Plugin()->register_taxonomy( 'tw_project_category', __( 'Project Categories', 'tw-projects-plugin' ), __( 'Project Category', 'tw' ), 'tw_project', array('hierarchical'=>true) );
}

if($project_tag=='on'){
 TW_Projects_Plugin()->register_taxonomy( 'tw_project_tag', __( 'Project Tags', 'tw-projects-plugin' ), __( 'Project Tag', 'tw-projects-plugin' ), 'tw_project', array('hierarchical'=>false) );
}


if (is_admin()){
  $project_config = array(
    'id'             => 'tw_project_cpt_metabox',
    'title'          => 'Project Details',
    'pages'          => array('tw_project'),
    'context'        => 'normal',
    'priority'       => 'high',
    'fields'         => array(),
    'local_images'   => true,
    'use_with_theme' => false
  );
  $project_meta =  new AT_Meta_Box($project_config);

  $project_meta->addText('tw_project_url',array('name'=> 'Project URL', 'desc'=>'Project Website URL. External links must include http://'));

  if(is_plugin_active('tw-clients-plugin/tw-clients-plugin.php') && $project_client){
    $project_meta->addPosts('tw_project_client',array('post_type' => 'tw_client'),array('name'=> 'Client'));
  }

  if( is_plugin_active( 'tw-testimonials-plugin/tw-testimonials-plugin.php' ) && $project_testimonials ){
    $project_meta->addPosts('tw_project_testimonials',array('post_type' => 'tw_testimonial', 'type'=>'checkbox_list'),array('name'=> 'Testimonials'));
  }

  $project_meta->Finish();

}
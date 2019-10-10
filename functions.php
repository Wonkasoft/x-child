<?php
// =============================================================================
// FUNCTIONS.PHP
// -----------------------------------------------------------------------------
// Overwrite or add your own custom functions to X in this file.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
// 01. Enqueue Parent Stylesheet
// 02. Additional Functions
// =============================================================================

// Enqueue Parent Stylesheet
// =============================================================================

add_filter( 'x_enqueue_parent_stylesheet', '__return_true' );

// Boot Registry
// =============================================================================

function wonkasoft_x_boot_registry() {
	return array(
		'preinit'   => array(
			'functions/helpers',
			'functions/thumbnails',
			'functions/setup',

			'tco/tco',
			'legacy/setup',
			'functions/fonts',
			'functions/plugins/setup',
			'functions/updates/class-x-tgmpa-integration',
			'functions/updates/class-tgm-plugin-activation',
		),

		'init'      => array(
			'functions/frontend/conditionals',
		),

		'front_end' => array(
			'functions/frontend/portfolio',
			'functions/frontend/view-routing',
			'functions/frontend/styles',
			'functions/frontend/scripts',
			'functions/frontend/content',
			'functions/frontend/classes',
			'functions/frontend/meta',
			'functions/frontend/integrity',
			'functions/frontend/renew',
			'functions/frontend/icon',
			'functions/frontend/ethos',
			'functions/frontend/social',
			'functions/frontend/breadcrumbs',
			'functions/frontend/pagination',
			'functions/frontend/featured',
		),

		'logged_in' => array(),

		'admin'     => array(
			'functions/admin/class-validation',
			'functions/updates/class-theme-updater',
			'functions/updates/class-plugin-updater',
			'functions/admin/class-validation-updates',
			'functions/admin/class-validation-theme-options-manager',
			'functions/admin/class-validation-extensions',
			'functions/admin/setup',
			'functions/admin/customizer',
			'functions/admin/meta-boxes',
			'functions/admin/meta-entries',
			'functions/admin/taxonomies',
		),

		'app_init'  => array(
			'functions/theme-options',
		),

		'ajax'      => array(),

	);
}

// Bootstrap Class
// =============================================================================

function wonksoft_x_run() {

	class Wonkasoft_X_Bootstrap extends X_Bootstrap {

		private static $instance;
		protected $registry              = array();
		protected $theme_option_defaults = array();

		public function boot() {

			// Define Path / URL Constants
			// ---------------------------
			define( 'X_CHILD_TEMPLATE_PATH', get_stylesheet_directory() );
			define( 'X_CHILD_TEMPLATE_URL', get_stylesheet_directory_uri() );
			define( 'X_TEMPLATE_PATH', get_template_directory() );
			define( 'X_TEMPLATE_URL', get_template_directory_uri() );

			// Preboot
			// -------

			$x_boot_files = glob( X_TEMPLATE_PATH . '/framework/load/*.php' );

			sort( $x_boot_files );

			foreach ( $x_boot_files as $filename ) {
				$file = basename( $filename, '.php' );
				if ( file_exists( $filename ) && apply_filters( "x_pre_boot_$file", '__return_true' ) ) {
					require_once $filename;
				}
			}

			// Set Asset Revision Constant (For Cache Busting)
			// -----------------------------------------------

			define( 'X_ASSET_REV', X_VERSION );

			// Preinit
			// --------

			$this->registry = wonkasoft_x_boot_registry();
			$this->boot_context( 'preinit' );

			// Theme Option Defaults
			// ---------------------
			$this->theme_option_defaults = include X_TEMPLATE_PATH . '/framework/data/option-defaults.php';

			if ( is_admin() ) {
				$this->boot_context( 'admin' );
			}

			add_action( 'init', array( $this, 'init' ) );
			add_action( 'admin_init', array( $this, 'ajax_init' ) );
			add_action( 'cornerstone_before_boot_app', array( $this, 'app_init' ) );
			add_action( 'cornerstone_before_custom_endpoint', array( $this, 'app_init' ) );
			add_action( 'cornerstone_before_admin_ajax', array( $this, 'app_init' ) );
			add_action( 'cornerstone_before_admin_ajax', array( $this, 'ajax_init' ) );
			add_action( 'cornerstone_before_custom_endpoint', array( $this, 'ajax_init' ) );

		}

		public function init() {

			$this->boot_context( 'init' );

			if ( ! is_admin() ) {
				$this->boot_context( 'front_end' );
			}

			if ( is_user_logged_in() ) {
				$this->boot_context( 'logged_in' );
			}

		}

		public function admin_init() {
			$this->boot_context( 'admin_init' );
		}

		public function app_init() {
			$this->boot_context( 'app_init' );
		}

		public function ajax_init() {
			if ( defined( 'DOING_AJAX' ) ) {
				$this->boot_context( 'ajax' );
			}
		}

		public function boot_context( $context ) {

			if ( ! isset( $this->registry[ $context ] ) ) {
				return;
			}

			foreach ( $this->registry[ $context ] as $file ) {
				if ( 'preinit' === $context && 'functions/plugins/setup' === $file ) {
					require_once X_CHILD_TEMPLATE_PATH . "/framework/$file.php";
				} else {
					require_once X_TEMPLATE_PATH . "/framework/$file.php";
				}
			}

			do_action( 'x_boot_' . $context );

		}

		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new Wonkasoft_X_Bootstrap();
			}
			return self::$instance;
		}

		public function get_theme_option_defaults() {
			return $this->theme_option_defaults;
		}

		public function get_theme_option_default( $key ) {
			return isset( $this->theme_option_defaults[ $key ] ) ? $this->theme_option_defaults[ $key ] : false;
		}

	}

	function wonkasoft_x_bootstrap() {
		return Wonkasoft_X_Bootstrap::instance();
	}

	wonkasoft_x_bootstrap()->boot();
}
add_action( 'after_setup_theme', 'wonksoft_x_run' );


// Additional Functions
// =============================================================================
register_nav_menus(
	array(
		'rsc_videos_menu' => __( 'Videos Menu', '__x__' ),
		'profile_menu'    => __( 'Profile Navigation Menu', '__x__' ),
	)
);

/*
=======================================================
=            Custom Functions for Buddypress            =
=======================================================*/
require_once get_stylesheet_directory() . '/inc/custom-buddypress.php';

/*=====  End of Custom Functions for Buddypress  ======*/

/*
=======================================================
=            Custom Functions for menu-items            =
=======================================================*/
require_once get_stylesheet_directory() . '/inc/custom-menu-items.php';
/*=====  End of Custom Functions for menu-items  ======*/

/*
=======================================================
=            Custom Functions for Gravity Forms         =
=======================================================*/
require_once get_stylesheet_directory() . '/inc/custom-gravity-forms-fields.php';
/*=====  End of Custom Functions for Gravity Forms  ======*/

/*
======================================================*/
/*
			  Change Register Link
/*======================================================*/
add_filter( 'register', 'wssaved_register_link' );
function wssaved_register_link( $link ) {
	/*Required: Replace Register_URL with the URL of registration*/
	$custom_register_link = '/membership-levels/';
	/*Optional: You can optionally change the register text e.g. Signup*/
	$register_text = 'Register';
	$link          = '<a href="' . $custom_register_link . '">' . $register_text . '</a>';
	return $link;
}
/*==============End Change Register Link================*/

/*
==================================================
=            This is for the js enqueue            =
==================================================*/
function wonka_scripts() {
	// Style
	wp_enqueue_style( 'x-child', get_stylesheet_directory_uri() . '/style.css', array( '' ), false, 'all' );
	wp_enqueue_style( 'x-child-woocommerce', get_stylesheet_directory_uri() . '/woocommerce.css', array( '' ), false, 'all' );
	wp_enqueue_script( 'connect-js', get_stylesheet_directory_uri() . '/assets/js/connect.js', array( 'jquery' ), '1.0.0', true );

	// Script
	wp_enqueue_script( 'wonkamizer-js', get_stylesheet_directory_uri() . '/assets/js/x-child.min.js', array( 'jquery' ), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'wonka_scripts', 250 );
/*=====  End of This is for the js enqueue  ======*/

/*
======================================================*/
/*
		Change the activity time format
/*======================================================*/

add_filter( 'bp_activity_time_since', 'bp_activity_time_since_newformat', 10, 2 );
function bp_activity_time_since_newformat( $time_since, &$actvitiy ) {

	$timestamp_now                    = time();
	$timestamp_activity_date_recorded = strtotime( $actvitiy->date_recorded );

	if ( abs( $timestamp_now - $timestamp_activity_date_recorded ) > 60 * 60 * 24 /* (24 hours) */ ) {
		// you can change the date format to "Y-m-d H:i:s"
		$time_since = '<span class="time-since">' . date_i18n( 'F j, Y g:i a', strtotime( $actvitiy->date_recorded ) ) . '</span>';

	}
	return $time_since;
}

add_filter( 'bp_activity_comment_date_recorded', 'bp_activity_comment_date_recorded_newformat', 10, 2 );
function bp_activity_comment_date_recorded_newformat( $date_recorded ) {
	global $activities_template;

	$timestamp_now                   = time();
	$timestamp_comment_date_recorded = strtotime( $activities_template->activity->current_comment->date_recorded );

	if ( abs( $timestamp_now - $timestamp_comment_date_recorded ) > 60 * 60 * 24 /* (24 hours) */ ) {
		// you can change the date format to "Y-m-d H:i:s"
		$date_recorded = date_i18n( 'F j, Y g:i a', $timestamp_comment_date_recorded );
	}
	return $date_recorded;
}

/*==============End activity time format================*/

/*
======================================================*/
/*
		Click on connect and follow will be done
/*======================================================*/

add_action( 'friends_friendship_requested', 'bp_friends_custom_request_and_follow', 10, 3 );
function bp_friends_custom_request_and_follow( $friendship_id ) {
	$friendship = new BP_Friends_Friendship( $friendship_id, true, false );
	bp_follow_start_following(
		array(
			'leader_id'   => $friendship->friend_user_id,
			'follower_id' => $friendship->initiator_user_id,
		)
	);

}

// add_action( 'friends_friendship_accepted',  'bp_friends_custom_accept_and_follow', 10, 3 );
function bp_friends_custom_accept_and_follow( $friendship_id ) {
	$friendship = new BP_Friends_Friendship( $friendship_id, true, false );
	bp_follow_start_following(
		array(
			'leader_id'   => $friendship->initiator_user_id,
			'follower_id' => $friendship->friend_user_id,
		)
	);
}
/*==============End Click on connect================*/

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

function wonkasoft_bbb_form_submission( $entry, $form ) {

	

}
add_action( 'gform_after_submission', 'wonkasoft_bbb_form_submission', 10, 2 );

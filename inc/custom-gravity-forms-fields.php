<?php
/**
 * This file contains custom fields added on the gravity forms
 *
 * @since 1.0.0
 * 
 */
class WONKA_CUSTOM_AFFILIATE_FIELD extends GF_Field {

	public $type = 'affiliate_code';

	public function get_form_editor_field_title() {
	    return esc_attr__( 'Affiliate Code', 'gravityforms' );
	} 

	public function get_form_editor_button() {
		$button = array(
	        'group' => 'advanced_fields',
	        'text'  => $this->get_form_editor_field_title()
	    );
	    return $button;
	}

	function get_form_editor_field_settings() {
		$settings = array(
			'prepopulate_field_setting',
			'label_setting',
			'label_placement_setting',
			'admin_label_setting',
			'placeholder_setting',
			'size_setting',
			'visibility_setting',
		);

		return $settings;
	}

	public function get_field_input( $form, $value = '', $entry = null ) {
	    $form_id         = $form['id'];
	    $is_entry_detail = $this->is_entry_detail();
	    $id              = (int) $this->id;
	    $is_admin        = $is_entry_detail || $is_form_editor;

	    if ( $is_entry_detail || $is_admin ) {
	            $input = "<input type='hidden' id='input_{$id}' name='input_{$id}' value='{$value}' />";
	     
	            return $input . '<br/>' . esc_html__( 'Affiliate Code is not editable', 'gravityforms' );
	        }

	    $placeholder_attribute = $this->get_field_placeholder_attribute();
	    $size_class = ' ' . $this->size;
	    if ( function_exists( 'pmpro_affiliates_getAffiliatesForUser' ) ) :
		    global $current_user;
		    $code = ( !empty( pmpro_affiliates_getAffiliatesForUser( $current_user->ID )[0]->code ) ) ? pmpro_affiliates_getAffiliatesForUser( $current_user->ID )[0]->code: '';
	    endif;

	    $input = "<div class='ginput_container' id='gf_affiliate_code_container_{$form_id}'>";
	    $input .= "<input id='gf_affiliate_code_{$form_id}' class='gf-affiliate-code{$size_class}' value='{$code}' readonly='true' name='input_{$id}' type='text' {$placeholder_attribute} />";
	    $input .= "</div>";

	    return $input;
	}
}

GF_Fields::register( new WONKA_CUSTOM_AFFILIATE_FIELD() );
<?php

defined('ABSPATH') or die('Jog on!');

// This is used to keep track of multiple forms on a page allowing us to pass messages to each
global $form_number;

/*
	Displays either a target or weight form
*/
function ws_ls_form_weight( $arguments = [] ) {

	$arguments = wp_parse_args( $arguments, [   'css-class-form'        => '',
	                                            'entry-id'              => NULL,
	                                            'hide-button-cancel'    => true,
	                                            'hide-fields-meta'      => false,
	                                            'hide-fields-photos'    => false,
	                                            'hide-login-message'    => false,
	                                            'hide-titles'           => false,
	                                            'html'                  => '',
	                                            'option-force-today'    => false,
	                                            'is-target-form'        => false,
	                                            'redirect-url'          => '',
	                                            'user-id'               => get_current_user_id() ] );

	// Is the user logged in?
	if ( false === is_user_logged_in() ) {
		// Suppress login prompt?
		return ( false === $arguments[ 'hide-login-message' ] ) ? ws_ls_blockquote_login_prompt() : '';
	}

	// If a ID has been specified, let's try and fetch the entry in question
	if ( false === empty( $arguments[ 'entry-id'] ) &&
	        false === $arguments[ 'is-target-form' ] ) {

		$arguments[ 'entry' ] = ws_ls_entry_get( [ 'user-id' => $arguments[ 'user-id' ], 'id' => $arguments[ 'entry-id'] ] );

		// Did we manage to fetch an entry?
		if ( true === empty( $arguments[ 'entry' ] ) ) {
			return ws_ls_blockquote_error( __( 'There was an issue loading the data for this weight entry.', WE_LS_SLUG ) );
		}
	} else {
		$arguments[ 'entry-id'] = 0;    // If a target form, then blank ID
	}

	$arguments  = ws_ls_form_init( $arguments );
	$html       = $arguments[ 'html' ];

	$html .= sprintf( '<form action="%1$s" method="post" class="we-ls-weight-form we-ls-weight-form-validate ws_ls_display_form %2$s"
									id="%3$s" data-is-target-form="%4$s" data-metric-unit="%5$s" %9$s>
									<input type="hidden" name="target-form" value="%4$s" />
									<input type="hidden" name="user-id" value="%6$d" />
									<input type="hidden" name="security" value="%7$s" />
									<input type="hidden" name="entry-id" value="%8$d" />
									<input type="hidden" name="redirect-url" value="%10$s" />
									<input type="hidden" name="form-number" value="%11$d" />',
							esc_url_raw( $arguments[ 'post-url' ] ),
							esc_attr( $arguments[ 'css-class-form' ] ),
							$arguments[ 'form-id' ],
							( true === $arguments[ 'is-target-form' ] ) ? 'true' : 'false',
							esc_attr( $arguments[ 'data-unit' ] ),
							esc_attr( $arguments[ 'user-id' ] ),
							esc_attr( wp_hash( $arguments[ 'user-id' ] ) ),
							$arguments[ 'entry-id' ],
							( true === $arguments[ 'photos-enabled' ]  ) ? 'enctype="multipart/form-data"' : '',
							esc_url_raw( $arguments[ 'redirect-url' ] ),
							$arguments[ 'form-number' ]
	);


	$html .= '</form>';
	print_R($arguments);




	$html .= '<div class="ws-ls-inner-form">';

	$html .= '</div>';

	return $html;
//
//	';
//
//	// If not a target form include date
//	if (!$target_form) {
//
//		$default_date = date("d/m/Y");
//
//		// Do we have an existing value?
//		if($existing_date = ws_ls_get_existing_value($existing_data, 'date-display')) {
//			$default_date = $existing_date;
//		} else if (ws_ls_get_config('WE_LS_US_DATE')) { // Override if US
//			$default_date = date("m/d/Y");
//		}
//
//		if(false == $force_to_todays_date){
//			$html_output .= '<input type="text" name="we-ls-date" tabindex="' . ws_ls_form_tab_index_next() . '" id="we-ls-date-' . esc_attr($form_id) . '" value="' . esc_attr($default_date) . '" placeholder="' . esc_attr($default_date) . '" size="22" class="we-ls-datepicker">';
//		} else {
//			$html_output .= '<input type="hidden" name="we-ls-date" value="' . esc_attr($default_date) . '">';
//		}
//
//	} else {
//
//		$target_weight = ws_ls_target_get( $user_id );
//
//		if ($target_weight['display'] != '') {
//
//			$pre_text = (false === is_admin()) ? __('Your target weight is', WE_LS_SLUG) : __('The user\'s target weight is currently', WE_LS_SLUG);
//
//			$html_output .= '<p>' . esc_html( $pre_text ) . ' <strong>' . esc_html( $target_weight['display'] ) . '</strong>.</p>';
//		}
//	}
//
//	// Display the relevant weight fields depending on weight unit selected
//	if( ws_ls_get_config('WE_LS_IMPERIAL_WEIGHTS', $user_id ) )
//	{
//		if ( 'stones_pounds' === ws_ls_get_config('WE_LS_DATA_UNITS' , $user_id ) ) {
//			$html_output .= '<input  type="number"  tabindex="' . ws_ls_form_tab_index_next() . '" step="any" min="0" name="we-ls-weight-stones" id="we-ls-weight-stones" value="' . ws_ls_get_existing_value($existing_data, 'stones') . '" placeholder="' . __('Stones', WE_LS_SLUG) . '" size="11" >';
//			$html_output .= '<input  type="number" tabindex="' . ws_ls_form_tab_index_next() . '" step="any" min="0" max="13.99" name="we-ls-weight-pounds" id="we-ls-weight-pounds" value="' . ws_ls_get_existing_value($existing_data, 'pounds') . '" placeholder="' . __('Pounds', WE_LS_SLUG) . '" size="11"  >';
//		}
//		else {
//			$html_output .= '<input  type="number" tabindex="' . ws_ls_form_tab_index_next() . '" step="any" min="1" name="we-ls-weight-pounds" id="we-ls-weight-pounds" value="' . ws_ls_get_existing_value($existing_data, 'only_pounds') . '" placeholder="' . __('Pounds', WE_LS_SLUG) . '" size="11"  >';
//		}
//	}
//	else {
//		$html_output .= '<input  type="number" tabindex="' . ws_ls_form_tab_index_next() . '" step="any" min="1" name="we-ls-weight-kg" id="we-ls-weight-kg" value="' . ws_ls_get_existing_value($existing_data, 'kg') . '" placeholder="' . __('Weight', WE_LS_SLUG) . ' (' . __('kg', WE_LS_SLUG) . ')" size="22" >';
//	}
//
//	$html_output .= '</div>';
//
//	// Display notes section if not target form
//	if (false === $target_form) {
//
//		$html_output .= '<div id="comment-textarea">
//							<textarea name="we-ls-notes" tabindex="' . ws_ls_form_tab_index_next() . '" id="we-ls-notes" cols="39" rows="4" tabindex="4" class="textarea-comment" placeholder="' . __('Notes', WE_LS_SLUG) . '">' . esc_textarea(ws_ls_get_existing_value($existing_data, 'notes', false)) . '</textarea>
//						</div>';
//	}
//
//	// Render Meta Fields
//	if ( false === $target_form && true === $meta_field_form_enabled ) {
//		$html_output .= ws_ls_meta_fields_form( $existing_data );
//	}
//
//	$button_text = ($target_form) ?  __('Set Target', WE_LS_SLUG) :  __('Save Entry', WE_LS_SLUG);
//
//	$html_output .= '<div class="ws-ls-form-buttons">
//						<div>
//						    <div class="ws-ls-error-summary ws-ls-hide-if-admin">
//						        <p>' . __('Please correct the following:', WE_LS_SLUG) . '</p>
//                                <ul></ul>
//                            </div>
//                            <div class="ws-ls-form-processing-throbber ws-ls-loading ws-ls-hide"></div>
//							<input name="submit_button" type="submit" id="we-ls-submit"  tabindex="' . ws_ls_form_tab_index_next() . '" value="' . $button_text . '" class="comment-submit button ws-ls-remove-on-submit" />';
//
//	// If we want a cancel button then add one
//	if ( false === empty( $cancel_button ) && false === $target_form && false === empty( $redirect_url ) ) {
//		$html_output .= '&nbsp;<button id="ws-ls-cancel" type="button" tabindex="' . ws_ls_form_tab_index_next() . '" class="ws-ls-cancel-form button ws-ls-remove-on-submit" data-form-id="' . esc_attr($form_id) . '" >' . __('Cancel', WE_LS_SLUG) . '</button>';
//	}
//
//	//If a target form, display "Clear Target" button
//	if ($target_form && false === is_admin()){
//		$html_output .= '&nbsp;<button name="ws-ls-clear-target" id="ws-ls-clear-target" type="button" tabindex="' . ws_ls_form_tab_index_next() . '" class="ws-ls-clear-target button ws-ls-remove-on-submit" >' . __('Clear Target', WE_LS_SLUG) . '</button>';
//	}
//	$html_output .= '	</div>
//					</div>
//	</form>';
//
//	return $html_output;
}


/**
 * Initialise the form config
 * @param array $arguments
 *
 * @return array
 */
function ws_ls_form_init( $arguments = [] ) {

	$arguments[ 'form-id' ]     = ws_ls_component_id();
	$arguments[ 'form-number' ] = ws_ls_form_number_next();
	$arguments[ 'data-unit' ]   = ws_ls_get_config('WE_LS_DATA_UNITS', $arguments[ 'user-id' ] );

	global $save_response;

	// Has this form been previously submitted?
	if ( false === empty( $save_response ) &&
	     $arguments[ 'form-number'] === $save_response['form_number'] ){
		$arguments[ 'html' ] .=  ws_ls_display_blockquote( $save_response[ 'message' ] );
	}

	// Main title for form
	if ( true === $arguments[ 'is-target-form' ] ) {
		$title = __( 'Target weight', WE_LS_SLUG );
	} else if ( false === empty( $arguments[ 'entry' ] ) ) {
		$title = __( 'Edit an existing entry', WE_LS_SLUG );
	} else {
		$title = __( 'Add a new weight entry', WE_LS_SLUG );
	}

	$arguments[ 'html' ] .= ws_ls_form_title( $title, $arguments[ 'hide-titles' ] );

	// Allow others to determine where form is posted too
	$arguments[ 'post-url' ] = apply_filters( 'wlt_form_url', get_permalink() );

	// Are meta fields enabled for this form?
	$arguments[ 'meta-enabled' ]  = ( false === $arguments[ 'is-target-form' ] &&
	                                  false === $arguments[ 'hide-fields-meta' ] &&
	                                  true === ws_ls_meta_fields_is_enabled() &&
	                                  ws_ls_meta_fields_number_of_enabled() > 0 );


	// Are photo fields enabled for this form?
	$arguments[ 'photos-enabled' ] = ( false === $arguments[ 'hide-fields-photos' ] &&
	                                   true === $arguments[ 'meta-enabled' ] &&
	                                   true === ws_ls_meta_fields_photo_any_enabled( true ) );

	return $arguments;
}


/*
	Displays either a target or weight form
*/
function ws_ls_display_weight_form($target_form = false, $class_name = false, $user_id = false, $hide_titles = false,
	$form_number = false, $force_to_todays_date = false, $hide_login_message_if_needed = true,
	$hide_measurements_form = false, $redirect_url = false, $existing_data = false, $cancel_button = false,
	$hide_photos_form = false, $hide_meta_fields_form = false ) {
	global $save_response;
	$html_output  = '';

	$photo_form_enabled = ( false === $hide_photos_form && true === ws_ls_meta_fields_photo_any_enabled( true ) && false === $target_form);
	$meta_field_form_enabled = ( false === $hide_meta_fields_form && true === ws_ls_meta_fields_is_enabled() && ws_ls_meta_fields_number_of_enabled() > 0 && false === $target_form);
	$entry_id = NULL;

	// Make sure they are logged in
	if (!is_user_logged_in())	{
		if ($hide_login_message_if_needed) {

			$prompt = ( true === $target_form ) ? __('You need to be logged in to set your target.', WE_LS_SLUG) : __('You need to be logged in to record your weight.', WE_LS_SLUG);

			return ws_ls_display_blockquote($prompt, '', false, true);
		} else {
			return '';
		}
	}

	if(true === empty($user_id)){
		$user_id = get_current_user_id();
	}

	$form_id = 'ws_ls_form_' . rand(10,1000) . '_' . rand(10,1000);

	// Set title / validator
	if (!$hide_titles) {

		$title = __('Add a new weight', WE_LS_SLUG);

		if ($target_form) {
			$title = __('Target Weight', WE_LS_SLUG);
		} else if( false === empty($existing_data) ) {
			$title = __('Edit weight', WE_LS_SLUG);
		}

		$html_output .= '<h3 class="ws_ls_title">' . esc_html($title) . '</h3>';
	}

	// If a form was previously submitted then display resulting message!
	if ($form_number && !empty($save_response) && $save_response['form_number'] == $form_number){
		$html_output .= $save_response['message'];
	}

	$post_url = apply_filters( 'wlt_form_url', get_permalink() );

	$html_output .= sprintf('
							<form action="%1$s" method="post" class="we-ls-weight-form we-ls-weight-form-validate ws_ls_display_form%2$s" id="%3$s"
							data-is-target-form="%4$s"
							data-metric-unit="%5$s",
							data-photos-enabled="%9$s",
							%8$s
							>
							<input type="hidden" value="%4$s" id="ws_ls_is_target_form" name="ws_ls_is_target_form" />
							<input type="hidden" value="true" id="ws_ls_is_weight_form" name="ws_ls_is_weight_form" />
							<input type="hidden" value="%6$s" id="ws_ls_user_id" name="ws_ls_user_id" />
							<input type="hidden" value="%7$s" id="ws_ls_security" name="ws_ls_security" />',
		esc_url( $post_url ),
		(($class_name) ? ' ' . esc_attr( $class_name ) : ''),
		esc_attr( $form_id ),
		( ( true === $target_form ) ? 'true' : 'false'),
		esc_attr (ws_ls_get_chosen_weight_unit_as_string( $user_id ) ),
		esc_attr($user_id),
		esc_attr( wp_hash($user_id) ),
		( true === $photo_form_enabled) ? ' enctype="multipart/form-data"' : '',
		(($photo_form_enabled) ? 'true' : 'false')
	);

	// Do we have data? If so, embed existing row ID
	if(!empty($existing_data['id']) && is_numeric($existing_data['id'])) {
		$entry_id = (int) $existing_data['id'];
		$html_output .= '<input type="hidden" value="' . $entry_id . '" id="db_row_id" name="db_row_id" />';
	}

	// Redirect form afterwards?
	if($redirect_url) {
		$html_output .= '<input type="hidden" value="' . esc_url($redirect_url) . '" id="ws_redirect" name="ws_redirect" />';
	}

	if($form_number){
		$html_output .= '	<input type="hidden" value="' . esc_attr($form_number) . '" id="ws_ls_form_number" name="ws_ls_form_number" />';
	}

	$html_output .= '<div class="ws-ls-inner-form comment-input">

	';

	// If not a target form include date
	if (!$target_form) {

		$default_date = date("d/m/Y");

		// Do we have an existing value?
		if($existing_date = ws_ls_get_existing_value($existing_data, 'date-display')) {
			$default_date = $existing_date;
		} else if (ws_ls_get_config('WE_LS_US_DATE')) { // Override if US
			$default_date = date("m/d/Y");
		}

		if(false == $force_to_todays_date){
			$html_output .= '<input type="text" name="we-ls-date" tabindex="' . ws_ls_form_tab_index_next() . '" id="we-ls-date-' . esc_attr($form_id) . '" value="' . esc_attr($default_date) . '" placeholder="' . esc_attr($default_date) . '" size="22" class="we-ls-datepicker">';
		} else {
			$html_output .= '<input type="hidden" name="we-ls-date" value="' . esc_attr($default_date) . '">';
		}

	} else {

		$target_weight = ws_ls_target_get( $user_id );

		if ($target_weight['display'] != '') {

			$pre_text = (false === is_admin()) ? __('Your target weight is', WE_LS_SLUG) : __('The user\'s target weight is currently', WE_LS_SLUG);

			$html_output .= '<p>' . esc_html( $pre_text ) . ' <strong>' . esc_html( $target_weight['display'] ) . '</strong>.</p>';
		}
	}

	// Display the relevant weight fields depending on weight unit selected
	if( ws_ls_get_config('WE_LS_IMPERIAL_WEIGHTS', $user_id ) )
	{
		if ( 'stones_pounds' === ws_ls_get_config('WE_LS_DATA_UNITS' , $user_id ) ) {
			$html_output .= '<input  type="number"  tabindex="' . ws_ls_form_tab_index_next() . '" step="any" min="0" name="we-ls-weight-stones" id="we-ls-weight-stones" value="' . ws_ls_get_existing_value($existing_data, 'stones') . '" placeholder="' . __('Stones', WE_LS_SLUG) . '" size="11" >';
			$html_output .= '<input  type="number" tabindex="' . ws_ls_form_tab_index_next() . '" step="any" min="0" max="13.99" name="we-ls-weight-pounds" id="we-ls-weight-pounds" value="' . ws_ls_get_existing_value($existing_data, 'pounds') . '" placeholder="' . __('Pounds', WE_LS_SLUG) . '" size="11"  >';
		}
		else {
			$html_output .= '<input  type="number" tabindex="' . ws_ls_form_tab_index_next() . '" step="any" min="1" name="we-ls-weight-pounds" id="we-ls-weight-pounds" value="' . ws_ls_get_existing_value($existing_data, 'only_pounds') . '" placeholder="' . __('Pounds', WE_LS_SLUG) . '" size="11"  >';
		}
	}
	else {
		$html_output .= '<input  type="number" tabindex="' . ws_ls_form_tab_index_next() . '" step="any" min="1" name="we-ls-weight-kg" id="we-ls-weight-kg" value="' . ws_ls_get_existing_value($existing_data, 'kg') . '" placeholder="' . __('Weight', WE_LS_SLUG) . ' (' . __('kg', WE_LS_SLUG) . ')" size="22" >';
	}

	$html_output .= '</div>';

	// Display notes section if not target form
	if (false === $target_form) {

		$html_output .= '<div id="comment-textarea">
							<textarea name="we-ls-notes" tabindex="' . ws_ls_form_tab_index_next() . '" id="we-ls-notes" cols="39" rows="4" tabindex="4" class="textarea-comment" placeholder="' . __('Notes', WE_LS_SLUG) . '">' . esc_textarea(ws_ls_get_existing_value($existing_data, 'notes', false)) . '</textarea>
						</div>';
	}

	// Render Meta Fields
	if ( false === $target_form && true === $meta_field_form_enabled ) {
		$html_output .= ws_ls_meta_fields_form( $existing_data );
	}

	$button_text = ($target_form) ?  __('Set Target', WE_LS_SLUG) :  __('Save Entry', WE_LS_SLUG);

	$html_output .= '<div class="ws-ls-form-buttons">
						<div>
						    <div class="ws-ls-error-summary ws-ls-hide-if-admin">
						        <p>' . __('Please correct the following:', WE_LS_SLUG) . '</p>
                                <ul></ul>
                            </div>
                            <div class="ws-ls-form-processing-throbber ws-ls-loading ws-ls-hide"></div>
							<input name="submit_button" type="submit" id="we-ls-submit"  tabindex="' . ws_ls_form_tab_index_next() . '" value="' . $button_text . '" class="comment-submit button ws-ls-remove-on-submit" />';

	// If we want a cancel button then add one
	if ( false === empty( $cancel_button ) && false === $target_form && false === empty( $redirect_url ) ) {
		$html_output .= '&nbsp;<button id="ws-ls-cancel" type="button" tabindex="' . ws_ls_form_tab_index_next() . '" class="ws-ls-cancel-form button ws-ls-remove-on-submit" data-form-id="' . esc_attr($form_id) . '" >' . __('Cancel', WE_LS_SLUG) . '</button>';
	}

	//If a target form, display "Clear Target" button
	if ($target_form && false === is_admin()){
		$html_output .= '&nbsp;<button name="ws-ls-clear-target" id="ws-ls-clear-target" type="button" tabindex="' . ws_ls_form_tab_index_next() . '" class="ws-ls-clear-target button ws-ls-remove-on-submit" >' . __('Clear Target', WE_LS_SLUG) . '</button>';
	}
	$html_output .= '	</div>
					</div>
	</form>';

	return $html_output;
}

/**
 * Format form title
 * @param $title
 * @param $hide
 *
 * @return string
 */
function ws_ls_form_title( $title, $hide ) {
	return ( false === $hide ) ?
		sprintf( '<h3 class="ws_ls_title">%s</h3>', esc_html( $title ) ) :
			'';
}
/**
 * Keep track of the current tab index and increment
 * @return int
 */
function ws_ls_form_tab_index_next() {

	global $ws_ls_tab_index;

	$current_index = $ws_ls_tab_index;
	$ws_ls_tab_index++;

	return $current_index;
}

/**
 * Keep track of the current form number and increment
 * @return mixed
 */
function ws_ls_form_number_next() {

	global $form_number;

	if ( true === empty( $form_number ) ) {
		$form_number = 1;
	}

	$current_index = $form_number;
	$form_number++;

	return $current_index;
}

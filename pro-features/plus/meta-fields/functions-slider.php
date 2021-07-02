<?php

defined('ABSPATH') or die("Jog on!");

/**
 * Functions required to support the jQuery range finder
 * https://simeydotme.github.io/jQuery-ui-Slider-Pips/#styling-circles
 */

/**
 * Generate the HTML for a meta field yes / no field
 *
 * @param $field
 * @param $value
 * @return string
 */
function ws_ls_meta_fields_form_field_range_slider( $field, $value ) {

	ws_meta_fields_range_slider_enqueue();

	$html = sprintf( '	<div class="ws-ls-meta-field">
                            <label for="%1$s" class="ws-ls-meta-field-title">%2$s:</label>
                            <div class="ws-ls-meta-fields-slider" id="%4$s"></div>
                            <input id="%4$s-value" name="%1$s" value="%8$s" type="hidden" />
                      	</div>
                      	<script>
                      		jQuery( document ).ready( function ( $ ) {
                      			$( "#%4$s" ).slider(
	                                  {
	                                    min: 	%5$s,
	                                    max: 	%6$s,
	                                    step:	%7$s,
	                                    value:	"%8$s"
	                                  }
                      			).slider( "pips",
	                                 {
	                                   rest: "%9$s"
	                                 }
                      			 ).on( "slidechange", function(e,ui) {
       						 			$( "#%4$s-value" ).val( ui.value );
    							});;
                      		});
						</script>
	                            ',
		ws_ls_meta_fields_form_field_generate_id( $field['id'] ),
		esc_attr( $field['field_name'] ),
		ws_ls_form_tab_index_next(),
		ws_ls_component_id(),
		esc_attr( $field[ 'min_value' ] ),
		esc_attr( $field[ 'max_value' ] ),
		esc_attr( $field[ 'step' ] ),
		esc_attr( $value ),
		2 === (int) $field[ 'show_all_labels' ] ? 'label' : ''
	);

	$value = (int) $value;

	return $html;

}

/**
 * Enqueue range slider JS
 */
function ws_meta_fields_range_slider_enqueue() {

	wp_enqueue_script( 'ws-ls-meta-fields-range-js', plugins_url( '/meta-fields/assets/jquery-ui-slider-pips.js', __DIR__ ), [ 'jquery', 'jquery-ui-core', 'jquery-ui-slider' ], WE_LS_CURRENT_VERSION, true );
	wp_enqueue_style( 'ws-ls-meta-fields-range-css', plugins_url( '/meta-fields/assets/jquery-ui-slider-pips.css', __DIR__ ), [], WE_LS_CURRENT_VERSION );
}

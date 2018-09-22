<?php

    defined('ABSPATH') or die("Jog on!");

	/**
	 * Listen for weight entries / updates and determine whether it should be considered for an award
	 *
	 * @param $type
	 * @param $weight_object
	 */
	function ws_ls_awards_listen( $info, $weight_object ) {

	    if ( false === WS_LS_IS_PRO_PLUS ) {
	        return;
        }

		if ( false === empty( $info['type'] ) && $info['type'] === 'weight-measurements' ) {

            // Is user gaining or losing weight?
            $losing_weight = ( $weight_object['difference_from_start_kg'] < 0 );

			$awards = ws_ls_awards_to_give( NULL, $info['mode'], $losing_weight );    // Mode: update or add

			if ( false === empty( $awards ) ) {

				// Do we have any weight awards to consider?
				if ( false === empty( $awards['counts']['weight'] ) ) {

					// Get weight difference
					var_dump($weight_object['difference_from_start_kg']);

				}


				// Check this award hasn't already been issue to the user

			}

			//var_dump( $awards );
			die;

		}

	}
	add_action( WE_LS_HOOK_DATA_ADDED_EDITED, 'ws_ls_awards_listen', 10, 2 );





//	function test() {
//
//	    $a = ws_ls_awards_to_give(1, 'add');
//        print_r($a);
//		$t= ws_ls_awards_user_times_awarded(1,33);
//		print_r($t);
//		die;
//
//	}
//	add_action('init' , 'test');
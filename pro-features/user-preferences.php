<?php
defined('ABSPATH') or die("Jog on!");

function ws_ls_user_preferences_form()
{
	$html_output = '';
	// Capture user preferences post
	if($_POST && isset($_POST['ws-ls-user-pref']) && 'true' == $_POST['ws-ls-user-pref'])	{

			$html_output .= '<div id="ws-ls-user-pref-saved" style="display:none"></div>';

			// List of form fields / globals we want to store for the user
			$keys_to_save = array('WE_LS_DATA_UNITS', 'WE_LS_US_DATE');

			$user_preferences = array();

			foreach ($keys_to_save as $key) {
					$user_preferences[$key] = ws_ls_string_to_bool($_POST[$key]);
			}

			if ('stones_pounds' == $user_preferences['WE_LS_DATA_UNITS'] || 'pounds_only' == $user_preferences['WE_LS_DATA_UNITS']) {
				$user_preferences['WE_LS_IMPERIAL_WEIGHTS'] = true;
			} else {
				$user_preferences['WE_LS_IMPERIAL_WEIGHTS'] = false;
			}

			ws_ls_set_user_preferences($user_preferences);
			//var_dump($user_preferences);

	}

	$html_output .= '<form action="' .  get_permalink() . '" method="post">
  <input type="hidden" name="ws-ls-user-pref" value="true" />
	<input type="hidden" name="ws-ls-user-pref-redirect" value="' . get_the_ID() . '" />
  <label>' . __('Which unit would you like to record your weight in:', WE_LS_SLUG) . '</label>
  <br /><br />
    <select id="WE_LS_DATA_UNITS" name="WE_LS_DATA_UNITS"  tabindex="' . ws_ls_get_next_tab_index() . '">
      <option value="kg" ' . selected( ws_ls_get_config('WE_LS_DATA_UNITS'), 'kg', false ) . '>' . __('Kg', WE_LS_SLUG) . '</option>
      <option value="stones_pounds" ' . selected( ws_ls_get_config('WE_LS_DATA_UNITS'), 'stones_pounds', false ) . '>' . __('Stones & Pounds', WE_LS_SLUG) . '</option>
      <option value="pounds_only" ' . selected( ws_ls_get_config('WE_LS_DATA_UNITS'), 'pounds_only', false ) . '>' . __('Pounds', WE_LS_SLUG) . '</option>
    </select>
    <br /><br />
  <label>' . __('Display dates in the following formats:', WE_LS_SLUG) . '</label>
  <br /><br />
    <select id="WE_LS_US_DATE" name="WE_LS_US_DATE"  tabindex="' . ws_ls_get_next_tab_index() . '">
      <option value="false" ' . selected( ws_ls_get_config('WE_LS_US_DATE'), false, false ) . '>' . __('UK (DD/MM/YYYY)', WE_LS_SLUG) . '</option>
      <option value="true" ' . selected( ws_ls_get_config('WE_LS_US_DATE'), true, false ) . '>' . __('US (MM/DD/YYYY)', WE_LS_SLUG) . '</option>
    </select>
  <br /><br />
  <input name="submit_button" type="submit" id="we-ls-user-pref-submit"  tabindex="' . ws_ls_get_next_tab_index() . '" value="' .  __('Save Settings', WE_LS_SLUG) . '" class="comment-submit btn btn-default button default small fusion-button button-small button-default button-round button-flat">
</form>
  ';
	return $html_output;
}

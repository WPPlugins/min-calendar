<?php
/**
 * This is WordPress plugin Min Calendar
 *
 * @package MinCalendar
 */

/**
 * MC_Admin_Utility
 *
 * @package MinCalendar
 * @subpackage Admin
 */
class MC_Admin_Utility {
	/**
	 * Get action attribute value of calendar form
	 */
	public static function get_current_action() {
		if ( isset( $_REQUEST['action'] ) && - 1 != $_REQUEST['action'] ) {
			return $_REQUEST['action'];
		}
		return false;
	}

}

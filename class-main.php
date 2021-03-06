<?php
/**
 * MC_Main
 */
class MC_Main {
	function __construct() {
		require_once MC_PLUGIN_DIR . '/admin/class-mc-admin-controller.php';
		require_once MC_PLUGIN_DIR . '/admin/class-mc-admin-utility.php';
		require_once MC_PLUGIN_DIR . '/admin/class-mc-admin-action.php';
		require_once MC_PLUGIN_DIR . '/admin/class-mc-appearance.php';
		require_once MC_PLUGIN_DIR . '/admin/class-mc-custom-field.php';
		require_once MC_PLUGIN_DIR . '/admin/class-mc-list-table.php';
		require_once MC_PLUGIN_DIR . '/admin/class-mc-post-form.php';
		require_once MC_PLUGIN_DIR . '/admin/class-mc-validation.php';
		require_once MC_PLUGIN_DIR . '/includes/class-mc-capabilities.php';
		require_once MC_PLUGIN_DIR . '/includes/class-mc-controller.php';
		require_once MC_PLUGIN_DIR . '/includes/class-mc-date.php';
		require_once MC_PLUGIN_DIR . '/includes/class-mc-draw-calendar.php';
		require_once MC_PLUGIN_DIR . '/includes/class-mc-post-factory.php';
		require_once MC_PLUGIN_DIR . '/includes/class-mc-post.php';
		require_once MC_PLUGIN_DIR . '/includes/class-mc-utilities.php';
		// get_currentuserinfoはpluggable.phpで定義。自動では読み込まれない。
		require_once ABSPATH . WPINC . '/pluggable.php';
		// 管理ユーザーのみ実行
		global $user_level;
		wp_get_current_user();
		if ( 10 === (int) $user_level ) {
			new MC_Capabilities();
		}
		if ( is_admin() && 10 === (int) $user_level ) {
			new MC_Admin_Controller();
			add_action( 'admin_init', array( $this, 'upgrade' ) );
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'activate_' . MC_PLUGIN_BASENAME, array( &$this, 'activate' ) );
		} else {
			new MC_Controller();
		}
	}

	/**
	 * Initialize Min Calendar plugin
	 */
	public function init() {
		// L18N
		load_plugin_textdomain( 'mincalendar', false, 'min-calendar/languages' );
		// Custom Post Type
		$this->register_post_types();
	}

	/**
	 * Min Calendar用カスタム投稿タイプ登録
	 */
	private function register_post_types() {
		register_post_type(
			MC_Utilities::get_post_type(),
			array(
				'labels'    => array(
					'name'          => 'Min Calendar',
					'singular_name' => 'Min Calendar',
				),
				'rewrite'   => false,
				'query_var' => false,
			)
		);
	}

	/**
	 * Activate and default settings.
	 */
	public function activate() {
		$opt = get_option( ( 'mincalendar' ) );
		if ( $opt ) {
			return;
		}
		load_plugin_textdomain( 'mincalendar', false, 'min-calendar/languages' );
		$this->register_post_types();
		$this->upgrade();
	}

	/**
	 * Upgrading
	 *
	 * Current version of option update.
	 */
	public function upgrade() {
		$opt = get_option( 'mincalendar' );
		if ( ! is_array( $opt ) ) {
			$opt = array();
		}
		$old_ver = isset( $opt['version'] ) ? (string) $opt['version'] : '0';
		$new_ver = MC_VERSION;
		if ( $old_ver === $new_ver ) {
			return;
		}
		$opt['version'] = $new_ver;
		update_option( 'mincalendar', $opt );
	}
}

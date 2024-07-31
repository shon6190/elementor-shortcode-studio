<?php
namespace Elementor\Core\WidgetManager;

use Elementor\Core\Admin\Menu\Admin_Menu_Manager;
use Elementor\Plugin;
use Elementor\Settings;
use Elementor\Settings_Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Widget_Manager extends Settings_Page {

	const PAGE_ID = 'elementor-widget-manager';

	const WIDGET_MANAGER_OPTION_NAME = 'exclude_user_roles';

	/**
	 * @since 2.0.0
	 * @access public
	 */
	public function get_role_manager_options() {
		return get_option( 'elementor_' . self::WIDGET_MANAGER_OPTION_NAME, [] );
	}

	/**
	 * @since 2.0.0
	 * @access protected
	 */
	protected function get_page_title() {
		return esc_html__( 'Widget Manager', 'elementor' );
	}

	/**
	 * @since 2.0.0
	 * @access public
	 */
	public function register_admin_menu( Admin_Menu_Manager $admin_menu ) {
		$admin_menu->register( static::PAGE_ID, new Widget_Manager_Menu_Item( $this ) );
	}

	/**
	 * @since 2.0.0
	 * @access protected
	 */
	protected function create_tabs() {
		$validation_class = 'Elementor\Settings_Validations';
		return [
			'general' => [
				'label' => esc_html__( 'General', 'elementor' ),
				'sections' => [
					'tools' => [
						'fields' => [
							'exclude_user_roles' => [
								'label' => esc_html__( 'Exclude Roles', 'elementor' ),
								'field_args' => [
									'type' => 'checkbox_list_roles',
									'exclude' => [ 'super_admin', 'administrator' ],
								],
								'setting_args' => [
									'sanitize_callback' => [ $validation_class, 'checkbox_list' ],
								],
							],
						],
					],
				],
			],
		];
	}

	/**
	 * @since 2.0.0
	 * @access public
	 */
	public function display_settings_page() {
		?>
		Test 
		<?php
	}
 
	/**
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {
		parent::__construct();

		if ( ! Plugin::$instance->experiments->is_feature_active( 'admin_menu_rearrangement' ) ) {
			add_action( 'elementor/admin/menu/register', function ( Admin_Menu_Manager $admin_menu ) {
				$this->register_admin_menu( $admin_menu );
			}, Settings::ADMIN_MENU_PRIORITY + 10 );
		}

		// add_action( 'elementor/role/restrictions/controls', [ $this, 'get_go_pro_link_html' ] );
	}
}

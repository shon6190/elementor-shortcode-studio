<?php
namespace Elementor\Core\WidgetManager;

use Elementor\Core\Admin\Menu\Interfaces\Admin_Menu_Item_With_Page;
use Elementor\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Widget_Manager_Menu_Item implements Admin_Menu_Item_With_Page {

	private $widget_manager;

	public function __construct( Widget_Manager $widget_manager ) {
		$this->widget_manager = $widget_manager;
	}

	public function is_visible() {
		return true;
	}

	public function get_parent_slug() {
		return Settings::PAGE_ID;
	}

	public function get_label() {
		return esc_html__( 'Widget Manager', 'elementor' );
	}

	public function get_page_title() {
		return esc_html__( 'Widget Manager', 'elementor' );
	}

	public function get_capability() {
		return 'manage_options';
	}

	public function render() {
		$this->widget_manager->display_settings_page();
	}
}

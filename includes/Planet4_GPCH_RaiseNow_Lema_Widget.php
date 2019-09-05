<?php

namespace Greenpeace\Planet4GPCHRaiseNowLemaWidget;

if ( ! class_exists( 'Planet4_GPCH_RaiseNow_Lema_Widget' ) ) {
	class Planet4_GPCH_RaiseNow_Lema_Widget {

		/**
		 * Singleton instance
		 *
		 * @var Planet4_GPCH_RaiseNow_Lema_Widget
		 */
		private static $instance;

		/**
		 * Returns the instance
		 *
		 * @return Planet4_GPCH_RaiseNow_Lema_Widget
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {
			if ( is_admin() ) {
				$this->add_admin();
			}
		}

		public function hook_into_wordpress() {
			// @todo implement hooks here (if possible)
		}

		public function add_admin() {
			add_action( 'admin_menu', array( $this, 'add_menu' ) );

			// the following hooks are only registered on a contextual basis
			add_action( 'current_screen', array( $this, 'add_options' ) );
		}

		public function add_menu() {
			add_options_page(
				__( 'RaiseNow Lema Widget', P4_GPCH_RNLW_PREFIX ),
				__( 'RaiseNow Lema Widget', P4_GPCH_RNLW_PREFIX ),
				'manage_options',
				'raisenow_lema_widget_setup',
				[ $this, 'render_options_page' ]
			);
		}

		public function render_options_page() {
			require_once P4_GPCH_RNLW_BASE_PATH . '/templates/options.php';
		}

		public function add_options() {
			$context = get_current_screen();

			if ( $context->base && $context->base == 'settings_page_raisenow_lema_widget_setup' ) {
				print_r( '[$context->base]' );
				print_r( $context->base );

				// require_once
				// $options =
			}

			// register the options if we're on the corresponding option page
			/*
			if ( $context->base ) {
				if ( 'options' == $context->base || 'settings_page_' . RAISENOW_COMMUNITY_PREFIX . '_donation_settings' == $context->base ) {
					require_once( RAISENOW_COMMUNITY_PATH . '/includes/class-raisenow-community-options.php' );
					$options = new Raisenow_Community_Options();
					$options->init();

					add_action( 'admin_enqueue_scripts', array( &$options, 'add_code_editor' ) );
				}
			}
			*/
		}
	}
}

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
				__( 'RaiseNow Lema Widget Setup', P4_GPCH_RNLW_PREFIX ),
				__( 'RaiseNow Lema Widget Setup', P4_GPCH_RNLW_PREFIX ),
				'manage_options',
				P4_GPCH_RNLW_PREFIX . '_setup',
				[ $this, 'render_options_page' ]
			);
		}

		public function render_options_page() {
			require_once P4_GPCH_RNLW_BASE_PATH . '/templates/options.php';
		}

		public function add_options() {
			$context = get_current_screen();

			// print_r( '[$context->base]' );
			// print_r( $context->base );

			if ( $context->base && $context->base == 'settings_page_' . P4_GPCH_RNLW_PREFIX . '_setup' ) {

				// print_r( '[$context->base]' );
				// print_r( $context->base );

				register_setting( P4_GPCH_RNLW_PREFIX . '_setup', P4_GPCH_RNLW_PREFIX . '_general_options' );

				add_settings_section(
					P4_GPCH_RNLW_PREFIX . '_general_section',
					__( 'General Options', P4_GPCH_RNLW_PREFIX ),
					[ $this, 'general_options_section_header' ],
					P4_GPCH_RNLW_PREFIX . '_setup'
				);

				add_settings_field(
					P4_GPCH_RNLW_PREFIX . '_test_mode',
					__( 'Turn test mode on', P4_GPCH_RNLW_PREFIX ),
					[ $this, 'render_general_options_checkbox' ],
					P4_GPCH_RNLW_PREFIX . '_setup',
					P4_GPCH_RNLW_PREFIX . '_general_section',
					[
						'option_id' => 'test_mode',
						'helptext'  => "<p>" . __( 'Turn on test mode for the forms. No real transactions possible and debug information is printed to the page. Only use this setting if RaiseNow is also in test mode.', P4_GPCH_RNLW_PREFIX ) . "</p>",
					]
				);
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

		public function general_options_section_header() {
		}

		public function render_general_options_checkbox( $args ) {
			$options_id = P4_GPCH_RNLW_PREFIX . '_general_options';
			$options    = get_option( $options_id );

			if ( isset( $options[ $args['option_id'] ] ) ) {
				$input = $options[ $args['option_id'] ];
			} else {
				$input = 0;
			}

			echo $args['helptext'];

			echo "<input type='checkbox' name='{$options_id}[{$args['option_id']}]' id='$options_id-{$args['option_id']}' value='1'" . checked( $input, 1, false ) . ">";

		}
	}
}

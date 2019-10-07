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

				add_settings_field(
					P4_GPCH_RNLW_PREFIX . '_api_key',
					__( 'RaiseNow API key', P4_GPCH_RNLW_PREFIX, 'raisenow-community' ),
					[ &$this, 'render_api_key_option' ],
					P4_GPCH_RNLW_PREFIX . '_setup',
					P4_GPCH_RNLW_PREFIX . '_general_section',
					[
						'option_id' => 'api_key',
						'helptext'  => "<p>" . __( 'Your RaiseNow API key. Can be overridden in individual shortcodes if allowed (see setting below).', P4_GPCH_RNLW_PREFIX, 'raisenow-community' ) . "</p>",
					]
				);

				add_settings_field(
					P4_GPCH_RNLW_PREFIX . '_default_mode',
					__( 'Select the default mode of the form (recurring or one time donation)', P4_GPCH_RNLW_PREFIX, 'raisenow-community' ),
					[ &$this, 'render_default_mode_select' ],
					P4_GPCH_RNLW_PREFIX . '_setup',
					P4_GPCH_RNLW_PREFIX . '_general_section',
					[
						'option_id' => 'default_mode',
						'helptext'  => "",
					]
				);

				add_settings_field(
					P4_GPCH_RNLW_PREFIX . '_allow_shortcode_apikey',
					__( 'Allow the API key set above to be overridden in shortcodes', P4_GPCH_RNLW_PREFIX, 'raisenow-community' ),
					[ &$this, 'render_general_options_checkbox' ],
					P4_GPCH_RNLW_PREFIX . '_setup',
					P4_GPCH_RNLW_PREFIX . '_general_section',
					[
						'option_id' => 'allow_shortcode_apikey',
						'helptext'  => "<p>" . __( 'The API key for RaiseNow set above can be overridden in shortcodes if this setting is on. Check this box if your website uses multiple RaiseNow contracts (or for backwards compatibility).', P4_GPCH_RNLW_PREFIX, 'raisenow-community' ) . "</p>",
					]
				);

				add_settings_field(
					P4_GPCH_RNLW_PREFIX . '_default_minimum_onetime',
					__( 'Default minimum amount for one time donations', P4_GPCH_RNLW_PREFIX, 'raisenow-community' ),
					[ &$this, 'render_general_options_textinput' ],
					P4_GPCH_RNLW_PREFIX . '_setup',
					P4_GPCH_RNLW_PREFIX . '_general_section',
					[
						'option_id' => 'minimum_amount_onetime',
						'helptext'  => "<p>" . __( 'Minimum amount that can be donated in the forms for onetime donations. This is the default value that can be overridden in shortcodes. ', P4_GPCH_RNLW_PREFIX, 'raisenow-community' ) . "</p>",
					]
				);

				add_settings_field(
					P4_GPCH_RNLW_PREFIX . '_default_onetime_amounts',
					__( 'Default amounts for one time donations (comma separated list)', P4_GPCH_RNLW_PREFIX, 'raisenow-community' ),
					[ &$this, 'render_general_options_textinput' ],
					P4_GPCH_RNLW_PREFIX . '_setup',
					P4_GPCH_RNLW_PREFIX . '_general_section',
					[
						'option_id' => 'onetime_amounts',
						'helptext'  => "<p>" . __( 'Comma separated list of preset amounts for one time donations. This is the default value that can be overridden in shortcodes. ', P4_GPCH_RNLW_PREFIX, 'raisenow-community' ) . "</p>",
					]
				);

				add_settings_field(
					P4_GPCH_RNLW_PREFIX . '_default_default_amount',
					__( 'The preselected default amount in the form.', P4_GPCH_RNLW_PREFIX, 'raisenow-community' ),
					[ &$this, 'render_general_options_textinput' ],
					P4_GPCH_RNLW_PREFIX . '_setup',
					P4_GPCH_RNLW_PREFIX . '_general_section',
					[
						'option_id' => 'default_amount',
						'helptext'  => "<p>" . __( 'Of the above default amounts, enter one that will be preselected in the form. This is the default value that can be overridden in shortcodes. ', P4_GPCH_RNLW_PREFIX, 'raisenow-community' ) . "</p>",
					]
				);

				add_settings_field(
					P4_GPCH_RNLW_PREFIX . '_default_minimum_monthly',
					__( 'Default minimum amount for recurring donations (monthly)', P4_GPCH_RNLW_PREFIX, 'raisenow-community' ),
					[ &$this, 'render_general_options_textinput' ],
					P4_GPCH_RNLW_PREFIX . '_setup',
					P4_GPCH_RNLW_PREFIX . '_general_section',
					[
						'option_id' => 'minimum_amount_monthly',
						'helptext'  => "<p>" . __( 'Minimum amount that can be donated in the forms for monthly recurring donations. This is the default value that can be overridden in shortcodes. PLEASE NOTE: specify a monthly value, all other recurring periods will be calculated, for example yearly will be 12 times this value.', P4_GPCH_RNLW_PREFIX, 'raisenow-community' ) . "</p>",
					]
				);

				add_settings_field(
					P4_GPCH_RNLW_PREFIX . '_default_recurring_amounts',
					__( 'Default amounts for recurring donations (comma separated list)', P4_GPCH_RNLW_PREFIX, 'raisenow-community' ),
					[ &$this, 'render_general_options_textinput' ],
					P4_GPCH_RNLW_PREFIX . '_setup',
					P4_GPCH_RNLW_PREFIX . '_general_section',
					[
						'option_id' => 'recurring_amounts',
						'helptext'  => "<p>" . __( 'Comma separated list of preset amounts for recurring donations. This is the default value that can be overridden in shortcodes.', P4_GPCH_RNLW_PREFIX, 'raisenow-community' ) . "</p>",
					]
				);

				add_settings_field(
					P4_GPCH_RNLW_PREFIX . '_default_recurring_interval',
					__( 'Default recurring interval', P4_GPCH_RNLW_PREFIX, 'raisenow-community' ),
					[ &$this, 'render_general_options_textinput' ],
					P4_GPCH_RNLW_PREFIX . '_setup',
					P4_GPCH_RNLW_PREFIX . '_general_section',
					[
						'option_id' => 'default_recurring_interval',
						'helptext'  => "<p>" . __( 'What interval should be preselected when users select recurring donations? Valid values are: weekly, monthly, quarterly, semestral, yearly.', P4_GPCH_RNLW_PREFIX, 'raisenow-community' ) . "</p>",
					]
				);
			}
			else
			{
				print_r( 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' );
			}

			// register the options if we're on the corresponding option page
			/*
			if ( $context->base ) {
				if ( 'options' == $context->base || 'settings_page_' . P4_GPCH_RNLW_PREFIX . '_donation_settings' == $context->base ) {
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

		public function render_api_key_option( $args ) {
			$options_id = P4_GPCH_RNLW_PREFIX . '_general_options';
			$options    = get_option( $options_id );

			if ( isset( $options[ $args['option_id'] ] ) ) {
				$input = $options[ $args['option_id'] ];
			} else {
				$input = '';
			}

			echo $args['helptext'];
			echo "<input type='text' name='{$options_id}[{$args['option_id']}]' id='$options_id-{$args['option_id']}' value='{$input}'>";
		}

		public function render_general_options_textinput( $args ) {
			$options_id = P4_GPCH_RNLW_PREFIX . '_general_options';
			$options    = get_option( $options_id );

			if ( isset( $options[ $args['option_id'] ] ) ) {
				$input = $options[ $args['option_id'] ];
			} else {
				$input = '';
			}

			echo $args['helptext'];
			echo "<input type='text' name='{$options_id}[{$args['option_id']}]' id='$options_id-{$args['option_id']}' value='{$input}'>";
		}

		public function render_default_mode_select( $args ) {
			$options_id = P4_GPCH_RNLW_PREFIX . '_general_options';
			$options    = get_option( $options_id );

			$supportedOrganisations = array(
				'recurring' => 'Recurring Donations',
				'onetime' => 'One time donations',
			);

			$output = "<select name='{$options_id}[{$args['option_id']}]'>";

			foreach ($supportedOrganisations as $key => $name) {
				if ($key == $options[ $args['option_id'] ]) {
					$output .= '<option value="' . $key . '" selected="selected">' . $name . '</option>';
				}
				else {
					$output .= '<option value="' . $key . '">' . $name . '</option>';
				}
			}

			$output .= '</select>';

			echo $output;
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

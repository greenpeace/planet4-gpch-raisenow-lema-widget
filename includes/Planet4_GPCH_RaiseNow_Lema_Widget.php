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
		}
	}
}

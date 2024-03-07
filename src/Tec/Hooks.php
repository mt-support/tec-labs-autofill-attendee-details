<?php
/**
 * Handles hooking all the actions and filters used by the module.
 *
 * To remove a filter:
 * ```php
 *  remove_filter( 'some_filter', [ tribe( Tribe\Extensions\AutofillAttendeeDetails\Hooks::class ), 'some_filtering_method' ] );
 *  remove_filter( 'some_filter', [ tribe( 'extension.autofill_attendee_details.hooks' ), 'some_filtering_method' ] );
 * ```
 *
 * To remove an action:
 * ```php
 *  remove_action( 'some_action', [ tribe( Tribe\Extensions\AutofillAttendeeDetails\Hooks::class ), 'some_method' ] );
 *  remove_action( 'some_action', [ tribe( 'extension.autofill_attendee_details.hooks' ), 'some_method' ] );
 * ```
 *
 * @since   1.0.0
 *
 * @package Tribe\Extensions\AutofillAttendeeDetails;
 */

namespace Tribe\Extensions\AutofillAttendeeDetails;

use TEC\Common\lucatume\DI52\Container;
use Tribe__Main as Common;

use TEC\Common\Contracts\Service_Provider;

/**
 * Class Hooks.
 *
 * @since   1.0.0
 *
 * @package Tribe\Extensions\AutofillAttendeeDetails;
 */
class Hooks extends Service_Provider {

	const EVENT_POST_TYPE = \Tribe__Events__Main::POSTTYPE;

	/**
	 * Binds and sets up implementations.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$this->container->singleton( static::class, $this );
		$this->container->singleton( 'extension.autofill_attendee_details.hooks', $this );

		$this->add_actions();
		$this->add_filters();
	}

	/**
	 * Adds the actions required by the plugin.
	 *
	 * @since 1.0.0
	 */
	protected function add_actions() {
		add_action( 'tribe_load_text_domains', [ $this, 'load_text_domains' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'load_assets' ] );
	}

	/**
	 * Adds the filters required by the plugin.
	 *
	 * @since 1.0.0
	 */
	protected function add_filters() {

	}

	/**
	 * Load text domain for localization of the plugin.
	 *
	 * @since 1.0.0
	 */
	public function load_text_domains() {
		$mopath = tribe( Plugin::class )->plugin_dir . 'lang/';
		$domain = 'tec-labs-autofill-attendee-details';

		// This will load `wp-content/languages/plugins` files first.
		Common::instance()->load_text_domain( $domain, $mopath );
	}

	public function load_assets() {
		if ( $this->should_load_assets() ) {
			$this->container->make( Assets::class )->load_assets();
			$user_details = $this->wp_get_user_details();
			if ( $user_details ) {
				$user_details['modal'] = $this->is_ar_modal_enabled();
				wp_localize_script( 'autofill_attendee_details', 'current_user_data', $user_details );
			}
		}
	}

	private function should_load_assets() {
		return ( $this->is_ar_modal_enabled() && is_singular( self::EVENT_POST_TYPE ) ) || tribe( 'tickets.attendee_registration' )->is_on_page();
	}

	private function is_ar_modal_enabled() {
		$ar_class = tribe( \Tribe__Tickets__Attendee_Registration__Main::class );

		return $ar_class->is_modal_enabled();
	}

	private function wp_get_user_details() {
		if ( ! is_user_logged_in() ) {
			return false;
		}

		$current_user = wp_get_current_user();

		return [
			'name'  => esc_html( $current_user->display_name ),
			'email' => esc_html( $current_user->user_email ),
		];
	}
}

<?php
/**
 * Handles registering all Assets for the Plugin.
 *
 * To remove an Asset you can use the global assets handler:
 *
 * ```php
 *  tribe( 'assets' )->remove( 'asset-name' );
 * ```
 *
 * @since   1.0.0
 *
 * @package Tribe\Extensions\AutofillAttendeeDetails
 */

namespace Tribe\Extensions\AutofillAttendeeDetails;

use TEC\Common\Contracts\Service_Provider;

/**
 * Register Assets.
 *
 * @since   1.0.0
 *
 * @package Tribe\Extensions\AutofillAttendeeDetails
 */
class Assets extends Service_Provider {
	/**
	 * Binds and sets up implementations.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$this->container->singleton( static::class, $this );
		$this->container->singleton( 'extension.autofill_attendee_details.assets', $this );

		$plugin = tribe( Plugin::class );
	}

	public function load_assets() {
		wp_enqueue_script(
			'autofill_attendee_details',
			plugins_url( 'js/autofill-attendee-details.js', dirname( __FILE__ ) ),
			[],
			Plugin::VERSION
		);
	}
}

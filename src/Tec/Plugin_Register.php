<?php
/**
 * Handles the Extension plugin dependency manifest registration.
 *
 * @since   1.0.0
 *
 * @package Tribe\Extensions\AutofillAttendeeDetails
 */

namespace Tribe\Extensions\AutofillAttendeeDetails;

use Tribe__Abstract_Plugin_Register as Abstract_Plugin_Register;

/**
 * Class Plugin_Register.
 *
 * @since   1.0.0
 *
 * @package Tribe\Extensions\AutofillAttendeeDetails
 *
 * @see     Tribe__Abstract_Plugin_Register For the plugin dependency manifest registration.
 */
class Plugin_Register extends Abstract_Plugin_Register {
	protected $base_dir = Plugin::FILE;
	protected $version = Plugin::VERSION;
	protected $main_class = Plugin::class;
	protected $dependencies = [
		'parent-dependencies' => [
			'Tribe__Events__Main'  => '6.1.2-dev',
			'Tribe__Tickets__Main' => '5.6.1-dev'
		],
		'co-dependencies'     => [
			'Tribe__Tickets_Plus__Main' => '5.7.1-dev'
		],
	];
}

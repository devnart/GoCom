<?php
/**
 * Singleton pattern class.
 *
 * @package xts
 */

namespace XTS;

/**
 * Singleton pattern class.
 *
 * @since 1.0.0
 */
class Singleton {

	/**
	 * Instance of this static object.
	 *
	 * @var array
	 */
	private static $instances = [];

	/**
	 * Get singleton instance.
	 *
	 * @since 1.0.0
	 *
	 * @return object Current object instance.
	 */
	public static function get_instance() {
		$subclass = static::class;
		if ( ! isset( self::$instances[ $subclass ] ) ) {
			self::$instances[ $subclass ] = new static();
			self::$instances[ $subclass ]->init();
		}
		return self::$instances[ $subclass ];
	}

	/**
	 * Prevent singleton class clone.
	 *
	 * @since 1.0.0
	 */
	private function __clone() {}

	/**
	 * Prevent singleton class initialization.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {}

}

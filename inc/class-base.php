<?php

namespace WordPress_Objects;

abstract class Base {

	/**
	 * Get an object
	 *
	 * @param  int $id
	 * @return Base|null if not exists
	 */
	public static function get( $id ) {
		$class = get_called_class();
		return new $class( $id );
	}

	public static function get_many( $args ) {
		$class = get_called_class();
		if ( is_array_numerics( $args ) ) {
			return array_map( $args, array( $class, 'get' ) );
		} else {
			return array_map( array( $class, 'get' ), $class::get_many_ids( $args ) );
		}
	}

	public static function get_many_iterator( $args ) {
		if ( is_array_numerics( $args ) ) {
			return new Object_Iterator( $args, get_called_class() );
		} else {
			return new Object_Iterator( static::get_many_ids( $args ), get_called_class() );
		}
		return new Object_Iterator( static::get_many_ids( $args ), get_called_class() );
	}

	protected static function get_many_ids( $args ) {}

	protected static function get_one_id( $args ) {}
}

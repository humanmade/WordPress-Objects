<?php

class Term {

	public $_term;

	/**
	 * This is used to subclasses get set it, so setters and getters don;t need to be overriden to set the taxonomy arg
	 * @var null
	 */
	protected static $taxonomy = null;

	public static function get_by_slug( $slug, $taxonomy = null ) {

		$term = get_term_by( 'slug', $slug, $taxonomy ? $taxonomy : static::$taxonomy );

		$class = get_called_class();

		return new $class( $term->term_id, $taxonomy ? $taxonomy : static::$taxonomy );
	}

	public function __construct( $term_id, $taxonomy = null ) {
		
		$taxonomy = $taxonomy ? $taxonomy : static::$taxonomy;
		
		if ( empty( $term_id ) || ! $taxonomy )
			throw new Exception( '$term_id or $taxonomy empty' );

		$this->_term = get_term( (int) $term_id, $taxonomy );

		if ( ! $this->_term )
			throw new Exception( 'Term not found' );
	}

	public function __get( $name ) {

		if ( in_array( $name, array( 'term_id', 'name', 'slug', 'taxonomy' ) ) )
			throw new Exception( 'Trying to access wp_term object properties from Term object' );
	}

	/**
	 * Check is this term was used in the global $wp_query 
	 * 
	 * @return bool
	 */
	public function is_queried_object() {

		if ( function_exists( 'hm_is_queried_object' ) )
			return hm_is_queried_object( $this->_term );

	}

	public function get_id() {
		return $this->_term->term_id;
	}

	public function get_parent() {

		if ( $this->_term->parent )
			return new Term( $this->_term->parent, $this->get_taxonomy() );

		return null;
	}

	/**
	 * Get the taxonomy this term is in
	 * 
	 * @return string
	 */
	public function get_taxonomy() {

		return $this->_term->taxonomy;
	}

	/**
	 * Get meta date for this term
	 * 
	 * @param  string  $key
	 * @param  boolean $single default false
	 * @return array|mixed
	 */
	public function get_meta( $key, $single = false ) {
		return get_term_meta( $this->get_id(), $key, $single );
	}

	/**
	 * Update meta data for this term
	 * 
	 * @param  string $key
	 * @param  mixed $value
	 */
	public function update_meta( $key, $value ) {
		return update_term_meta( $this->get_id(), $key, $value );
	}

	/**
	 * Add meta data for this term
	 * 
	 * @param  string $key
	 * @param  mixed $value
	 */
	public function add_meta( $key, $value ) {
		return add_term_meta( $this->get_id(), $key, $value );
	}

	/**
	 * Delete meta data for this term
	 * 
	 * @param  string $key
	 * @param  mixed $value
	 */
	public function delete_meta( $key, $value = null ) {
		return delete_term_meta( $this->get_id(), $key, $value );
	}

	/**
	 * Get the slug for this term
	 * 
	 * @return string
	 */
	public function get_slug() {
		return $this->_term->slug;
	}

	/**
	 * Get the name for this term
	 * 
	 * @return string
	 */
	public function get_name() {
		return $this->_term->name;
	}

	/**
	 * Get the WordPressnative term object
	 * 
	 * @return StdClass
	 */
	public function get_term() {
		return $this->_term;
	}

	/**
	 * Get the term_taxonomy_id for this term
	 * 
	 * @return int
	 */
	public function get_term_taxonomy_id() {
		return $this->_term->term_taxonomy_id;
	}
}
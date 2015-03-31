<?php

class Taxonomy {

	protected $_tax;

	public function __construct( $taxonomy ) {
		$this->_tax = $taxonomy;
	}

	public static function get( $taxonomy ) {

		$taxonomy = get_taxonomy( $taxonomy );

		if ( ! $taxonomy ) {
			return new WP_Error( 'invalid_taxonomy', __( 'Invalid Taxonomy' ) );
		}

		$class = get_called_class();
		return new $class( $taxonomy );

	}

	public function get_name() {
		return $this->_tax->name;
	}

	public function get_label_plural() {
		return $this->_tax->labels->name;
	}

	public function get_label_singular() {
		return $this->_tax->labels->singular_name;
	}

	public function get_terms( $args = array() ) {
		return array_map( function( $term ) {
			return new Term( $term->term_id, $term->taxonomy );
		}, (array) get_terms( $this->get_name(), $args ) );
	}

}

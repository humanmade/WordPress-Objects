<?php

class Taxonomy {

	static $taxonomy;

	protected $_tax;

	public function __construct( $taxonomy = null ) {

		$taxonomy = $taxonomy ? $taxonomy : static::$taxonomy;

		if ( ! $taxonomy ) {
			throw new Exception( '$taxonomy empty' );
		}

		$this->_tax = get_taxonomy( $taxonomy );

		if ( ! $this->_tax ) {
			throw new Exception( 'Taxonomy not found' );
		}

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

<?php

namespace WordPress_Objects;

class Object_Iterator implements \SeekableIterator, \Countable, \ArrayAccess {

	protected $ids = array();
	protected $position = 0;
	protected $class = null;

	public function __construct( $ids, $class ) {
		$this->ids = $ids;
		$this->class = $class;
	}

	// Iterator methods
	public function rewind() {
		$this->position = 0;
	}

	public function current() {
		$class = $this->class;
		return $class::get( $this->ids[ $this->position ] );
	}

	public function key() {
		return $this->position;
	}

	public function next() {
		++$this->position;
	}

	public function valid() {
		 return isset( $this->ids[ $this->position ] );
	}

	public function count() {
		return count( $this->ids );
	}

	// SeekableIterator methods
	public function seek( $position ) {
		$this->position = $position;
	}

	// ArrayAccess methods
	public function offsetSet( $offset, $object ) {
		if ( is_null( $offset ) ) {
			$this->ids[] = $object->get_id();
		} else {
			$this->ids[ $offset ] = $object->get_id();
		}
	}

	public function offsetExists( $offset ) {
		return isset( $this->ids[ $offset ] );
	}

	public function offsetUnset( $offset ) {
		unset( $this->ids[ $offset ] );
	}

	public function offsetGet( $offset ) {
		return $class::get( $this->ids[ $offset ] );
	}
}

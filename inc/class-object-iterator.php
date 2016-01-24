<?php

namespace WordPress_Objects;

class Object_Iterator implements \Iterator {

	protected $ids = array();
	protected $position = 0;
	protected $class = null;

	public function __construct( $ids, $class ) {
		$this->ids = $ids;
		$this->class = $class;
	}

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
		 return isset($this->ids[ $this->position ] );
	}
}

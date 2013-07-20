<?php

namespace LinearAlgebra;

class Vector implements \ArrayAccess, \Countable, \Iterator {

	/**
	 * @var array
	 */
	protected $values;

	/**
	 * Number of rows
	 * @var int
	 */
	protected $m;

	/**
	 * Number current of row
	 * @var int
	 */
	protected $i;


	public function __construct($values = array()) {
		$this->m = count($values);
		$this->i = 0;
		$this->values = &$values;
	}

	/**
	 *
	 * @param int $size
	 * @return Vector
	 */
	public static function zeros($size) {
		return self::fill(0, $size);
	}

	/**
	 *
	 * @param int $size
	 * @return Vector
	 */
	public static function ones($size) {
		return self::fill(1, $size);
	}

	/**
	 *
	 * @return array
	 */
	public function values() {
		return $this->values;
	}

	/**
	 *
	 * @param int $value
	 * @param int $size
	 * @return Vector
	 */
	public static function fill($value, $size) {
		return new static(array_fill(0, $size, $value));
	}

    /**
     *
     * @param array $values
     * @return Vector
     */
    public static function create($values = array()) {
        $values = $values instanceof Vector ? $values->values() : $values;
        if (!is_array($values)) {
            $values = func_get_args();
        }
        return new static($values);
    }

	public function toString($format = '%6.2f', $headerFormat = '%6d', $columnSeparator = " ", $lineSeparator = PHP_EOL) {
		return implode(
            $columnSeparator,
            array_map(
                function ($value) use ($headerFormat) {return sprintf($headerFormat, $value);},
                array_keys($this->values)
            )
        ) .
        $lineSeparator .
		implode(
            $columnSeparator,
            array_map(
                function ($value) use ($format) {return sprintf($format, $value);},
                $this->values)
        ) .
        $lineSeparator;
	}

	public function __toString() {
		return $this->toString();
	}

	public function offsetExists($offset) {
		return $offset >= 0 && $offset < $this->m;
	}

	public function offsetGet($offset) {
		if (!$this->offsetExists($offset)) {
			throw new \InvalidArgumentException();
		}
		return $this->values[$offset];
	}

	public function offsetSet($offset, $value) {
		if (!$this->offsetExists($offset)) {
			throw new \InvalidArgumentException();
		}

		$this->values[$offset] = $value;
	}

	public function offsetUnset($offset) {
		throw new \BadMethodCallException();
	}


	/** Implements Countable */
	public function count() {
		return $this->m;
	}

	public function current() {
		return $this->values[$this->i];
	}

	public function key() {
		return $this->i;
	}

	public function next() {
		$this->i++;
	}

	public function rewind() {
		$this->i = 0;
	}

	public function valid() {
		return $this->i < $this->m;
	}

	public function normalized() {
		return $this->scale(1/$this->norm());
	}

	public function cosine(Vector $b) {
		return $this->dot($b) / ($this->norm() * $b->norm());
	}

	public function norm() {
		return sqrt($this->dot($this));
	}

	public function dot(Vector $b) {
		return array_sum($this->coMap($b, function ($a,$b) {return $a * $b;}));
	}

	public function scale($f) {
		return new Vector($this->map(function ($e) use ($f) { return $e * $f;}));
	}

	public function add(Vector $b) {
		return new Vector($this->coMap($b, function ($a,$b) {return $a + $b;}));
	}

	public function subtract(Vector $b) {
		return new Vector($this->coMap($b, function ($a,$b) {return $a - $b;}));
	}

	public function invert() {
		return $this->scale(-1);
	}



	public function map($function) {
		$result = array();
		for ($i=0; $i < $this->m; $i++) {
			$result[] = $function($this->values[$i], $i);
		}
		return $result;
	}


	public function coMap(Vector $b, $function) {
		$result = array();
		for ($i=0; $i < $this->m; $i++) {
			$result[] = $function($this->values[$i], $b->values[$i],$i);
		}
		return $result;
	}


}

?>

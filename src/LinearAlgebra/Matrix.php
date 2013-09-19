<?php

namespace LinearAlgebra;


class Matrix implements \ArrayAccess, \Countable, \Iterator, \JsonSerializable {

	/**
	 * @var array
	 */
	protected $values;

	/**
	 * Number of row
	 * @var int
	 */
	protected $m;

	/**
	 * Number current of row
	 * @var int
	 */
	protected $i;


	/**
	 * Number of columns
	 * @var int
	 */
	protected $n;

    /**
     * @param array $values
     * @param bool $forceNumeric
     * @throws \InvalidArgumentException
     */
    public function __construct($values = array(), $forceNumeric = false) {
		$this->values = array();
		$this->m = count($values);

		if ($this->m > 0) {
            if (!is_array(current($values))) {
                throw new \InvalidArgumentException('2 dimensional array expected');
            }
			$this->n = count(current($values));
		}

		for ($i = 0; $i < $this->m; $i++) {
			if ($this->n != count($values[$i])) {
				throw new \InvalidArgumentException('array must have the same number of columns in each row');
			}
			for ($j = 0; $j < $this->n; $j++) {
				$this->values[$i][$j] = $forceNumeric ? (1*$values[$i][$j]) : $values[$i][$j];
			}

		}
		$this->i = -1;

		#$this->values = $values;
	}

	/**
	 *
	 * @param Vector|array $v
	 * @return Matrix
	 */
	public static function eye($v) {
        $v = Vector::create($v);
		$a = array();
		$dim = count($v);
		for ($t = 0; $t < $dim; $t++) {
			$a[$t] = array_fill(0, $dim, 0);
			$a[$t][$t] = $v[$t];
		}
		return static::create($a);
	}


    /**
     *
     * @param int $m
     * @param int $n
     * @return Matrix
     */
    public static function zero($m, $n) {
        return static::constant($m, $n, 0);
    }

    /**
     *
     * @param int $m
     * @param int $n
     * @param float|int $constant
     * @return Matrix
     */
    public static function constant($m, $n, $constant = 0.0) {
        $a = array();
        for ($t = 0; $t < $m; $t++) {
            $a[$t] = array_fill(0, $n, $constant);
        }
        return static::create($a);
    }



	/**
	 *
	 * @param int $dim
	 * @return Matrix
	 */
	public static function identity($dim) {
		return static::eye(array_fill(0, $dim, 1));
	}

    /**
     * @param array $values
     * @param bool $forceNumeric
     * @return Matrix
     */
    public static function create($values = array(), $forceNumeric = false) {
        return new static($values, $forceNumeric);

    }

	/**
	 *
	 * @return Matrix
	 */
	public function transpose() {
		$transposedValues = array();
		for ($i = 0; $i < $this->m; $i++) {
			for ($j = 0; $j < $this->n; $j++) {
				$transposedValues[$j][$i] = $this->values[$i][$j];
			}
		}
		return static::create($transposedValues);
	}

	/**
	 *
	 * @param int $i
	 * @return Vector
	 * @throws \OutOfBoundsException
	 */
	public function row($i) {
		if ($i >= $this->m || $i<0) {
			throw new \OutOfBoundsException('Row '.$i.' does not exist');
		}
		return Vector::create($this->values[$i]);
	}


    /**
     * @param array $map
     * @return Matrix
     */
    public function permute($map) {
        $values = array();
        foreach ($map as $newIndex => $oldIndex) {
            $values[$newIndex] = $this->values[$oldIndex];
        }

        return new static($values);
    }

	/**
	 *
	 * @param int $j
	 * @return Vector
	 * @throws \OutOfBoundsException
	 */
	public function column($j) {
		if ($j >= $this->n || $j<0) {
			throw new \OutOfBoundsException('Column '.$j.' does not exist');
		}

		$v = array();
		for($i = 0; $i < $this->m; $i++) {
			$v[$i] = $this->values[$i][$j];
		}

		return Vector::create($v);
	}

    /**
     *
     * @param int $i
     * @param int $j
     * @throws \OutOfBoundsException*
     * @return float
     */
    public function index($i,$j) {
        if ($i >= $this->m || $i<0) {
            throw new \OutOfBoundsException('Row '.$i.' does not exist');
        }
        if ($j >= $this->n || $j<0) {
            throw new \OutOfBoundsException('Column '.$j.' does not exist');
        }
        return $this->values[$i][$j];
    }

    /**
     * @param int $i
     * @return float
     */
    public function diagonal($i) {
        return $this->index($i, $i);
    }

    protected static function toFormattingCallback($format) {
        if (is_null($format)) {
            return function ($value) {return '';};
        } else if (is_object($format) && $format instanceof \ArrayAccess) {

        } else if (is_callable($format)) {
            return $format;
        } else if (is_string($format)) {
            return function ($value) use ($format) {return sprintf($format, $value);};
        }

    }

    /**
     *
     * @param string $format
     * @param string $columnHeaderFormat
     * @param string $rowHeaderFormat
     * @param string $columnSeparator
     * @param string $rowSeparator
     * @internal param string $headerFormat
     * @return string
     */
	public function toString($format = '%6.2f', $columnHeaderFormat = '%6d', $rowHeaderFormat = '%6d', $columnSeparator = " ", $rowSeparator = PHP_EOL) {
        $format = static::toFormattingCallback($format);
        $columnHeaderFormat = static::toFormattingCallback($columnHeaderFormat);
        $rowHeaderFormat = static::toFormattingCallback($rowHeaderFormat);

        $firstLine = current($this->values());

		$csv =
            strtr($rowHeaderFormat(0),'0',' ') .
            $columnSeparator .
            implode(
                $columnSeparator,
                array_map($columnHeaderFormat, array_keys($firstLine))
            ) .
            $rowSeparator . $rowSeparator;

		foreach ($this->values as $k => $row) {
			$csv .=
                $rowHeaderFormat($k) .
                $columnSeparator .
                implode(
                    $columnSeparator,
                    array_map(
                        $format,
                        $row
                    )
                ) .
                $rowSeparator;
		}
		return $csv;
	}

	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->toString();
	}

	/**
	 *
	 * @param int $m
	 * @return Matrix
	 */
	public function squared($m = null) {
		$m = $m ?: $this->m;
		return $this->resized($m, $m);
	}


	/**
	 *
	 * @param int $m
	 * @param int $n
	 * @return Matrix
	 */
	public function resized($m = null, $n = null) {
		$m = $m ?: $this->m;
		$n = $n ?: $this->n;
		$v = array();
		for ($i = 0; $i < $m; $i++) {
			for ($j = 0; $j < $n; $j++) {
				$v[$i][$j] = $this->values[$i][$j];
			}
		}
		return static::create($v);
	}

	public function offsetExists($offset) {
		return $offset >= 0 && $offset < $this->m;
	}

	public function offsetGet($offset) {
		if (!$this->offsetExists($offset)) {
			throw new \InvalidArgumentException();
		}
		return Vector::create($this->values[$offset]);
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
		return Vector::create($this->values[$this->i]);
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

	/**
	 *
	 * @return array
	 */
	public function values() {
		return $this->values;
	}

	/**
	 * Number of rows
	 * @return int
	 */
	public function m() {
		return $this->m;
	}

	/**
	 * Number of columns
	 * @return int
	 */
	public function n() {
		return $this->n;
	}

	/**
	 * Returns the minimal dimension
	 * @return int
	 */
	public function short() {
		return min($this->m,$this->n);
	}

	/**
	 * Returns the maximal dimension
	 * @return int
	 */
	public function long() {
		return max($this->m,$this->n);
	}


	/**
	 *
	 * @return Vector
	 */
	public function getEye() {
		$v = array();
		for ($t = 0; $t < $this->short(); $t++) {
			$v[$t] = $this->values[$t][$t];
		}
		return Vector::create($v);
	}

	/**
	 *
	 * @param float $eps
	 * @param int $maxIterations
	 * @return ThreeMatrixFactorisation
	 */
	public function svd($eps = 0.0001, $maxIterations = 75) {
		list ($u,$s,$v) = SVD::decompose($this->values, $this->m, $this->n, $eps, $maxIterations);
        return new ThreeMatrixFactorisation(
            Matrix::create($u),
            Matrix::eye($s),
            Matrix::create($v)->transpose()
        );
	}


    /**
     *
     * @param Matrix $b
     * @return Matrix
     */
	public function add(Matrix $b) {
		return $this->coMap($b, function ($a, $b) {return $a+$b;});
	}

    /**
     *
     * @param Matrix $b
     * @return Matrix
     */
	public function subtract(Matrix $b) {
		return $this->coMap($b, function ($a, $b) {return $a-$b;});
	}

	/**
	 *
	 * @return Matrix
	 */
	public function negate() {
		return $this->scalar(-1);
	}

    /**
     *
     * @param float $f
     * @return Matrix
     */
    public function scalar($f) {
        return $this->map(function ($a) use($f){ return $f*$a;});
    }


	/**
	 *
	 * @return Matrix
	 */
	public function round() {
		return $this->map(function ($e) {return round($e);});
	}

	/**
	 *
	 * @param Callable $function
	 * @return Matrix
	 */
	public function map($function) {
		$result = array();
		for ($i=0; $i < $this->m; $i++) {
			for ($j=0; $j < $this->n; $j++) {
				$result[$i][$j] = $function($this->values[$i][$j], $i, $j);
			}
		}
		return static::create($result);
	}


    /**
     *
     * @param Callable $function
     * @param mixed $result
     * @return number
     */
    public function reduce($function, $result = 0) {
        for ($i=0; $i < $this->m; $i++) {
            for ($j=0; $j < $this->n; $j++) {
                $result = $function($result,$this->values[$i][$j], $i, $j);
            }
        }
        return $result;
    }


    /**
     *
     * @param array|callable $filter
     * @return Matrix
     */
    public function filterRows($filter) {
        $filter = is_callable($filter) ? $filter : function ($i) use($filter) {return isset($filter[$i]);};
        $result = array();
        foreach ($this as $i=>$row) {
            /** @var $row Vector */
            if ($filter($i, $row)) {
                $result[] = $row->values();
            }
        }
        return static::create($result);
    }


    /**
     * @param array|callable $filter
     * @return Matrix
     */
    public function filterColumns($filter) {
        $filter = is_callable($filter) ? $filter : function ($i) use($filter) {return isset($filter[$i]);};
        $result = array();
        for ($i = 0; $i < $this->n; $i++) {
            $column = $this->column($i);
            if ($filter($i, $column)) {
                for ($j = 0; $j < count($column);$j++) {
                    $result[$j][] = $column[$j];
                }
            }
        }
        return static::create($result);
    }


    /**
     *
     * @return string
     */
    public function dim() {
		return $this->m.'x'.$this->n;
	}

    /**
     *
     * @param Matrix $b
     * @param Callable $function
     * @throws \InvalidArgumentException
     * @return Matrix
     */
	public function coMap(Matrix $b, $function) {
		if ($this->m != $b->m || $this->n != $b->n) {
			throw new \InvalidArgumentException('Dimensions don\'t match, this is '.$this->dim().', b is '.$b->dim());
		}
		$result = array();
		for ($i=0; $i < $this->m; $i++) {
			for ($j=0; $j < $this->n; $j++) {
				$result[$i][$j] = $function($this->values[$i][$j], $b->values[$i][$j], $i, $j);
			}
		}
		return static::create($result);
	}


    /**
     * @param Matrix $b
     * @return Matrix
     */
    public function multiply(Matrix $b) {
        return $this->coWalk($b, function(Vector $aRow, Vector $bColumn) {return $aRow->dot($bColumn);});
    }

    /**
     * @param Matrix $b
     * @return Matrix
     */
    public function cosine(Matrix $b) {
        return $this->coWalk($b, function(Vector $aRow, Vector $bColumn) {return $aRow->cosine($bColumn);});
    }



    /**
     *
     *
     * @param Matrix $b
     * @param callback $function
     * @throws \InvalidArgumentException
     * @return Matrix
     */
	public function coWalk(Matrix $b, $function) {
		if ($this->n != $b->m) {
			throw new \InvalidArgumentException('Matrix b incompatible: '.$this->n .' != ' .$b->m);
		}

        $c = array();
		for ($i=0; $i < $this->m; $i++) {
            $c[$i] = array();
			for ($j = 0; $j < $b->n; $j++) {
                $c[$i][$j] = $function($this->row($i), $b->column($j));
			}
		}

		return static::create($c);
	}

    /**
     * @param int|float $p
     * @return number
     */
    public function pNorm($p = 2) {
        return pow(
            $this->reduce(
                function($r, $a) use ($p) {
                    return $r + pow($a, $p);
                }
            ),
            1.0/$p
        );
    }

    /**
     * @return number
     */
    public function norm() {
        return $this->pNorm(2);
    }

    /**
     * @return number
     */
    public function max() {
        return $this->reduce(function ($r, $a) {return $a > $r ? $a : $r;}, -INF);
    }


    /**
     * @return number
     */
    public function min() {
        return $this->reduce(function ($r, $a) {return $a < $r ? $a : $r;}, INF);
    }

    public function toObject() {
        return $this->values;
    }



    public function toArray() {
        return $this->values;
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return $this->toObject();
    }
}

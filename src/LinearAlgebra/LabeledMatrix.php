<?php

namespace LinearAlgebra;

use LinearAlgebra\Matrix;
use LinearAlgebra\Vector;
use TermDocumentTools\Collection;

class LabeledMatrix implements \JsonSerializable {
	/**
	 * @var Collection
	 */
	protected $columnLabels;

	/**
	 * @var Collection
	 */
	protected $rowLabels;

	/**
	 * @var Matrix
	 */
	protected $values;

	/**
	 *
	 * @param Collection $columnLabels
	 * @param Collection $rowLabels
	 * @param Matrix $values
	 * @throws \InvalidArgumentException
	 */
	public function __construct(Collection $columnLabels, Collection $rowLabels, Matrix $values) {
		if (count($rowLabels) != count($values)) {
			throw new \InvalidArgumentException('$values must have # of rowLabels entries in the first dimension');
		}

		foreach($values as $column) {
			if (count($columnLabels) != count($column)) {
				throw new \InvalidArgumentException('$values must have # of columnLabels entries in the second dimension');
			}
		}

		$this->columnLabels = $columnLabels;
		$this->rowLabels = $rowLabels;
		$this->values =$values;
	}

	/**
	 * @return Collection
	 */
	public function getColumnLabels() {
		return $this->columnLabels;
	}

	/**
	 * @return Collection
	 */
	public function getRowLabels() {
		return $this->rowLabels;
	}

	/**
	 * @return Matrix
	 */
	public function getValues() {
		return $this->values;
	}

    /**
     *
     * @param array|callable $filter
     * @return LabeledMatrix
     */
	public function filterRows($filter) {
        $filter = is_callable($filter) ? $filter : function ($i) use($filter) {return isset($filter[$i]);};
		$rowLabels = $this->rowLabels->filter($filter);
        $keepKeys = array_flip($rowLabels->getKeys());

		return new static(
            clone $this->columnLabels,
            $rowLabels->valueCollection(),
            $this->values->filterRows($keepKeys)
        );
	}

    /**
     *
     * @param array|callable $filter
     * @return LabeledMatrix
     */
	public function filterColumns($filter) {
        $filter = is_callable($filter) ? $filter : function ($i) use($filter) {return isset($filter[$i]);};
        $columnLabels = $this->columnLabels->filter($filter);
        $keepKeys = array_flip($columnLabels->getKeys());

        return new static(
            $columnLabels->valueCollection(),
            clone $this->rowLabels,
            $this->values->filterColumns($keepKeys)
        );
	}


    /**
     * @param string $format
     * @return string
     */
	public function toCSV($format = '%10.4F') {
		$csv = '';
        /** @var $row Vector */
		foreach ($this->values as $row) {
			$csv .= implode(',',  array_map(function ($value) use ($format) {return sprintf($format, $value);}, $row->values())).PHP_EOL;
		}
		return $csv;
	}



    /**
     * @return string
     */
    public function __toString() {
        return $this->getValues()->toString();
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

    /**
     * @return object
     */
    public function toObject() {
        return (object) array(
            'plot' => $this->values->values(),
            'rowLabels' => $this->getRowLabels()->jsonSerialize(),
            'columnLabels' => $this->getColumnLabels()->jsonSerialize()
        );
    }

}
?>

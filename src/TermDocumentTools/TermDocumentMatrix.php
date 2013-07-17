<?php

namespace TermDocumentTools;

use ___PHPSTORM_HELPERS\object;
use LinearAlgebra\Matrix;
use LinearAlgebra\Vector;

class TermDocumentMatrix implements \JsonSerializable {
	/**
	 * @var array
	 */
	protected $terms;

	/**
	 * @var array
	 */
	protected $documents;

	/**
	 * @var Matrix
	 */
	protected $values;

	/**
	 *
	 * @param array|Terms $terms
	 * @param array|Documents  $documents
	 * @param Matrix $values
	 * @throws \InvalidArgumentException
	 */
	public function __construct(Terms $terms, Documents $documents, Matrix $values) {
		if (count($documents) != count($values)) {
			throw new \InvalidArgumentException('$values must have # of document entries in the first dimension');
		}

		foreach($values as $documentValues) {
			if (count($terms) != count($documentValues)) {
				throw new \InvalidArgumentException('$values must have # of term entries in the second dimension');
			}
		}

		$this->terms = $terms;
		$this->documents = $documents;
		$this->values =$values;
	}

	/**
	 * @return Terms
	 */
	public function getTerms() {
		return $this->terms;
	}

	/**
	 * @return Documents
	 */
	public function getDocuments() {
		return $this->documents;
	}

	/**
	 * @return Matrix
	 */
	public function getValues() {
		return $this->values;
	}

    /**
     *
     * @param Filter $filter
     * @return TermDocumentMatrix
     */
	public function filterDocuments(Filter $filter) {
		$newDocuments = array();
		$newValues = array();
		foreach ($this->documents as $index => $document) {
			if ($filter($document, $index)) {
				$newDocuments[] = $document;
				$newValues[] = $this->values->row($index)->values();
			}
		}

		return new TermDocumentMatrix($this->terms, new Documents($newDocuments), new Matrix($newValues));
	}

    /**
     *
     * @param Filter $filter
     * @return TermDocumentMatrix
     */
	public function filterTerms(Filter $filter) {
		$newTerms = array();
		$newValues = array();
		foreach ($this->terms as $index => $term) {
			if ($filter($term, $index)) {
				$newTerms[] = $term;
				$column = $this->values->column($index)->values();
				for ($j = 0; $j < count($column);$j++) {
					$newValues[$j][] = $column[$j];
				}
			}
		}

		return new TermDocumentMatrix(new Terms($newTerms), $this->documents, new Matrix($newValues));
	}


    /**
     * @param string $format
     * @return string
     */
	public function toCSV($format = '%10.4F') {
		$csv = '';
		foreach ($this->values as $row) {
            /** @var $row Vector */
			$csv .= implode(',',  array_map(function ($value) use ($format) {return sprintf($format, $value);}, $row->values())).PHP_EOL;
		}
		return $csv;
	}


    /**
     * @return Features
     */
    public function calculateFactorMatrix() {
        $f = $this->values->svd()->truncate($this->values->m());

        $features = new Features();
        for ($featureNumber = 0; $featureNumber <  $f->m(); $featureNumber++) {
            $features[$featureNumber] = new Feature(
                $featureNumber,
                new Vector($f->v()->row($featureNumber)->values()),
                new Vector($f->u()->column($featureNumber)->values()),
                $f->s()->diagonal($featureNumber),
                'Feature '.$featureNumber
            );
        }

        return $features;
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
        $data = array();
        return (object) array(
            'plot' => $data,
            'parties' => $this->getDocuments()->jsonSerialize(),
            'terms' => $this->getTerms()->jsonSerialize()
        );
    }

}
?>

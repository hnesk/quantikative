<?php

namespace TermDocumentTools;

use LinearAlgebra\LabeledMatrix;
use LinearAlgebra\Matrix;
use LinearAlgebra\Vector;

class TermDocumentMatrix extends LabeledMatrix {

    /**
     *
     * @param Terms $terms
     * @param Documents $documents
     * @param Matrix $values
     */
	public function __construct(Terms $terms, Documents $documents, Matrix $values) {
        parent::__construct($terms, $documents, $values);
	}

	/**
	 * @return Terms
	 */
	public function getTerms() {
		return $this->columnLabels;
	}

	/**
	 * @return Documents
	 */
	public function getDocuments() {
		return $this->rowLabels;
	}

    /**
     * @param array $map
     * @return \TermDocumentTools\TermDocumentMatrix
     */
    public function permuteDocuments($map = array()) {
        return new static(
            $this->getTerms(),
            $this->getDocuments()->permute($map),
            $this->values->permute($map)
        );
    }


    /**
     * @param array $map
     * @return \TermDocumentTools\TermDocumentMatrix
     */
    public function permuteTerms($map = array()) {
        return new static(
            $this->getTerms()->permute($map),
            $this->getDocuments(),
            $this->values->transpose()->permute($map)->transpose()
        );
    }

    /**
     * @param array|callable $filter
     * @return TermDocumentMatrix
     */
    public function filterTerms($filter) {
        return $this->filterColumns($filter);
    }

    /**
     * @param array|callable $filter
     * @return TermDocumentMatrix
     */
    public function filterDocuments($filter) {
        return $this->filterRows($filter);
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
     * @return DocumentDocumentMatrix
     */
    public function calculateDocumentMatrix() {
        $m = $this->values->cosine($this->values->transpose());
        return new DocumentDocumentMatrix($this->getDocuments(),$m);
    }


    /**
     * @return object
     */
    public function toObject() {
        return (object) array(
            'values' => $this->getValues()->transpose()->jsonSerialize(),
            'documents' => $this->getDocuments()->jsonSerialize(),
            'terms' => $this->getTerms()->jsonSerialize()
        );
    }

}
?>

<?php

namespace TermDocumentTools;

use LinearAlgebra\LabeledMatrix;
use LinearAlgebra\Matrix;
use LinearAlgebra\Vector;

class TermDocumentMatrix extends LabeledMatrix {

	/**
	 *
	 * @param Terms $terms
	 * @param Documents  $documents
	 * @param Matrix $values
	 * @throws \InvalidArgumentException
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

<?php

namespace TermDocumentTools;

use LinearAlgebra\LabeledMatrix;
use LinearAlgebra\Matrix;
use LinearAlgebra\Vector;

class DocumentDocumentMatrix extends LabeledMatrix {

	/**
	 *
	 * @param Documents  $documents
	 * @param Matrix $values
	 * @throws \InvalidArgumentException
	 */
	public function __construct(Documents $documents, Matrix $values) {
        parent::__construct($documents, $documents, $values);
	}

	/**
	 * @return Documents
	 */
	public function getDocuments() {
		return $this->rowLabels;
	}

    /**
     * @param array|callable $filter
     * @return TermDocumentMatrix
     */
    public function filterDocuments($filter) {
        return $this->filterRows($filter)->filterColumns($filter);
    }



    /**
     * @return object
     */
    public function toObject() {

        return (object) array(
            'values' => $this->values->toObject(),
            'documents' => $this->getDocuments()->toObject()
        );
    }

}
?>

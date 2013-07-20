<?php

namespace LinearAlgebra\Tests;

use LinearAlgebra\LabeledMatrix;
use LinearAlgebra\Matrix;
use LinearAlgebra\Vector;
use TermDocumentTools\Documents;
use TermDocumentTools\Document;
use TermDocumentTools\Terms;
use TermDocumentTools\Term;

class LabeledMatrixTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var LabeledMatrix
     */
    protected $m;

    /**
     * @var Terms
     */
    protected $terms;

    /**
     * @var Documents
     */
    protected $documents;

    /**
     * @var Matrix
     */
    protected $matrix;

    public function setUp() {
        $this->terms = new Terms(array(
            new Term('KH','Kill all humans'),
            new Term('KA','Kill all animals'),
        ));
        $this->documents = new Documents(array(
            new Document('CDU','CDU'),
            new Document('SPD','SPD'),
            new Document('FDP','FDP'),
        ));
        $this->matrix = new Matrix(
            array(
                array(0, 1),
                array(-1, 0),
                array(1, 1),
            )
        );

        $this->m = new LabeledMatrix($this->terms, $this->documents, $this->matrix);
    }

    public function testGetRowLabels() {
        $this->assertEquals($this->documents, $this->m->getRowLabels());
    }

    public function testGetColumnLabels() {
        $this->assertEquals($this->terms, $this->m->getColumnLabels());
    }

    public function testGetValues() {
        $this->assertEquals($this->matrix, $this->m->getValues());
    }

    public function testFilterRows() {
        $filtered = $this->m->filterRows(
            function (Document $d) {
                return in_array($d->id(),array('CDU','FDP'));
            }
        );

        $expectedRowLabels = new Documents(array(
            new Document('CDU','CDU'),
            new Document('FDP','FDP')
        ));
        $expectedValues = new Matrix(
            array(
                array(0, 1),
                array(1, 1)
            )
        );

        $this->assertEquals($expectedRowLabels, $filtered->getRowLabels());
        $this->assertEquals($this->terms, $filtered->getColumnLabels());
        $this->assertEquals($expectedValues->values(), $filtered->getValues()->values());
    }

    public function testFilterColumns() {
        $filtered = $this->m->filterColumns(function (Term $t) {return $t->id() == 'KH';});

        $expectedColumnLabels = new Terms(array(
            new Term('KH','Kill all humans'),
        ));
        $expectedValues = new Matrix(
            array(
                array(0),
                array(-1),
                array(1)
            )
        );

        $this->assertEquals($expectedColumnLabels->toArray(), $filtered->getColumnLabels()->toArray());
        $this->assertEquals($this->documents, $filtered->getRowLabels());
        $this->assertEquals($expectedValues->values(), $filtered->getValues()->values());
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

}
?>

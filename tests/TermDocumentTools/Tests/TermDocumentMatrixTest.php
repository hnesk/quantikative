<?php

namespace TermDocumentTools\Tests;

use LinearAlgebra\Matrix;
use LinearAlgebra\Vector;
use TermDocumentTools\ArrayKeyFilter;
use TermDocumentTools\Document;
use TermDocumentTools\Documents;
use TermDocumentTools\Term;
use TermDocumentTools\TermDocumentMatrix;
use TermDocumentTools\Terms;

class TermDocumentMatrixTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var TermDocumentMatrix
     */
    protected $m;

    public function setUp() {
        $this->m = new TermDocumentMatrix(
            new Terms(array(
                new Term('KH','Kill all humans'),
                new Term('KA','Kill all animals'),
            )),
            new Documents(array(
                new Document('CDU','CDU'),
                new Document('SPD','SPD'),
                new Document('FDP','FDP'),
            )),
            new Matrix(
                array(
                    array(0, 1),
                    array(-1, 0),
                    array(1, 1),
                )
            )
        );
    }

    public function testFilterDocumentsWithEmptyFilterReturnsAllDocuments() {
        $filtered = $this->m->filterRows(ArrayKeyFilter::create(array())->toClosure());
        $this->assertEquals($this->m->getDocuments(), $filtered->getDocuments());
    }

    public function testFilterTermsWithEmptyFilterReturnsAllTerms() {
        $filtered = $this->m->filterColumns(ArrayKeyFilter::create(array())->toClosure());
        $this->assertEquals($this->m->getTerms(), $filtered->getTerms());
    }



    public function tearDown() {
        $this->m = null;
    }
}
?>

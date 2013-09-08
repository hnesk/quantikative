<?php

namespace TermDocumentTools\Tests;

use LinearAlgebra\Vector;
use TermDocumentTools\Document;
use TermDocumentTools\Documents;
use TermDocumentTools\Term;

class DocumentsTest extends \PHPUnit_Framework_TestCase {


    public function testCountable() {
        $documents = new Documents();
        $this->assertCount(0, $documents);
        $documents->append(new Document('a','aa'));
        $this->assertCount(1, $documents);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTypeSafetyOffsetSet() {
        $documents = new Documents();
        $documents[0] = new Term('a','aa');
    }

    /**
     * @expectedException \PHPUnit_Framework_Error
     * @expectedExceptionMessage  must be an instance of TermDocumentTools\Document
     */
    public function testTypeSafetyAppend() {
        $documents = new Documents();
        /** @noinspection PhpParamsInspection */
        $documents->append(new Term('a','aa'));
    }



}
?>

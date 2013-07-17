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

class DocumentTest extends \PHPUnit_Framework_TestCase {


    public function testConstructorSetsData() {
        $doc = new Document('a','aa','aaa');
        $this->assertEquals('a', $doc->id());
        $this->assertEquals('aa', $doc->name());
        $this->assertEquals('aaa', $doc->description());
    }


    public function testSerialize() {
        $doc = new Document('a','aa','aaa');
        $this->assertEquals(
            (object) array(
                'index' => 'a',
                'short' => 'aa',
                'long' => 'aaa',
            ),
            $doc->jsonSerialize()
        );
    }

}
?>

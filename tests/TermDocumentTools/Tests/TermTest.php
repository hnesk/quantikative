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

class TermTest extends \PHPUnit_Framework_TestCase {


    public function testConstructorSetsData() {
        $term = new Term('a','aa','aaa');
        $this->assertEquals('a', $term->id());
        $this->assertEquals('aa', $term->name());
        $this->assertEquals('aaa', $term->description());
    }


    public function testSerialize() {
        $term = new Term('a','aa','aaa');
        $this->assertEquals(
            (object) array(
                'index' => 'a',
                'short' => 'aa',
                'long' => 'aaa',
            ),
            $term->jsonSerialize()
        );
    }

}
?>

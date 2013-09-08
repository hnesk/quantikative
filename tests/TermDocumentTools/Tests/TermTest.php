<?php

namespace TermDocumentTools\Tests;

use TermDocumentTools\Term;

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

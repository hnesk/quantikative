<?php

namespace TermDocumentTools\Tests;

use TermDocumentTools\Document;

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

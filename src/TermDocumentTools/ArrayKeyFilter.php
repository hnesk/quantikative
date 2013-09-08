<?php

namespace TermDocumentTools;

class ArrayKeyFilter extends Filter  {

    public function __invoke($value, $index) {
        /** @var Document $value */
        return empty($this->filter) || isset($this->filter[$value->id()]);
    }
}

?>

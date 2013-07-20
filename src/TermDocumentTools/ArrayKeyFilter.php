<?php

namespace TermDocumentTools;

class ArrayKeyFilter extends Filter  {

    public function __invoke($value, $index) {
        return empty($this->filter) || isset($this->filter[$index]);
    }
}

?>

<?php

namespace TermDocumentTools;

abstract class Filter {

    protected $filter;

    public function __construct($filter) {
        $this->filter = $filter;
    }

    abstract public function __invoke($value, $index);
}

?>

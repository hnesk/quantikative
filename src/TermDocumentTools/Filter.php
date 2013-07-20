<?php

namespace TermDocumentTools;

abstract class Filter {

    protected $filter;

    public function __construct($filter) {
        $this->filter = $filter;
    }

    abstract public function __invoke($value, $index);

    /**
     * @return \Closure
     */
    public function toClosure() {

        $f = function ($value, $index = null) {
            return $this->__invoke($value, $index);
        };
        return $f->bindTo($this, $this);
    }

    /**
     * @param $predicate
     * @return Filter
     */
    public static function create($predicate) {
        return new static($predicate);
    }
}

?>

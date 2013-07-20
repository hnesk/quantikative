<?php

namespace TermDocumentTools;

class ArrayFilter extends ArrayKeyFilter  {

    /**
     * @param array|string $filter
     */
    public function __construct($filter) {
        if (!is_array($filter)) {
            $filter = array_map('trim',explode(',', $filter));
        }
        parent::__construct(array_flip($filter));
    }
}

?>

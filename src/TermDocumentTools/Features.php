<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jk
 * Date: 03.07.13
 * Time: 23:25
 * To change this template use File | Settings | File Templates.
 */

namespace TermDocumentTools;


class Features extends Collection {

    public function append(Feature $t) {
        $this[] = $t;
    }

    protected function checkValue($value)
    {
        if (!$value instanceof Feature) {
            throw new \InvalidArgumentException($value . ' is not of type Term');
        }
    }
}
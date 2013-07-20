<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jk
 * Date: 03.07.13
 * Time: 23:25
 * To change this template use File | Settings | File Templates.
 */

namespace TermDocumentTools;


class Documents extends Collection {

    public function append(Document $t) {
        return parent::add($t);
    }

    protected function checkValue($value)
    {
        if (!$value instanceof Document) {
            throw new \InvalidArgumentException($value . ' is not of type Term');
        }
    }
}
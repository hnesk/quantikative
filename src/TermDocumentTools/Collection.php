<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jk
 * Date: 03.07.13
 * Time: 22:54
 * To change this template use File | Settings | File Templates.
 */

namespace TermDocumentTools;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * A typesafe Collection class
 * @package TermDocumentTools
 */
abstract class Collection extends ArrayCollection implements \JsonSerializable {

    /**
     * @var array
     */
    protected $entries;


    public function set($key, $value) {
        $this->checkValue($value);
        return parent::set($key, $value);
    }

    public function add($value) {
        $this->checkValue($value);
        return parent::add($value);
    }


    /**
     * @return string
     */
    public function __toString() {
        return $this->toString();
    }

    /**
     * @param string $separator
     * @param string $format
     * @return string
     */
    public function toString($separator = "\n", $format = '%1s => %2s') {
        $parts = array();
        foreach ($this as $k => $v) {
            $parts[] = sprintf($format, $k, (string)$v);
        }
        return implode($separator, $parts);
    }

    public function jsonSerialize() {
        return $this->toObject();
    }

    public function toObject() {
        return $this->entries;
    }

    /**
     * @param mixed $value
     * @return void
     */
    abstract protected function checkValue($value);
}
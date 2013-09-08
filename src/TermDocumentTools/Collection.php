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
 * A type safe Collection class
 * @package TermDocumentTools
 */
abstract class Collection extends ArrayCollection implements \JsonSerializable {


    public function set($key, $value) {
        $this->checkValue($value);
        parent::set($key, $value);
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
     * Re-indexes the collection
     * @return Collection
     */
    public function valueCollection() {
        return new static($this->getValues());
    }

    /**
     * @param string $separator
     * @param string $format
     * @return string
     */
    public function toString($separator = PHP_EOL, $format = '%1s => %2s') {
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
        return $this->toArray();
    }

    /**
     * @param array $map
     * @return static
     */
    public function permute($map) {
        $values = array();
        foreach ($map as $newIndex => $oldIndex) {
            $values[$newIndex] = $this[$oldIndex];
        }

        return new static($values);
    }

    /**
     * @param mixed $value
     * @return void
     */
    abstract protected function checkValue($value);
}
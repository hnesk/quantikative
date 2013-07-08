<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jk
 * Date: 03.07.13
 * Time: 22:54
 * To change this template use File | Settings | File Templates.
 */

namespace TermDocumentTools;


abstract class Collection implements \IteratorAggregate, \ArrayAccess, \Countable, \JsonSerializable {

    /**
     * @var array
     */
    protected $entries;

    /**
     * @param array $entries
     */
    public function __construct($entries=array()) {
        foreach ($entries as $k=>$v) {
            $this[$k] = $v;
        }
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->entries);
    }

    public function offsetExists($offset)
    {
        return isset($this->entries[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->entries[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->checkValue($value);
        if ($offset) {
            $this->entries[$offset] = $value;
        } else {
            $this->entries[] = $value;
        }

    }

    public function offsetUnset($offset)
    {
        unset($this->entries[$offset]);
    }

    public function count()
    {
        return count($this->entries);
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
        return $this->entries;
    }

    /**
     * @param mixed $value
     * @return void
     */
    abstract protected function checkValue($value);
}
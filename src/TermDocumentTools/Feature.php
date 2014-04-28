<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jk
 * Date: 03.07.13
 * Time: 22:54
 * To change this template use File | Settings | File Templates.
 */

namespace TermDocumentTools;


use LinearAlgebra\Vector;

class Feature implements \JsonSerializable {

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \LinearAlgebra\Vector
     */
    protected $termValues;

    /**
     * @var \LinearAlgebra\Vector
     */
    protected $documentValues;

    /**
     * @param int $id
     * @param \LinearAlgebra\Vector $termValues
     * @param \LinearAlgebra\Vector $documentValues
     * @param int $weight
     * @param string $name
     */
    public function __construct($id, Vector $termValues, Vector $documentValues, $weight = 1, $name = '')
    {
        $this->id = intval($id);
        $this->termValues = $termValues;
        $this->documentValues = $documentValues;
        $this->weight = $weight;
        $this->name = trim($name);
    }

    /**
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name() {
        return $this->name;
    }

    /**
     * @return float
     */
    public function weight()
    {
        return $this->weight;
    }

    public function __toString() {
        return $this->name ? $this->name : $this->id;
    }


    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return $this->toObject();
    }

    /**
     * @param \TermDocumentTools\Document|\TermDocumentTools\Term $entry
     * @throws \InvalidArgumentException
     * @return float
     */
    public function getValue($entry) {
        if ($entry instanceof Term) {
            $value = $this->termValues[$entry->id()];
        } else if ($entry instanceof Document) {
            $value = $this->documentValues[$entry->id()];
        } else {
            throw new \InvalidArgumentException("entry needs to be of type Term or Document");
        }
        return $this->weight * $value;
    }


    /**
     * @param int $i
     * @return float
     */
    public function getTermValue($i) {
        try {
            return $this->weight * $this->termValues[$i];
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * @param int $i
     * @return float
     */
    public function getDocumentValue($i) {
        try {
            return $this->weight * $this->documentValues[$i];
        } catch (\Exception $e) {
            return 0;
        }

    }

    /**
     * @return \LinearAlgebra\Vector
     */
    public function getDocumentValues()
    {
        return $this->documentValues;
    }

    /**
     * @return \LinearAlgebra\Vector
     */
    public function getTermValues()
    {
        return $this->termValues;
    }


    public function toObject() {
        return (object) array(
            'index' => $this->id,
            'short' => $this->name,
            'weight' => $this->weight,
            'termValues' => $this->termValues,
            'documentValues' => $this->documentValues,
        );

    }

    public function toObjectWithJoinedLabels(Terms $terms, Documents $documents) {
        $result = array();
        foreach ($documents as $i => $item) {
            /** @var $item Document  */
            $result[] = (object)array(
                'id' => 'd_'.$item->id(),
                'type' => 'd',
                'name' => $item->name(),
                'value' => $this->weight * $this->documentValues[$i]
            );
        }
        foreach ($terms as $i => $item) {
            /** @var $item Term */
            $result[] = (object)array(
                'id' => 't_'.$item->id(),
                'type' => 't',
                'name' => $item->name(),
                'value' => $this->weight * $this->termValues[$i]
            );
        }

        return $result;
    }
}
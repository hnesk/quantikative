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

    /**
     * @var Terms
     */
    protected $terms;

    /**
     * @var Documents
     */
    protected $documents;


    public function __construct(Terms $terms, Documents $documents) {
        $this->terms = $terms;
        $this->documents = $documents;
        parent::__construct(array());
    }

    public function jsonSerialize() {
        $result = array();
        foreach ($this as $feature) { /** @var $feature Feature */
            $result[] = (object)array(
                'id' => $feature->id(),
                'name' => $feature->name(),
                'weight' => $feature->weight(),
                'data' => $feature->toObjectWithJoinedLabels($this->terms, $this->documents),
            );
        }
        return $result;
    }

    public function append(Feature $t) {
        $this[] = $t;
    }

    protected function checkValue($value)
    {
        if (!$value instanceof Feature) {
            throw new \InvalidArgumentException($value . ' is not of type Feature');
        }
    }
}
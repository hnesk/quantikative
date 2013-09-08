<?php

namespace Statistics\ClusterAlgorithm\Hierarchical;

abstract class Cluster{

    protected $value;

    /**
     * @return object
     */
    public function getValue() {
        return $this->value;
    }

    abstract public function getIndex();

    abstract public function getCacheKey();
}
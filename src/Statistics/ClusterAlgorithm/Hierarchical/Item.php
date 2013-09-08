<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jk
 * Date: 20.08.13
 * Time: 21:37
 * To change this template use File | Settings | File Templates.
 */

namespace Statistics\ClusterAlgorithm\Hierarchical;



class Item extends Cluster  {

    protected $index;

    public function __construct($index, $value) {
        $this->index = $index;
        $this->value = $value;
    }

    public function getCacheKey() {
        return $this->index;
    }

    public function getIndex() {
        return array($this->index);
    }

    public function __toString() {
        return $this->index.PHP_EOL;
    }
}
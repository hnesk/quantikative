<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jk
 * Date: 20.08.13
 * Time: 21:37
 * To change this template use File | Settings | File Templates.
 */

namespace Statistics\ClusterAlgorithm\Hierarchical;

class Pair extends Cluster  {

    protected $left;

    protected $right;

    static $NR = -1;

    public function __construct($value, $left, $right) {
        $this->value =  $value;
        $this->left = $left;
        $this->right = $right;
        $this->cacheKey = --self::$NR;
    }

    public function getCacheKey() {
        return $this->cacheKey;
    }

    public function getIndex() {
        return array_merge($this->left->getIndex(), $this->right->getIndex());
    }

    public function __toString() {
        return '<ul>'.
            '<li>'.$this->left.'</li>'.
            '<li>'.$this->right.'</li>'.
        '</ul>';
    }
}
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jk
 * Date: 01.09.13
 * Time: 13:41
 * To change this template use File | Settings | File Templates.
 */

namespace Statistics\ClusterAlgorithm\KMeans;


use LinearAlgebra\Matrix;
use LinearAlgebra\Vector;
use Statistics\ClusterAlgorithm\AlgorithmInterface;

class Algorithm implements AlgorithmInterface{

    /**
     * @var int
     */
    protected $k;

    /**
     * @var array
     */
    protected $centroids;

    /**
     * @param int $k
     */
    public function __construct($k = 5) {
        $this->k = $k;

    }

    /**
     * @param $data
     */
    public function calculate($data) {
        /** @var $data Matrix */
        foreach ($data as $i=>$row) {
            /** @var $row Vector */
            $range[$i] = array($row->min(), $row->max());
        }


        for ($i = 0; $i < $this->k; $i++) {

            $this->centroids[$i] = array();
        }

    }

    public function getSortedPermutation($clusters) {

    }
}
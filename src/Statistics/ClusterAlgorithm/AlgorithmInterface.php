<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jk
 * Date: 01.09.13
 * Time: 13:39
 * To change this template use File | Settings | File Templates.
 */

namespace Statistics\ClusterAlgorithm;


interface AlgorithmInterface {
    public function calculate($data);

    public function getSortedPermutation($clusters);

}
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jk
 * Date: 20.08.13
 * Time: 22:30
 * To change this template use File | Settings | File Templates.
 */

namespace LinearAlgebra;

interface Measureable {

    /**
     * @param $b
     * @return double
     */
    public function distance(Measureable $b);

    /**
     * @return double
     */
    public function norm();

}
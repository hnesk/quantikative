<?php

namespace LinearAlgebra\Tests;

use LinearAlgebra\Matrix;
use LinearAlgebra\Vector;

class SVDTest extends \PHPUnit_Framework_TestCase {

    public function testFactorizationFactorsFactors() {
        $m = new Matrix(
            array(
                array(3,1,4),
                array(1,5,9),
                array(2,6,5)
            )
        );

        $factors = $m->svd();
        $this->assertEquals($m, $factors->multiply(),'Factorization is not equal to the original values', 0.001);
    }

    public function testFactorizationFactorsFactorsForNonSquareMatricesToo() {
        $m = new Matrix(
            array(
                array(3,1,4),
                array(1,5,9),
                array(2,6,5),
                array(3,5,8),
                array(9,7,9)
            )
        );

        $factors = $m->svd();
        $this->assertEquals($m,$factors->multiply(),'Factorization is not equal to the original values', 0.001);
    }

}

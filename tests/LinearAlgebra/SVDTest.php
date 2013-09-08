<?php

namespace LinearAlgebra\Tests;

use LinearAlgebra\Matrix;
use LinearAlgebra\Vector;

class SVDTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var array
     */
    protected $values =  array(
        array(1,2,3),
        array(2,4,5),
    );


    public function testConstructorEmpty() {
        $m = new Matrix();
        $this->assertEquals(0, $m->n());
        $this->assertEquals(0, $m->m());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage array must have the same number of columns in each row
     */
    public function testConstructorThrowsOnNonRectangularMatrix() {
        new Matrix(array(array(1), array(1,2)));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage 2 dimensional array expected
     */
    public function testConstructorThrowsOnOneDimensionalArray() {
        new Matrix(array(1,2));
    }


    public function testConstructorSetsValues() {
        $m = new Matrix($this->values);
        $this->assertEquals(2, $m->m());
        $this->assertEquals(3, $m->n());
        $this->assertEquals(1, $m->index(0,0));
        $this->assertEquals(2, $m->index(0,1));
        $this->assertEquals(3, $m->index(0,2));
        $this->assertEquals(2, $m->index(1,0));
        $this->assertEquals(4, $m->index(1,1));
        $this->assertEquals(5, $m->index(1,2));
        $this->assertEquals($this->values, $m->values());
    }


    public function testEye() {
        $e = Matrix::eye(array(1,2,3));
        $expected = array(
            array(1,0,0),
            array(0,2,0),
            array(0,0,3),
        );
        $this->assertEquals($expected, $e->values());
    }

    public function testIdentity() {
        $e = Matrix::identity(3);
        $expected = array(
            array(1,0,0),
            array(0,1,0),
            array(0,0,1),
        );
        $this->assertEquals($expected, $e->values());
    }

    public function testTranspose() {
        $m = Matrix::create($this->values);

        $expected = array(
            array(1,2),
            array(2,4),
            array(3,5)
        );

        $this->assertEquals($expected, $m->transpose()->values());
    }


    public function testDoubleTransposeEqualsOrigin() {
        $m = Matrix::create($this->values);
        $this->assertEquals($m, $m->transpose()->transpose());
    }

    public function testTransposeDoesNotEffectEyeMatrix() {
        $eye = Matrix::eye(1,2,3);
        $this->assertEquals($eye, $eye->transpose());
    }

    public function testRow() {
        $m = Matrix::create($this->values);
        $this->assertEquals(Vector::create(1,2,3), $m->row(0));
        $this->assertEquals(Vector::create(2,4,5), $m->row(1));
    }

    /**
     * @expectedException \OutOfBoundsException
     *
     */
    public function testRowThrowsOnNegativeIndex() {
        $m = Matrix::create($this->values);
        $m->row(-1);
    }

    /**
     * @expectedException \OutOfBoundsException
     *
     */
    public function testRowThrowsOnInvalidIndex() {
        $m = Matrix::create($this->values);
        $m->row(2);
    }

    public function testColumn() {
        $m = Matrix::create($this->values);
        $this->assertEquals(Vector::create(1,2), $m->column(0));
        $this->assertEquals(Vector::create(2,4), $m->column(1));
        $this->assertEquals(Vector::create(3,5), $m->column(2));
    }

    /**
     * @expectedException \OutOfBoundsException
     *
     */
    public function testColumnThrowsOnNegativeIndex() {
        $m = Matrix::create($this->values);
        $m->column(-1);
    }

    /**
     * @expectedException \OutOfBoundsException
     *
     */
    public function testColumnThrowsOnInvalidIndex() {
        $m = Matrix::create($this->values);
        $m->column(3);
    }

    /**
     * @expectedException \OutOfBoundsException
     *
     */
    public function testIndexRowNegativeThrows() {
        $m = Matrix::create($this->values);
        $m->index(-1,1);
    }

    /**
     * @expectedException \OutOfBoundsException
     *
     */
    public function testIndexColumnNegativeThrows() {
        $m = Matrix::create($this->values);
        $m->index(1,-1);
    }

    /**
     * @expectedException \OutOfBoundsException
     *
     */
    public function testIndexRowOverflowThrows() {
        $m = Matrix::create($this->values);
        $m->index(2,0);
    }

    /**
     * @expectedException \OutOfBoundsException
     *
     */
    public function testIndexColumnOverflowThrows() {
        $m = Matrix::create($this->values);
        $m->index(0,3);
    }

    public function testDiagonal() {
        $m = Matrix::eye(array(1,3,2));
        $this->assertEquals(1, $m->diagonal(0));
        $this->assertEquals(3, $m->diagonal(1));
        $this->assertEquals(2, $m->diagonal(2));
    }


    public function testToString() {
        $m = Matrix::create($this->values);
        $output =
            '            0      1      2' . PHP_EOL . PHP_EOL .
            '     0   1.00   2.00   3.00' . PHP_EOL .
            '     1   2.00   4.00   5.00' . PHP_EOL
        ;
        $this->assertEquals($output, $m->toString());
    }

    public function testSquared() {
        $m = Matrix::create($this->values);
        $m = $m->squared();
        $a = Matrix::create(array(array(1,2), array(2,4)));
        $this->assertEquals($a, $m);
    }


    public function testShort() {
        $m = Matrix::create($this->values);
        $this->assertEquals(2, $m->short());
    }

    public function testLong() {
        $m = Matrix::create($this->values);
        $this->assertEquals(3, $m->long());
    }

    public function testGetEye() {
        $v = Vector::create(1,2,3);
        $m = Matrix::eye($v);
        $this->assertEquals($v, $m->getEye());
    }


    public function testAdd() {
        $a = Matrix::create(array(
                array(2,5),
                array(1,2),
                array(3,2),
        ));
        $b = Matrix::create(array(
                array(1,2),
                array(7,1),
                array(0,5),
        ));
        $c = Matrix::create(array(
                array(3,7),
                array(8,3),
                array(3,7),
        ));
        $this->assertEquals($c->values(), $a->add($b)->values());

    }

    public function testSubtract() {
        $a = Matrix::create(array(
                array(2,5),
                array(1,2),
                array(3,2),
            ));
        $b = Matrix::create(array(
                array(1,2),
                array(7,1),
                array(0,5),
            ));
        $c = Matrix::create(array(
                array(1,3),
                array(-6,1),
                array(3,-3),
            ));
        $this->assertEquals($c->values(), $a->subtract($b)->values());

    }

    public function testNegate() {
        $m = Matrix::create($this->values);
        $n = Matrix::create(
            array(
                array(-1,-2,-3),
                array(-2,-4,-5),
            )
        );
        $this->assertEquals($n->values(), $m->negate()->values());
    }


    public function testRound() {
        $r = Matrix::create($this->values);
        $f = Matrix::create(
            array(
                array( 1.2, 2.4999, 2.5001),
                array( 2.1, 3.67, 4.56),
            )
        );
        $this->assertEquals($r->values(), $f->round()->values());
    }

    public function testMap() {
        $m = Matrix::create($this->values);
        $is = array();
        $js = array();
        $expected = Matrix::create(array(
                array(2,4,6),
                array(4,8,10)
        ));
        $n = $m->map(
            function ($a, $i, $j) use (&$is, &$js) {
                $is[] = $i;
                $js[] = $j;
                return 2*$a;
            }
        );

        $this->assertEquals($expected->values(), $n->values());
        $this->assertEquals(array(0,0,0,1,1,1), $is);
        $this->assertEquals(array(0,1,2,0,1,2), $js);
    }

    public function testDim() {
        $this->assertEquals('2x3', Matrix::create($this->values)->dim());
    }


    public function testMultiply() {
        $m = Matrix::create($this->values);
        $expected = Matrix::create(
            array(
                array(
                    1*1 + 2*2 + 3*3,
                    1*2 + 2*4 + 3*5
                ),
                array(
                    2*1 + 4*2 + 5*3,
                    2*2 + 4*4 + 5*5
                ),
            )
        );
        $a = $m->multiply($m->transpose());
        $this->assertEquals($expected->values(), $a->values());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMultiplyThrowsOnIncompatibleMatrices() {
        $m = Matrix::create($this->values);
        $m->multiply($m);
    }

    public function testNorm() {
        $m = Matrix::create($this->values);
        $expected = sqrt(1*1 + 2*2 + 3*3 + 2*2 + 4*4 + 5*5);
        $this->assertEquals($expected, $m->norm());
    }

    public function testMax() {
        $this->assertEquals(5,Matrix::create($this->values)->max());
    }

    public function testMin() {
        $this->assertEquals(1,Matrix::create($this->values)->min());
    }


    public function testFilterRowsWithAllRowsIsIdentical() {
        $m = Matrix::create($this->values);
        $filtered = $m->filterRows(array(0=>true, 1=>true));
        $this->assertEquals($m->values(), $filtered->values());
    }

    public function testFilterRowsWithArrayPredicate() {
        $m = Matrix::create($this->values);
        $filtered = $m->filterRows(array(0=>true));
        $this->assertEquals(array(array(1,2,3)), $filtered->values());
    }


    public function testFilterRowsWithTruePredicate() {
        $m = Matrix::create($this->values);
        $filtered = $m->filterRows(function () { return true;});
        $this->assertEquals($m->values(), $filtered->values());
    }

    public function testFilterRowsWithFalsePredicate() {
        $m = Matrix::create($this->values);
        $filtered = $m->filterRows(function () { return false;});
        $this->assertEquals(array(), $filtered->values());
    }

    public function testFilterRowsWithCallbackPredicate() {
        $m = Matrix::create($this->values);
        $filtered = $m->filterRows(function ($i) { return $i==1;});
        $this->assertEquals(array(array(2,4,5)), $filtered->values());
    }

    public function testFilterColumnsWithAllRowsIsIdentical() {
        $m = Matrix::create($this->values);
        $filtered = $m->filterColumns(array(0=>true, 1=>true, 2=>true));
        $this->assertEquals($m->values(), $filtered->values());
    }

    public function testFilterColumnsWithArrayPredicate() {
        $m = Matrix::create($this->values);
        $filtered = $m->filterColumns(array(2=>true));
        $this->assertEquals(array(array(3),array(5)), $filtered->values());
    }


    public function testColumnsRowsWithTruePredicate() {
        $m = Matrix::create($this->values);
        $filtered = $m->filterColumns(function () { return true;});
        $this->assertEquals($m->values(), $filtered->values());
    }

    public function testFilterColumnsWithFalsePredicate() {
        $m = Matrix::create($this->values);
        $filtered = $m->filterColumns(function () { return false;});
        $this->assertEquals(array(), $filtered->values());
    }

    public function testFilterColumnsWithCallbackPredicate() {
        $m = Matrix::create($this->values);
        $filtered = $m->filterColumns(function ($i) { return $i==1;});
        $this->assertEquals(array(array(2),array(4)), $filtered->values());
    }

}

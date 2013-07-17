<?php

namespace LinearAlgebra\Tests;

use LinearAlgebra\Vector;

class VectorTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var Vector
	 */
	protected $v;

    protected function setUp()
    {
        $this->v = new Vector(array(1,3,2,4));
    }

    protected function tearDown()
    {
        $this->v = null;
    }


	public function testStaticZeros() {
        $v = Vector::zeros(3);
        $this->assertCount(3, $v);
        $this->assertEquals(0, $v[0]);
        $this->assertEquals(0, $v[1]);
        $this->assertEquals(0, $v[2]);
	}

    public function testStaticOnes() {
        $v = Vector::ones(3);
        $this->assertCount(3, $v);
        $this->assertEquals(1, $v[0]);
        $this->assertEquals(1, $v[1]);
        $this->assertEquals(1, $v[2]);
    }

    public function testStaticFill() {
        $this->assertEquals(array(2,2,2,2), Vector::fill(2,4)->values());
    }


	public function testValues() {
		$this->assertEquals(array(1,3,2,4), $this->v->values());
	}


	public function toString($format = '%6.2f', $columnSeparator = " ") {
        $expected =
            '     0      1      2      3' . PHP_EOL .
            '  1.00   3.00   2.00   4.00' . PHP_EOL;
        ;
        $this->assertEquals($expected, $this->v->toString());
	}


	public function testOffsetExists() {
        $this->assertFalse(isset($this->v[-1]));
		$this->assertTrue(isset($this->v[0]));
        $this->assertTrue(isset($this->v[1]));
        $this->assertTrue(isset($this->v[2]));
        $this->assertTrue(isset($this->v[3]));
        $this->assertFalse(isset($this->v[4]));
	}

	public function testOffsetGetForExistingValues() {
        $this->assertEquals(1, $this->v[0]);
        $this->assertEquals(3, $this->v[1]);
        $this->assertEquals(2, $this->v[2]);
        $this->assertEquals(4, $this->v[3]);
	}

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOffsetGetForUnderflowThrows() {
        $this->v[-1];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOffsetGetForOverflowThrows() {
        $this->v[4];
    }

    public function testOffsetSet() {
        $this->v[3] = 12;
        $this->assertEquals(12, $this->v[3]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOffsetSetThrowsForOverflow() {
        $this->v[4] = 12;
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testOffsetUnsetThowsBadMethodCallException() {
		unset($this->v[0]);
	}


	public function testCount() {
		$this->assertCount(4, $this->v);
	}

    public function testIteration() {
        $keys = array();
        $values = array();
        foreach ($this->v as $k=>$v) {
            $keys[] = $k;
            $values[] = $v;
        }
        $this->assertEquals(array(0,1,2,3), $keys);
        $this->assertEquals(array(1,3,2,4), $values);
    }

    public function testMap() {
        $keys = array();
        $values = array();
        $result = $this->v->map(
            function ($v, $k) use (&$keys, &$values) {
                $keys[] = $k;
                $values[] = $v;
                return $v * $k;
            }
        );
        $this->assertEquals(array(0,1,2,3), $keys);
        $this->assertEquals(array(1,3,2,4), $values);
        $this->assertEquals(array(0,3,4,12), $result);
    }

    public function testCoMap() {
        $v2 = Vector::create(5,3,2,1);
        $keys = array();
        $values = array();
        $values2 = array();

        $result = $this->v->coMap(
            $v2,
            function ($v, $v2, $k) use (&$keys, &$values, &$values2) {
                $keys[] = $k;
                $values[] = $v;
                $values2[] = $v2;
                return $v * $v2 + $k;
            }
        );
        $this->assertEquals(array(0,1,2,3), $keys);
        $this->assertEquals(array(1,3,2,4), $values);
        $this->assertEquals(array(5,3,2,1), $values2);
        $this->assertEquals(array(5,10,6,7), $result);
    }


    public function testScale() {
        $this->assertEquals(array(2,6,4,8), $this->v->scale(2)->values());
        $this->assertEquals(array(-3,-9,-6,-12), $this->v->scale(-3)->values());
    }

    public function testNorm() {
        $this->assertEquals(5, Vector::create(array(3,4))->norm(), '', 0.0001);
        $this->assertEquals(sqrt(1*1 + 3*3 + 2*2 + 4*4), $this->v->norm(), 0.0001);
    }

    public function testNormalized() {
        $f = 1.0 / sqrt(1*1 + 3*3 + 2*2 + 4*4);
        $this->assertEquals(array(1 * $f, 3 * $f, 2 * $f, 4 * $f), $this->v->normalized()->values(), '', 0.0001);
	}

	public function testCosine() {
	    $this->assertEquals(0 , Vector::create(0,1)->cosine(Vector::create(1,0)));
        $this->assertEquals(0 , Vector::create(0,1)->cosine(Vector::create(2,0)));
        $this->assertEquals(1 , Vector::create(0,1)->cosine(Vector::create(0,1)));
        $this->assertEquals(1 , Vector::create(0,1)->cosine(Vector::create(0,2)));
        $this->assertEquals(-1 , Vector::create(0,1)->cosine(Vector::create(0,-1)));
        $this->assertEquals(cos(M_PI/4) , Vector::create(0,1)->cosine(Vector::create(1,1)));
        $this->assertEquals(-cos(M_PI/4) , Vector::create(0,1)->cosine(Vector::create(1,-1)));
    }


	public function testDot() {
		$this->assertEquals(0+0+9,Vector::create(0,2,3)->dot(Vector::create(2,0,3)));
        $this->assertEquals(5+8+9,Vector::create(1,2,3)->dot(Vector::create(5,4,3)));
	}



	public function testAdd() {
		$this->assertEquals(array(6,6,6), Vector::create(1,2,3)->add(Vector::create(5,4,3))->values());
	}

    public function testSubtract() {
        $this->assertEquals(array(-4,-2,0), Vector::create(1,2,3)->subtract(Vector::create(5,4,3))->values());
    }


	public function testInvert() {
		$this->assertEquals(array(-1,-3,-2,-4), $this->v->invert()->values());
	}

/*

	public function map($function) {
		$result = array();
		for ($i=0; $i < $this->m; $i++) {
			$result[] = $function($this->values[$i], $i);
		}
		return $result;
	}


	public function coMap(Vector $b, $function) {
		$result = array();
		for ($i=0; $i < $this->m; $i++) {
			$result[] = $function($this->values[$i], $b->values[$i],$i);
		}
		return $result;
	}

*/
}

?>

<?php

namespace LinearAlgebra;


class ThreeMatrixFactorisation  {

	/**
	 * @var Matrix
	 */
	protected $u;

    /**
     * @var Matrix
     */
    protected $s;

    /**
     * @var Matrix
     */
    protected $v;

    /**
     * @param Matrix $u
     * @param Matrix $s
     * @param Matrix $v
     * @throws \InvalidArgumentException
     */
    public function __construct(Matrix $u, Matrix $s, Matrix $v)
    {
        if ($u->n() != $s->m()) {
            throw new \InvalidArgumentException('Matrix s incompatible: u->n:'.$u->n() .' != s->m' .$s->m());
        }
        if ($s->n() != $v->m()) {
            throw new \InvalidArgumentException('Matrix v incompatible: s->n:'.$s->n() .' != v->m:' .$v->m());
        }
        $this->u = $u;
        $this->s = $s;
        $this->v = $v;
    }

    public function n() {
        return $this->u->n();
    }

    public function m() {
        return $this->v->m();
    }


    /**
     * @return Matrix
     */
    public function u() {
        return $this->u;
    }

    /**
     * @return Matrix
     */
    public function s() {
        return $this->s;
    }

    /**
     * @return Matrix
     */
    public function v() {
        return $this->v;
    }

    /**
     * @param int $dimensions
     * @return ThreeMatrixFactorisation
     */
    public function truncate($dimensions = 0) {
        $dimensions = $dimensions ?: $this->s->m();
        return new ThreeMatrixFactorisation(
            $this->u->squared($dimensions),
            $this->s->squared($dimensions),
            $this->v->resized($dimensions, null)
        );
    }

    /**
     * @return Matrix
     */
    public function multiply() {
        return $this->u->multiply($this->s)->multiply($this->v);
    }

    /**
     * @return Matrix
     */
    public function us() {
        return $this->u->multiply($this->s);
    }

    /**
     * @return Matrix
     */
    public function sv() {
        return $this->s->multiply($this->v);
    }

}

?>

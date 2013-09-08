<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jk
 * Date: 20.08.13
 * Time: 21:37
 * To change this template use File | Settings | File Templates.
 */
namespace Statistics\ClusterAlgorithm\Hierarchical;
/**
 * Class Clusters
 * A typesafe collection of clusters
 */
class Clusters extends \TermDocumentTools\Collection {

    public function append(Cluster $value) {
        return parent::add($value);
    }

    protected function checkValue($value)
    {
        if (!$value instanceof Cluster) {
            throw new \InvalidArgumentException($value . ' is not of type Cluster');
        }
    }

    public function __toString() {
        $string = '<ul>';
        foreach ($this as $v) {
            $string .= '<li>'.$v.'</li>';
        }
        $string .= '</ul>';
        return $string;
    }
}
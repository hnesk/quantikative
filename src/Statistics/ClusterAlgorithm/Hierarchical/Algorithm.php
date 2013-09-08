<?php
namespace Statistics\ClusterAlgorithm\Hierarchical;

use Statistics\ClusterAlgorithm\AlgorithmInterface;

class Algorithm implements AlgorithmInterface{

    /**
     * @var \Closure
     */
    protected $distanceCallback;

    /**
     * @var \Closure
     */
    protected $mergeCallback;

    /**
     * @var array
     */
    protected $cache;

    /**
     * @param $distanceCallback
     * @param $mergeCallback
     * @throws \InvalidArgumentException
     */
    public function __construct($distanceCallback, $mergeCallback) {
        if (!is_callable($distanceCallback)) {
            throw new \InvalidArgumentException('$distanceCallback needs to be callable');
        }
        if (!is_callable($mergeCallback)) {
            throw new \InvalidArgumentException('$mergeCallback needs to be callable');
        }
        $this->distanceCallback = $distanceCallback;
        $this->mergeCallback = $mergeCallback;
    }

    public function calculate($data) {
        $this->cache = array();

        $items = new Clusters();
        foreach ($data as $index => $item) {
            $items->append(new Item($index, $item));
        }

        $merge = $this->mergeCallback;

        while (count($items) != 1) {
            list($i1, $i2) = $this->getClosestItemsIndices($items);
            $item1 = $items[$i1];
            $item2 = $items[$i2];

            unset($items[$i1]);
            unset($items[$i2]);

            $items->append(new Pair($merge($item1->getValue(), $item2->getValue()), $item1, $item2));
            $items = $items->valueCollection();

        }

        return $items;
    }

    public function getSortedPermutation($clusters) {
        $result = array();
        foreach ($clusters as $cluster) {
            $result = array_merge($result, $cluster->getIndex());
        }
        return $result;
    }

    /**
     * @param $items
     * @return array
     */
    public function getClosestItemsIndices($items) {
        $minimumDistance = INF;
        $min1 = -1;
        $min2 = -1;
        $length = count($items);
        for ($i = 0; $i < $length; $i++) {
            $itemI = $items[$i];
            for ($j = $length-1; $j > $i; $j--) {
                $currentDistance = $this->getCachedDistance($itemI, $items[$j]);

                if ($currentDistance < $minimumDistance) {
                    $minimumDistance = $currentDistance;
                    $min1 = $i;
                    $min2 = $j;
                }
            }
        }

        return array($min1, $min2);
    }

    /**
     * @param Cluster $itemI
     * @param Cluster $itemJ
     * @return float
     */
    protected function getCachedDistance($itemI, $itemJ) {
        static $cache = array();
        $key =  $itemI->getCacheKey().'|'.$itemJ->getCacheKey();
        if (isset($cache[$key])) {
            return $cache[$key];
        } else {
            $currentDistance = call_user_func($this->distanceCallback, $itemI->getValue(), $itemJ->getValue());
            return $cache[$key] = $currentDistance;
        }

    }
}
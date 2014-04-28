<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jk
 * Date: 02.07.13
 * Time: 20:08
 * To change this template use File | Settings | File Templates.
 */

namespace Accidents;

use Silex\Application\TwigTrait;
use Symfony\Component\HttpFoundation\Response;
use Wahlomat\Application;
use Symfony\Component\HttpFoundation\Request;


class Controller {

    public function index(Application $app) {
        return $app->render(
            'accidents/index.html.twig',
            array()
        );
    }

    public function test(Application $app) {
        $data = $this->getData(new \DateTimeImmutable('-1 month'), new \DateTimeImmutable('now'));

        return $app->render(
            'accidents/test.html.twig',
            $data
        );
    }

    public function testData(Application $app) {
        $data = $this->getData(new \DateTimeImmutable('-1 month'), new \DateTimeImmutable('now'));
        $data = $this->groupData($data, function ($data) {
            return floor($data['ts'] / (24*3600)) * 24*3600;
        });
        return new Response(json_encode($data), 200, array('Content-Type' => 'application/json'));
    }

    /**
     * @param array $records
     * @param \Closure $by
     * @return array
     */
    protected function groupData($records, $by) {
        $result = array();
        foreach ($records as $id => $record) {
            $key = $by($record);
            if (!isset($result[$key])) {
                $result[$key] = array(
                    'key' => $key,
                    'data' => array()
                );
            }
            $result[$key]['data'][$id] = $record;
        }
        return $result;
    }

    protected function getData(\DateTimeInterface $start, \DateTimeInterface $end) {
        $data = array();
        for ($i = 0 ; $i < 1000; $i++) {
            $ts = rand($start->getTimestamp(), $end->getTimestamp());
            $data[$ts] = array(
                'ts' => $ts,
                'group' => rand(0,5)
            );
        }
        ksort($data);
        return $data;
    }

}
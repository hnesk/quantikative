<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jk
 * Date: 02.07.13
 * Time: 20:08
 * To change this template use File | Settings | File Templates.
 */

namespace Wahlomat;

use LinearAlgebra\Vector;
use Silex\Application\TwigTrait;
use Statistics\ClusterAlgorithm\AlgorithmInterface;
use Statistics\ClusterAlgorithm\Hierarchical\Algorithm;
use TermDocumentTools\ArrayFilter;
use TermDocumentTools\ArrayKeyFilter;
use TermDocumentTools\Document;
use TermDocumentTools\Feature;
use TermDocumentTools\LoaderInterface;
use TermDocumentTools\Term;
use TermDocumentTools\TermDocumentMatrix;
use Wahlomat\Application;
use Symfony\Component\HttpFoundation\Request;
use Wahlomat\Model\JavascriptLoader;


class Controller {

    /**
     * @var LoaderInterface
     */
    protected $loader;

    /**
     * @param LoaderInterface $loader
     */
    public function __construct(LoaderInterface $loader = null) {
        $this->loader = $loader ?: new JavascriptLoader(BASE_DIR.'/data/');
    }

    public function index($dataSet, Application $app) {
        return $app->render(
            'index.html.twig',
            array()
        );
    }

    public function view($dataSet, Request $request, Application $app) {
        $tdm = $this->getMatrix($dataSet);
        $reasonMatrix = $this->getMatrix($dataSet, LoaderInterface::DATA_TYPE_REASON);
        //$tdm = $tdm->filterDocuments(ArrayFilter::create('0,1,2,3,4,5,6,7,8,9,10,20,27')->toClosure());
        //$tdm = $this->clusterMatrix($tdm);
        return $app->render(
            'view.html.twig',
            array(
                'tdm' => $tdm,
                'reasonMatrix' => $reasonMatrix
            )
        );
    }



    public function eigen($dataSet, Request $request, Application $app) {
        $dataSets = $this->loader->getDataSets();
        $tdm = $this->getMatrix($dataSet);
        $tdm = $tdm->filterDocuments(ArrayKeyFilter::create($request->get('party'))->toClosure());
        $tdm = $tdm->filterTerms(ArrayKeyFilter::create($request->get('term'))->toClosure());
        return $app->render(
            'eigen.html.twig',
            array(
                'tdm' => $tdm,
                'dataSets' => $dataSets,
                'dataSet' => $dataSet,
            )
        );
    }

    public function parties($dataSet, Request $request, Application $app) {
        $tdm = $this->getMatrix($dataSet);
        //$tdm = $tdm->filterDocuments(ArrayFilter::create('0,1,2,3,4,5,6,7,8,9,10,20,27')->toClosure());
        $tdm = $this->clusterMatrix($tdm);
        $ddm = $tdm->calculateDocumentMatrix();

        return $app->render(
            'parties.html.twig',
            array(
                'ddm' => $ddm,
                'terms' => $tdm->getTerms(),
                'answers' => $tdm->getValues(),
                'plain' => $ddm->toCSV()
            )
        );
    }




    public function json($dataSet, Request $request, Application $app) {
        $tdm = $this->getMatrix($dataSet);

        $tdm = $tdm->filterDocuments(ArrayKeyFilter::create($request->get('party'))->toClosure());
        $tdm = $tdm->filterTerms(ArrayKeyFilter::create($request->get('term'))->toClosure());

        /** @var TermDocumentMatrix $tdm */
        $features = $tdm->calculateFactorMatrix();
        /** @var Feature $xFeature */
        $xFeature = $features[0];
        /** @var Feature $yFeature */
        $yFeature = $features[1];

        $data = array();

        foreach ($tdm->getDocuments() as $i => $party) {
            /** @var Document $party */
            $data[] = (object)array(
                'type' => 'party',
                'index' => 'party'.$party->name(),
                'x' => $xFeature->getDocumentValue($i),
                'y' => $yFeature->getDocumentValue($i),
                'short' => $party->name(),
                'long' => $party->description(),
            );
        }


        foreach ($tdm->getTerms() as $i => $term) {
            /** @var Term $term */
            $data[] = (object)array(
                'type' => 'term',
                'index' => 'term'.$term->name(),
                'x' => $xFeature->getTermValue($i),
                'y' => $yFeature->getTermValue($i),
                'short' => $term->name(),
                'long' => $term->description()
            );
        }

        $result = $tdm->toObject();
        $result->plot = $data;
        return $app->json($result);
    }


    /**
     * @param string $source A directory below data, containing a data.js file
     * @param $dataType
     * @return \TermDocumentTools\TermDocumentMatrix
     */
    public function getMatrix($source = 'bundestagswahl2013', $dataType = LoaderInterface::DATA_TYPE_VALUE) {
        return $this->loader->load($source, 0, $dataType);
    }

    protected function clusterMatrix(TermDocumentMatrix $tdm) {
        $clusterAlgorithm = $this->getClusterAlgorithm();
        $cluster = $clusterAlgorithm->calculate($tdm->getValues());
        $order = $clusterAlgorithm->getSortedPermutation($cluster);
        $tdm = $tdm->permuteDocuments($order);

        $cluster = $clusterAlgorithm->calculate($tdm->getValues()->transpose());
        $order = $clusterAlgorithm->getSortedPermutation($cluster);

        $tdm = $tdm->permuteTerms($order);

        return $tdm;
    }


    /**
     * @return AlgorithmInterface
     */
    protected function getClusterAlgorithm() {
        return new Algorithm(
            function (Vector $v1, Vector $v2) { return $v1->distance($v2); },
            function (Vector $v1, Vector $v2) { return $v1->add($v2)->scale(0.5); }
        );
    }




}
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jk
 * Date: 02.07.13
 * Time: 20:08
 * To change this template use File | Settings | File Templates.
 */

namespace Wahlomat;

use Silex\Application\TwigTrait;
use TermDocumentTools\ArrayKeyFilter;
use TermDocumentTools\Document;
use TermDocumentTools\Feature;
use TermDocumentTools\Term;
use TermDocumentTools\TermDocumentMatrix;
use Wahlomat\Application;
use Symfony\Component\HttpFoundation\Request;
use Wahlomat\Model\JavascriptLoader;
use Wahlomat\Model\LoaderInterface;

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


    public function index($dataSet, Request $request, Application $app) {
        $dataSets = $this->loader->getDataSets();
        $tdm = $this->getMatrix($dataSet);
        $tdm = $tdm->filterDocuments(new ArrayKeyFilter($request->get('party')));
        $tdm = $tdm->filterTerms(new ArrayKeyFilter($request->get('term')));
        return $app->render(
            'index.html.twig',
            array(
                'tdm' => $tdm,
                'dataSets' => $dataSets,
                'dataSet' => $dataSet,
            )
        );
    }

    public function json($dataSet, Request $request, Application $app) {
        $tdm = $this->getMatrix($dataSet);

        $tdm = $tdm->filterDocuments(new ArrayKeyFilter($request->get('party')));
        $tdm = $tdm->filterTerms(new ArrayKeyFilter($request->get('term')));

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
                'index' => 'party'.$party->id(),
                'x' => $xFeature->getDocumentValue($party->id()),
                'y' => $yFeature->getDocumentValue($party->id()),
                'short' => $party->name(),
                'long' => $party->description(),
            );
        }


        foreach ($tdm->getTerms() as $i => $term) {
            /** @var Term $term */
            $data[] = (object)array(
                'type' => 'term',
                'index' => 'term'.$term->id(),
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
     * @throws \InvalidArgumentException
     * @return \TermDocumentTools\TermDocumentMatrix
     */
    public function getMatrix($source = 'schleswigholstein2012') {
        return $this->loader->load($source);
    }



}
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
use TermDocumentTools\Term;
use Wahlomat\Application;
use Symfony\Component\HttpFoundation\Request;
use Wahlomat\Model\JavascriptLoader;

class Controller {


    public function index(Request $request, Application $app) {
        $tdm = $this->getMatrix();
        $tdm = $tdm->filterDocuments(new ArrayKeyFilter($request->get('partyName')));
        $tdm = $tdm->filterTerms(new ArrayKeyFilter($request->get('termName')));
        return $app->render('index.html');
        return
            '<pre>'.$tdm->getValues()->transpose()->toString('%6d').'</pre>'.
            '<pre>'.$tdm->getDocuments().'</pre>'.
            '<pre>'.$tdm->getTerms().'</pre>'
        ;
    }

    public function json(Request $request, Application $app) {
        $tdm = $this->getMatrix();
        $tdm = $tdm->filterDocuments(new ArrayKeyFilter($request->get('partyName')));
        $tdm = $tdm->filterTerms(new ArrayKeyFilter($request->get('termName')));

        $features = $tdm->calculateFactorMatrix();

        $xFeature = 0;
        $yFeature = 1;

        $data = array();

        foreach ($tdm->getDocuments() as $i => $party) {
            /** @var Document $party */
            $data[] = (object)array(
                'type' => 'party',
                'index' => 'party'.$party->id(),
                'x' => $features[$xFeature]->party[$i],
                'y' => $features[$yFeature]->party[$i],
                'short' => $party->name(),
                'long' => $party->description(),
            );
        }


        foreach ($tdm->getTerms() as $i => $term) {
            /** @var Term $term */
            $data[] = (object)array(
                'type' => 'term',
                'index' => 'term'.$term->id(),
                'x' => $features[$xFeature]->thesis[$i],
                'y' => $features[$yFeature]->thesis[$i],
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
        return JavascriptLoader::load(BASE_DIR.'/data/'.$source.'/data.js');
    }



}
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
use Wahlomat\Application;
use Symfony\Component\HttpFoundation\Request;
use Wahlomat\Model\JavascriptLoader;

class StaticController {

    public function index(Application $app) {
        return $app->redirect($app->url('wahlomat', array('dataSet' => 'europawahl2014')));
    }

    public function imprint(Application $app) {
        return $app->render('imprint.html.twig');
    }

    public function about(Application $app) {
        return $app->render('about.html.twig');
    }

}
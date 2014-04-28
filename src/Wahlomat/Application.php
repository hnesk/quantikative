<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jk
 * Date: 02.07.13
 * Time: 20:08
 * To change this template use File | Settings | File Templates.
 */

namespace Wahlomat;

use Silex\Application as BaseApplication;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use SilexAssetic\AsseticServiceProvider;

class Application extends BaseApplication {

    use BaseApplication\TwigTrait;
    use BaseApplication\UrlGeneratorTrait;

    public function __construct(array $values = array()) {
        parent::__construct($values);
        $this->registerLogging();
        $this->registerRoutes();
        $this->registerTemplating();
        //$this->registerAssets();
    }


    protected function registerRoutes() {
        $defaultDataSet = 'europawahl2014';
        $this->register(new UrlGeneratorServiceProvider());
        $this->get('/', 'Wahlomat\StaticController::index')->bind('index');
        $this->get('/wahlomat/{dataSet}/', 'Wahlomat\Controller::index')->value('dataSet',$defaultDataSet)->bind('wahlomat');
        $this->get('/wahlomat/{dataSet}/index/', 'Wahlomat\Controller::view')->value('dataSet',$defaultDataSet)->bind('wahlomat_view');
        $this->get('/wahlomat/{dataSet}/eigen/', 'Wahlomat\Controller::eigen')->value('dataSet',$defaultDataSet)->bind('wahlomat_eigen');
        $this->get('/wahlomat/{dataSet}/parties/', 'Wahlomat\Controller::parties')->value('dataSet',$defaultDataSet)->bind('wahlomat_parties');
        $this->get('/wahlomat/{dataSet}/partyForce/', 'Wahlomat\Controller::partyForce')->value('dataSet',$defaultDataSet)->bind('wahlomat_partyForce');
        $this->get('/wahlomat/{dataSet}/api/', 'Wahlomat\Controller::json')->bind('wahlomat_api');
        $this->get('/wahlomat/{dataSet}/spectrum/', 'Wahlomat\Controller::spectrum')->value('dataSet',$defaultDataSet)->bind('wahlomat_spectrum');
        $this->get('/meta/kontakt/', 'Wahlomat\StaticController::imprint')->bind('imprint');

        #$this->get('/unfaelle-bielefeld/', 'Accidents\Controller::index')->bind('accidents');
        #$this->get('/test/', 'Accidents\Controller::test')->bind('test');
        #$this->get('/testdata/', 'Accidents\Controller::testData')->bind('testdata');

    }


    protected function registerLogging() {
        $this['debug'] = true;
        $this->register(
            new MonologServiceProvider(),
            array(
                'monolog.logfile' => BASE_DIR.'/log/development.log',
                'monolog.name' => 'Wahlomat'
            )
        );
    }

    protected function registerTemplating() {
        $this->register(
            new TwigServiceProvider(),
            array(
                'twig.path' => BASE_DIR.'/views',
            )
        );

        $app = &$this;
        $this->before(
            function (Request $request) use ($app) {
                /** @noinspection PhpUndefinedMethodInspection */
                $app['twig']->addGlobal('layout', $app['twig']->loadTemplate('layout.html.twig'));
                /** @noinspection PhpUndefinedMethodInspection */
                $app['twig']->addGlobal('route', $request->attributes->get('_route'));
                /** @noinspection PhpUndefinedMethodInspection */
                $app['twig']->addGlobal('dataSet', $request->attributes->get('dataSet'));
                /** @noinspection PhpUndefinedMethodInspection */
                $app['twig']->addGlobal('base', $request->getBaseUrl());
            }
        );

    }


    protected function registerAssets() {
        $this->register(new AsseticServiceProvider());
        $this['assetic.path_to_web'] = BASE_DIR.'/web/assets/';
    }
}
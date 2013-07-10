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

class Application extends BaseApplication {

    use BaseApplication\TwigTrait;

    public function __construct(array $values = array()) {
        parent::__construct($values);
        $this->registerLogging();
        $this->registerRoutes();
        $this->registerTemplating();

    }


    protected function registerRoutes() {
        $this->register(new UrlGeneratorServiceProvider());

        $this->get('/wahlomat/{dataSet}', 'Wahlomat\Controller::index')->value('dataSet','bundestagswahl2009')->bind('wahlomat');
        $this->get('/wahlomat/{dataSet}/api/', 'Wahlomat\Controller::json')->bind('wahlomat_api');
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
            function () use ($app) {
                $app['twig']->addGlobal('layout', $app['twig']->loadTemplate('layout.html.twig'));
            }
        );

    }
}
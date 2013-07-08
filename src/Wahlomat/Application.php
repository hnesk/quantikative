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
use Symfony\Component\HttpFoundation\Request;

class Application extends BaseApplication {

    use BaseApplication\TwigTrait;

    public function __construct(array $values = array()) {
        parent::__construct($values);

        $app = &$this;

        $this['debug'] = true;
        $this->register(new MonologServiceProvider(), array(
            'monolog.logfile' => BASE_DIR.'/log/development.log',
            'monolog.name' => 'Wahlomat'
        ));
        $this->register(new TwigServiceProvider(), array(
                'twig.path' => BASE_DIR.'/views',
        ));
        $this->before(function () use ($app) {
            $app['twig']->addGlobal('layout', $app['twig']->loadTemplate('layout.html'));
        });

        $this->get('/', 'Wahlomat\Controller::index');
        $this->get('/api/', 'Wahlomat\Controller::json');
    }
}
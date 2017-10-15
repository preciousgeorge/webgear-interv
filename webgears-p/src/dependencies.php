<?php
// DIC configuration
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
use Monolog\Processor\UidProcessor;
use Monolog\Handler\StreamHandler;
use Slim\Views\PhpRenderer;

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new UidProcessor());
    $logger->pushHandler(new StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$settings = $container->get('settings');
$capsule = new Manager;
$capsule->addConnection($settings['db']);
$capsule->setEventDispatcher(new Dispatcher(new Container));
$capsule->bootEloquent();


$container['db'] = function ($c) {
    $capsule = new Manager;
    $capsule->addConnection($c['settings']['db']);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

$container['view'] = function ($container) {
    
      $view = new \Slim\Views\Twig(__DIR__ . '/../templates', [
    
          'cache' => false,
    
      ]);
    
      $view->addExtension(new \Slim\Views\TwigExtension(
    
          $container->router,
    
          $container->request->getUri()
    
      ));
    
      return $view;
    
   };



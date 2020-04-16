<?php
namespace App\Services;

use Illuminate\Routing\ResourceRegistrar;

class RouterExtended extends ResourceRegistrar
{
  protected $resourceDefaults = ['index', 'store', 'show', 'update', 'destroy', 'search'];

  protected function addResourceSearch($name, $base, $controller, $options)
  {
      $uri    = $this->getResourceUri($name).'/search';
      $action = $this->getResourceAction($name, $controller, 'search', $options);
      return $this->router->post($uri, $action);
  }
}
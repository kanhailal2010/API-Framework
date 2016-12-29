<?php if(!defined('BASE_URL')) exit('Direct Script Access Not Allowed');

class App
{
  public $uri;
  public $autoload;
  private $app;
  private $controller;
  private $method;
  private $params;
  public $debug = 0;

  function __construct($autoload) {
    $this->autoload = $autoload;
    $this->uri      = $autoload->sys->uri;
  }

  /**
   * This method will parse the url and determine the controller, its method and its parameters if any.
   * Then we will include the controller file.
   * Then initialize the controller
   * then run its method
   * @return [nothing]
   */
  function handle_request() {
    $this->controller = $this->uri->url["controller"];
    $this->method = restful($this->uri->url["method"]);
    $this->params = $this->uri->url["params"];

    if($this->debug) {
      dbug("The controller ",$this->controller);
      dbug("The method ",$this->method);
      dbug("The params ",$this->params);
    }

    // set file location
    $file = CONTROLLER_FOLDER.'/'.$this->controller.'.php';

    // check if the class file (controller) exist , else throw error
    if(!file_exists($file))
    {
      header("Content-Type: application/json");
      echo json_encode(["status" => "fail", "response" => "The requested API does not exist."]);
      die( header("HTTP/1.0 404 Oops requested url does not exist or has moved ") );
    }

    // include the class file (controller)
    require_once($file);

    $controller = $this->controller;

    // initialize the controller
    $this->app = new $controller( $this->autoload );

    // if the method exist in this controller
    if( method_exists( $this->app, $this->method ) ) :
      $this->app->{$this->method}( $this->params);
    else :
      header("Content-Type: application/json");
      echo json_encode(["status" => "fail", "response" => "The requested API does not exist."]);
      header("HTTP/1.0 404  Oops requested url does not exist  ");
    endif;

  }
}
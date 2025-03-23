<?php
namespace Core;
use Core\Application;

/**
 * This is the parent Controller class.  It describes functions that are 
 * available to all classes that extends this Controller class.
 */
#[\AllowDynamicProperties]
class Controller extends Application {
    protected $_action;
    protected $_controller;
    public $request;
    public $view;

    /**
     * Constructor for the parent Controller class.  This constructor gets 
     * called when an instance of the child class is instantiated.
     *
     * @param string $controller The name of the controller obtained while 
     * parsing the URL.
     * @param string $action The name of the action specified in the path of 
     * the URL.
     */
    public function __construct(string $controller, string $action) {
        parent::__construct();
        $this->_controller = $controller;
        $this->_action = $action;
        $this->request = new Input();
        $this->view = new View();
        $this->onConstruct();
    }

    /**
     * Sample jsonResponse for supporting AJAX requests.
     *
     * @param mixed $res The JSON response.
     * @return void
     */
    public function jsonResponse(mixed $res): void {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
        echo json_encode($res);
        exit;
    }

    /**
     * Function implemented by child model classes when models are 
     * instantiated.
     *
     * @return void
     */
    public function onConstruct(): void {}
}
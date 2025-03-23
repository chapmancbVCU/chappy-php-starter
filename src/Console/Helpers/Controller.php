<?php
namespace Console\Helpers;

/**
 * Helper class for controller related console commands.
 */
class Controller {
    /**
     * The default template for a new controller.
     *
     * @param string $controllerName The name of the controller.
     * @param string $layout The layout to be set.
     * @return string The contents for a new controller.
     */
    public static function defaultTemplate(string $controllerName, string $layout): string {
        return '<?php
namespace App\Controllers;
use Core\Controller;

/**
 * Undocumented class
 */
class '.$controllerName.'Controller extends Controller {
    /**
     * Runs when the object is constructed.
     *
     * @return void
     */
    public function onConstruct(): void{
        $this->view->setLayout(\''.$layout.'\');
    }
}
';
    }

    /**
     * The template that contains additional useful functions for a controller.
     *
     * @param string $controllerName The name of the controller.
     * @param string $layout The layout to be set.
     * @return string The contents for a new controller.
     */
    public static function resourceTemplate(string $controllerName, string $layout): string {
        return '<?php
namespace App\Controllers;
use Core\Controller;

/**
 * Undocumented class
 */
class '.$controllerName.'Controller extends Controller {
    /**
     * Runs when the object is constructed.
     *
     * @return void
     */
    public function onConstruct(): void{
        $this->view->setLayout(\''.$layout.'\');
    }

    public function indexAction(): void {
        //
    }
    
    public function addAction(): void {
        //
    }

    public function deleteAction(): void {
        //
    }

    public function editAction(): void {
        //
    }

    public function updateAction(): void {
        //
    }
}
';
    }
}
<?php
namespace App\Controllers;
use Core\Controller;
use Core\Services\AuthService;
use Core\Lib\Http\JsonResponse;
/**
 * Implements support for our Home controller.  Functions found in this class 
 * will support tasks related to the home page.
 */
class HomeController extends Controller {
    use JsonResponse;
    /** 
     * The default action for this controller.  It performs rendering of this 
     * site's home page.
     * 
     * @return void
     */
    public function indexAction(): void {
        // Toggle comments to enable React.js view.
        // $this->view->render('home.index');

        $user = AuthService::currentUser();
        $props = ['user' => $user ?? 'Guest'];
        $this->view->renderJSX('home.Index', $props);
    }

    /**
     * Demonstration for an Ajax request.
     *
     * @return void
     */
    public function testAjaxAction(): void {
        $resp = ['success'=>true,'data'=>['id'=>23,'name'=>'Hello World','favorite_food'=>'bread']];
        $this->jsonResponse($resp);
    }
}
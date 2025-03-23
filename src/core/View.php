<?php
namespace Core;
use stdClass;
use Exception;
use Core\Lib\Utilities\ArraySet;
use Core\Lib\Utilities\Env;

/**
 * Handles operations related to views and its content.
 */
class View extends stdClass {
    protected $_body;
    protected $_content = [];
    protected $_currentBuffer;
    protected $_head;
    protected $_layout;
    protected $_outputBuffer;
    protected $_siteTitle;
    
    /**
     * Default constructor.
     */
    public function __construct() {
        $this->_layout = Env::get('DEFAULT_LAYOUT', 'default'); // Default layout: 'default'
        $this->_siteTitle = Env::get('SITE_TITLE', 'My Website'); // Default site title
    }
    
    /**
     * Includes a component into a view.
     *
     * @param string $component The name of the component.
     * @return void
     */
    public function component(string $component): void {
        include ROOT . DS . 'resources' . DS . 'views' . DS . 'components' . DS . $component . '.php';
    }

    /**
     * The content of the page.  The two types are head and body.  If 
     * necessary, we can implement additional types of content.
     *
     * @param string $type The type of content we want to render.
     * @return mixed The type of content we want to render.  If it is not 
     * a known type of content we return false;
     */
    public function content(string $type): mixed {
        return ArraySet::make($this->_content)->get($type)->result() ?? false;
    }

    /**
     * Sets the end for a particular section of content.  When called it 
     * takes _outputBuffer, cleans it, and outputs it to the screen.  In the 
     * absence of a previous call to the start() function a message requesting 
     * you to call start() is displayed.
     *
     * @return void
     */
    public function end(): void {
        if(!empty($this->_currentBuffer)){
            $this->_content[$this->_currentBuffer] = ob_get_clean();
            $this->_currentBuffer = null;
        } else {
            die('You must first run the start method.');
        }
    }

    /**
     * Inserts a partial into another partial.
     *
     * @param string $path Path to view.  Example: auth/register
     * @return void
     */
    public function insertView(string $path): void {
        include ROOT . DS . 'resources' . DS . 'views' . DS . $path . '.php';
    }

    /**
     * Performs render operations for a particular view.
     * Example input: home/index.
     * 
     * @param string $viewName The name of the view we want to render.
     * @return void
     */
    public function render(string $viewName): void {
        // Separate into array to get view name.
        $viewArray = explode('/', $viewName);
        $viewString = implode(DS, $viewArray);

        // Include view and layouts
        if(file_exists(ROOT . DS . 'resources' . DS . 'views' . DS . $viewString . '.php')) {
            include(ROOT . DS . 'resources' . DS . 'views' . DS . $viewString . '.php');
            include(ROOT . DS . 'resources' . DS . 'views' . DS . 'layouts' . DS . $this->_layout . '.php');
        } else {
            throw new Exception('The view \"' . $viewName . '\" does not exist');
        }
    }

    /**
     * Sets the layout for the view.
     *
     * @param string $path The path for our view.
     * @return void
     */
    public function setLayout(string $path): void {
        $this->_layout = $path;
    }

    /**
     * Setter function for site title of current page.
     *
     * @param string $title The site title for a particular page.
     * @return void
     */
    public function setSiteTitle(string $title): void {
        $this->_siteTitle = $title;
    }

    /**
     * Getter function for current page's site title.
     *
     * @return string The site title for a particular page.
     */
    public function siteTitle(): string {
        return $this->_siteTitle;
    }

    /**
     * When called this function establishes the beginning for a section 
     * of content.  Anything between calls of this function and end() will be 
     * included in our view.
     *
     * @param string $type The name of the type of content we want to include 
     * in our view.
     * @return void
     */
    public function start(string $type): void {
        if(empty($type)) die('you must define a type');
        $this->_currentBuffer = $type;
        ob_start();
    } 
}
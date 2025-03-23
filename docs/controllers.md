<h1 style="font-size: 50px; text-align: center;">Controllers</h1>

## Table of contents
1. [Overview](#overview)
2. [Controller File](#controller-file)
3. [Example](#example)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
This framework supports controllers to manage the interactions between views and models.
<br>

## 2. Controller File <a id="controller-file"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Let's begin by creating a new controller using all available options and arguments:

```sh
php console make:controller Foo --layout=admin --resource
```

The first argument is the name and the first letter should always be upper case. It is used to create the name of the controller class. The --layout option is optional. If you do not use this option the layout is set to default. Finally, the --resource option creates boilerplate functions that you can use. The file created by running this command is shown below:

```php
namespace App\Controllers;
use Core\Controller;

/**
 * Undocumented class
 */
class FooController extends Controller {
    /**
     * Runs when the object is constructed.
     *
     * @return void
     */
    public function onConstruct(): void{
        $this->view->setLayout('admin');
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
```

The first function you see is the onConstruct. Any default settings you need for the controller is set here. It called by the Controller parent class' constructor. The value for the --layout option is used to set the layout in this function.

All of the other functions supports the default index action and the Create, Read, Update, and Delete (CRUD) operations. With this framework we require that functions that represent actions end with the word Action. Otherwise, the route function is unable to work correctly.
<br>

## 3. Example <a id="example"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
```php
public function addAction(): void {
    $contact = new Contacts();
    if($this->request->isPost()) {
        $this->request->csrfCheck();
        $contact->assign($this->request->get());
        $contact->user_id = Users::currentUser()->id;
        if($contact->save()) {
            Router::redirect('contacts');
        }
    }

    $this->view->contact = $contact;
    $this->view->displayErrors = $contact->getErrorMessages();
    // Set action for post.
    $this->view->postAction = Env::get('APP_DOMAIN', '/') . 'contacts' . DS . 'add';
    $this->view->render('contacts/add');
}
```

With this framework we standardize Create operations using a function called addAction. You can call it what ever you want as long as the function's name ends with Action.

We first begin with creating a new $contact object and we test if the request is a post. If a form has been rendered for the first time this block is skipped and the $view object is configured so that data is displayed correctly. Within this if statement we perform a Cross Site Request Forgery check and get data from the post request.

A quick note on configuring views by looking at line 12. You can think of this statement as being something familiar to passing props to blade.php views in Laravel. Within the view you don't have to use a @props directive. You, just have to use it. More on this in the views section of the user guide.

Finally, we save the data to the database and redirect the user. Note that the redirect has one word but render has 'contacts/add'. If you just enter contacts the controller assumes that the index action is wanted. 'contact' is also the name of the directory for all of the contact related views. The structure is setup as follows:

```php
$this->view->render('model_name/action_name');
```

The URL path equivalent you will see in the address bar is as follows: ```http://hostname/model_name/action_name```

This controller has a couple of ways to perform the Read operation from the CRUD paradigm. We perform reads in the indexAction and detailsAction. Let's go over the detailsAction function first.

```php
public function detailsAction(int $id): void {
    $contact = Contacts::findByIdAndUserId((int)$id, Users::currentUser()->id);

    // When user is not a contact we reroute to contacts index.
    if(!$contact) {
        Router::redirect('contacts');
    }

    $this->view->contact = $contact;
    $this->view->render('contacts/details');
}
```

For this action we are interested in displaying information for a particular contact. Note that this function has a parameter name id of type int. This function obtains this parameter from the URL path as shown here: `http://hostname/contacts/details/1`

Parsing out this path we see the expected identifier for contacts and the action name of details. The third part is interpreted as a parameter. In this case it is the id for the contact whose details we want to display. Next, we use the contact's model to obtain the record. This function on line 2 requires the id we initially passed along with the id of the current user.

Then we determine if the contact exists. If not, we redirect to the contacts view. Finally, we configure the view and render the selected contact's details. The indexAction is much simpler as shown below.

```php
public function indexAction(): void {
    $contacts = Contacts::findAllByUserId($this->currentUser->id, ['order'=>'lname, fname']);
    $this->view->contacts = $contacts;
    $this->view->render('contacts/index');
}
```

Nothing too complicated here but line 2 is noteworthy. Notice the second parameter in the findAllByUserId. We cover this more details in the model section but note we are using an associative array with a key of order and values `'lname, fname'`. This, framework supports setting parameters to configure query operations. In this example, we want to older our results by last name and then by first name.

Update operations is similar to the Create operation. There are some noteworthy differences to discuss so we have the code for the ContactsController's editAction shown below.

```php
public function editAction($id) {
    $contact = Contacts::findByIdAndUserId((int)$id, Users::currentUser()->id);

    // Check if contact exists
    if(!$contact) Router::redirect('contacts');
    if($this->request->isPost()) {
        $this->request->csrfCheck();
        $contact->assign($this->request->get(), Contacts::blackList);
        if($contact->save()) {
            Router::redirect('contacts');
        }
    }
    $this->view->displayErrors = $contact->getErrorMessages();
    $this->view->contact = $contact;
    $this->view->postAction = Env::get('APP_DOMAIN', '/') 'contacts' . DS . 'edit' . DS . $contact->id;
    $this->view->render('contacts/edit');
}
```

The first step is to get the contact record and make sure it's associated with the current user. The other difference is in the assign function on line 8. Notice the second parameter Contacts::blackList. This is an array that is found in the Contact's model and is used to protect certain database fields from being inadvertently updated.

The Delete operation is also very simple. In the example below you will find the findByIdAndUserId function again along with the setup of the confirmation Session Message.  More about Session Messages can be found [here](session_and_flash_messages).

```php
public function deleteAction(int $id): void {
    $contact = Contacts::findByIdAndUserId((int)$id, Users::currentUser()->id);
    if($contact) {
        $contact->delete();
        Session::addMessage('success', 'Contact has been deleted');
    }
    Router::redirect('contacts');
}
```
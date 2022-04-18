<?php

use Phalcon\Mvc\Controller;

class SignupController extends Controller
{

    public function IndexAction()
    {
        // return "Signup";
    }

    public function registerAction()
    {
        // return '<h1>registered</h1>';
        $user = new Users();

        //assign value from the form to $user
        $user->assign(
            $this->request->getPost(),
            [
                'name',
                'email'
            ]
        );

        $success = $user->save();

        $this->view->success = $success;

        if ($success) {
            $this->view->message = "<h3>Thank you for signing up</h3>";
        } else {
            $this->view->message = "some errors occured while signing you up: <br>" . implode("<br>", $user->getMessages());
        }
    }
}

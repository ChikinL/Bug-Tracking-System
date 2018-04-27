<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reset extends CI_Controller
{

    var $TPL;
    var $loggedin;

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }

    public function index()
    {
        
    }

    public function resetPassword($mode)
    {
        $this->loggedin = $this->userauth->validSessionExists();
//        if ($this->loggedin == TRUE) //hava loggined
//        {
        if ($mode == 'validated')// the url para is 'validated'
        {
            if ($this->userauth->isChangingPassword($_SESSION['id']))
                $this->TPL['mode'] = 'validated';
            else
                $this->userauth->redirect(base_url() . "index.php?/Home");
        }
        else
        {
            if ($mode == 'ByQNA')
            {
                $this->TPL['mode'] = 'ByQNA';
                $this->TPL['TheUser'] = $this->user->getOneUserDetail($_SESSION['id']);
            }
            else
                $this->TPL['mode'] = 'ByPassword';
        }
        $this->template->show('ResetPasswordView', $this->TPL, FALSE);
//        }
//        else
//            $this->userauth->redirect(base_url() . "index.php?/Login");
    }

    public function resetAll()
    {
        $this->loggedin = $this->userauth->validSessionExists();
        if ($this->loggedin == TRUE)
        {
            if ($this->userauth->isFirstTimeLoggedIn($_SESSION['id']))
                $this->template->show('resetall', $this->TPL, FALSE);
            else
                $this->userauth->redirect(base_url() . "index.php?/Home");
        }
        else
            $this->userauth->redirect(base_url() . "index.php?/Login");
    }

    public function inputLoginNumber()
    {
        $this->template->show('InputLoginNumberView', $this->TPL, FALSE);
    }

    public function answerSecurityQuestion()
    {
        $this->TPL['TheUser'] = $this->user->getOneUserDetail($_SESSION['id']);
        $this->template->show('AnswerSecurityQuestionView', $this->TPL, FALSE);
    }

    /***************************************************************
     * @desc Form Submission handling
     * ************************************************************* */

    public function isThisUserInDB()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
            $this->userauth->redirect(base_url() . "index.php?/Reset/inputLoginNumber");

        $LoginNumber = $this->input->post("LoginNumber");
        
        if ($this->userauth->isFirstTimeLoggedIn($LoginNumber))
        {
            $this->TPL['result'] = "This is your first time login, your password is your login number";
            $this->inputLoginNumber();
        }
        else
        {
            if ($this->userauth->isThisUserInDB($LoginNumber))
            {
                session_start();
                $_SESSION['id'] = $LoginNumber;
                $result = $this->user->getOneUserDetail($LoginNumber);
                $_SESSION['name'] = $result[0]['first_name'] . " " . $result[0]['last_name'];
                $this->answerSecurityQuestion();
            }
            else
            {
                $this->TPL['result'] = "This Login Number is NOT in the system";
                $this->inputLoginNumber();
            }
        }
        
        
    }

    public function resetALLVerification()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            $this->userauth->redirect(base_url() . "index.php?/Login");
        }

        $this->loggedin = $this->userauth->validSessionExists();
        if ($this->loggedin == TRUE)
        {
            if ($this->userauth->isFirstTimeLoggedIn($_SESSION['id']))
            {
                $firstpassword = $this->input->post("firstpassword");
                $secondpassword = $this->input->post("secondpassword");
                $question = $this->input->post("question");
                $answer = $this->input->post("answer");
                $this->TPL['result'] = $this->userauth->resetALLVerification($firstpassword, $secondpassword, $question, $answer);
                $this->template->show('resetall', $this->TPL, FALSE);
            }
            else
                $this->userauth->redirect(base_url() . "index.php?/Home");
        }
        else
            $this->userauth->redirect(base_url() . "index.php?/Login");
    }

    public function resetPasswordVerification()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            $this->userauth->redirect(base_url() . "index.php?/Reset/resetPassword/validated");
        }

        $this->loggedin = $this->userauth->validSessionExists();
//        if ($this->loggedin == TRUE)
//        {
        if ($this->userauth->isChangingPassword($_SESSION['id']))
        {
            $FirstNewPasswordd = $this->input->post("FirstNewPassword");
            $SecondNewPassword = $this->input->post("SecondNewPassword");
            $this->TPL['result'] = $this->userauth->resetPasswordVerification($FirstNewPasswordd, $SecondNewPassword);
            if ($this->TPL['result'] == "")
            {
                $this->TPL['result'] = "<span style=\"color: green\">You have changed your password</span>";
                if ($this->loggedin == TRUE)
                    $this->template->show('home', $this->TPL, TRUE);
                else
                    $this->template->show('login', $this->TPL, FALSE);
            }
            else
            {
                $this->TPL['mode'] = 'validated';
                $this->template->show('ResetPasswordView', $this->TPL, FALSE);
            }
        }
        else
            $this->userauth->redirect(base_url() . "index.php?/Home");
//        }
//        else
//            $this->userauth->redirect(base_url() . "index.php?/Login");
    }

    public function oldPasswordVerification()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            $this->userauth->redirect(base_url() . "index.php?/Reset/resetPassword/ByPassword");
        }
        session_start();
        $oldpassword = $this->input->post("oldpassword");
        $this->TPL['result'] = $this->userauth->oldPasswordVerification($oldpassword);
        $this->resetPassword('ByPassword');
    }

    public function answerVerification()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            $this->userauth->redirect(base_url() . "index.php?/Reset/resetPassword/ByQNA");
        }
        session_start();
        $answer = $this->input->post("answer");
        $this->TPL['result'] = $this->userauth->answerVerification($answer);
        $this->resetPassword('ByQNA');
    }

}

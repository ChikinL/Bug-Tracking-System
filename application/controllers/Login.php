<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{

    var $TPL;
    var $loggedin;

    public function __construct()
    {
        parent::__construct();
        //Your own constructor code
        $this->loggedin = $this->userauth->validSessionExists();
    }

    public function index()
    {
        if ($this->loggedin == TRUE)
        {
            $this->userauth->redirect(base_url() . "index.php?/Home");
        }
        else
        {
            $this->template->show('login', $this->TPL, FALSE);
        }
    }

    public function loginUser()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            $this->userauth->redirect(base_url() . "index.php?/Login");
        }
        
        $loginNumber = $this->input->post("loginNumber");
        $password = $this->input->post("password");
        $this->TPL['result'] = $this->userauth->login($loginNumber, $password);

        $this->template->show('login', $this->TPL, FALSE);
    }

    public function logout()
    {
        $this->userauth->logout();
    }

}

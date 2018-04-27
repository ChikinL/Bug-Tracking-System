<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{

    var $TPL;

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }

    public function index()
    {

        $this->userauth->accessChecking('Home');
        $this->template->show('home', $this->TPL, TRUE);
    }
    
    public function noRightToAccess()
    {

        $this->userauth->accessChecking('Home');
        $this->TPL['result'] = "You Do NOT Have The Right To Access That Page";
        $this->template->show('home', $this->TPL, TRUE);
    }
    public function notFound()
    {

        $this->userauth->accessChecking('Home');
        $this->TPL['result'] = "Page NOT Found";
        $this->template->show('home', $this->TPL, TRUE);
    }

}

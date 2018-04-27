<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller
{

    var $TPL;

    public function __construct()
    {
        parent::__construct();
        //Your own constructor code
        $this->TPL['active'] = array('project' => false,
            'submission' => false,
            'class' => false,
            'user' => true);
        $this->userauth->accessChecking('Users');
    }

    public function index()
    {
        
    }

    public function showAddUserView($mode)
    {
        
        if ($mode == 'One')
            $this->TPL['mode'] = 'One';
        elseif ($mode == 'CSV')
            $this->TPL['mode'] = 'CSV';
        $this->template->show('AddUserView', $this->TPL, TRUE);
    }

    public function showAllUsersView()
    {
        
        $this->TPL['AllUsers'] = $this->user->getAllUser();
        $this->template->show('AllUsersView', $this->TPL, TRUE);
    }

    public function showEditUserView($UserID)
    {
        
        $this->TPL['TheUser'] = $this->user->getOneUserDetail($UserID);
        $this->template->show('EditUserDetailView', $this->TPL, TRUE);
    }

    public function CSVVerification()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            $this->userauth->redirect(base_url() . "index.php?/Users/showAddUserView/CSV");
        }
        

        $temp_path = realpath(APPPATH . '/temp');
        $config['upload_path'] = $temp_path;
        $config['allowed_types'] = 'csv';
        $config['max_size'] = 500;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('CSVfile'))
        {
            $this->TPL['result'] = $this->upload->display_errors();
//            $this->TPL['result'] = $this->user->generateReturnInfo([],"Warning");
        }
        else
        {
            $file = fopen($temp_path.'/'.$this->upload->data('file_name'), "r");
            $AllUsers = [];
            while (!feof($file))
            {
                array_push($AllUsers,fgetcsv($file));
            }

            fclose($file);
            unlink($temp_path.'/'.$this->upload->data('file_name'));//delete the file
//            $this->TPL['test'] = $AllUsers;
            $this->TPL['result'] = $this->user->CSVVerification($AllUsers);
        }
        $this->showAddUserView('CSV');
    }

    public function addOneUserVerification()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            $this->userauth->redirect(base_url() . "index.php?/Users/showAddUserView/One");
        }
        

        $FirstName = $this->input->post("FirstName");
        $LastName = $this->input->post("LastName");
        $LoginNumer = $this->input->post("LoginNumer");
        $Role = $this->input->post("Role");
        $this->TPL['result'] = $this->user->addOneUserVerification($FirstName, $LastName, $LoginNumer, $Role);

        $this->showAddUserView('One');
    }

    public function editOneUserVerification($UserID)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            $this->userauth->redirect(base_url() . "index.php?/Users/showAllUsersView");
        }
        

        $FirstName = $this->input->post("FirstName");
        $LastName = $this->input->post("LastName");
        $LoginNumer = $UserID;
        $Role = $this->input->post("Role");
        $this->TPL['result'] = $this->user->editOneUserVerification($FirstName, $LastName, $LoginNumer, $Role);

        $this->showEditUserView($UserID);
    }

    public function deleteOneUser($UserID)
    {
        
        $this->TPL['result'] = $this->user->deleteOneUser($UserID);
        $this->showAllUsersView();
    }

    public function resetPassword($UserID)
    {
        
        $this->TPL['result'] = $this->user->resetPassword($UserID);
        $this->showAllUsersView();
    }

    public function search($Key)
    {
        
        $this->TPL['FoundUsers'] = $this->user->searchUsers($Key);
        echo json_encode($this->TPL['FoundUsers']);
    } 
}

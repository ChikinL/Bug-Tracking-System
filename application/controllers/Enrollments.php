<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Enrollments extends CI_Controller
{

    var $TPL;

    public function __construct()
    {
        parent::__construct();
        //Your own constructor code
        $this->TPL['active'] = array('project' => false,
            'submission' => false,
            'class' => true,
            'user' => false);
    }

    public function index()
    {
        
    }
    
    public function getEnrollmentsWithClassID($ClassID)
    {
        $this->userauth->accessChecking('getEnrollmentsWithClassID');
        $this->clas->isClassExist($ClassID);
        $this->clas->checkClassOwnership($ClassID);
        $this->TPL['active'] = array('project' => false,'submission' => false,'class' => true,'user' => false);
        $this->TPL['AllUsers'] = $this->user->getAllUser();
        $this->TPL['EnrolledUsers'] = $this->enrollment->getEnrollmentsWithClassID($ClassID);
        $this->TPL['SelectedClass'] = $this->clas->viewClassDetail($ClassID);
        $this->TPL['EnrolledUsersID'] = [];
        foreach ($this->TPL['EnrolledUsers'] as $EnrolledUser)
        {
            array_push($this->TPL['EnrolledUsersID'], $EnrolledUser['user_id']);
        }
        $this->template->show('EnrollmentOfAClassView', $this->TPL, TRUE);
    }
    
    
    
    public function enrollMemberToAClass($UserID, $ClassID)
    {
        $this->userauth->accessChecking('enrollMemberToAClass');
        $this->user->isUserExist($UserID);
        $this->clas->isClassExist($ClassID);
        $this->clas->checkClassOwnership($ClassID);
        $this->enrollment->enrollMemberToAClass($UserID, $ClassID);
        $this->userauth->redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function removeMemberFromAClass($UserID, $ClassID)
    {
        $this->userauth->accessChecking('removeMemberFromAClass');
        $this->user->isUserExist($UserID);
        $this->clas->isClassExist($ClassID);
        $this->clas->checkClassOwnership($ClassID);
        $this->enrollment->removeMemberFromAClass($UserID, $ClassID);
        $this->userauth->redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function changeMembersOfAClass($ClassID)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            $this->userauth->redirect(base_url() . "index.php?/Enrollments/getEnrollmentsWithClassID/$ClassID");
        }
        $this->userauth->accessChecking('changeMembersOfAClass');
        $this->clas->isClassExist($ClassID);
        $this->clas->checkClassOwnership($ClassID);
        $Members = $this->input->post("Members");
        $this->enrollment->changeMembersOfAClass($Members,$ClassID);
        $this->userauth->redirect($_SERVER['HTTP_REFERER']);
    } 
    
    public function enrollMemberByCSV($ClassID)
    {
        $this->userauth->accessChecking('enrollMemberByCSV');
        $this->clas->isClassExist($ClassID);
        $this->clas->checkClassOwnership($ClassID);
        $this->TPL['SelectedClass'] = $this->clas->viewClassDetail($ClassID);
        $this->template->show('EnrollmentMemberByCSVView', $this->TPL, TRUE);
    }
    
    public function CSVVerification($ClassID)
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            $this->userauth->redirect(base_url() . "index.php?/Enrollments/enrollMemberByCSV/$ClassID");
        }
        $this->clas->isClassExist($ClassID);
        $this->clas->checkClassOwnership($ClassID);

        $temp_path = realpath(APPPATH . '/temp');
        $config['upload_path'] = $temp_path;
        $config['allowed_types'] = 'csv';
        $config['max_size'] = 500;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('CSVfile'))
        {
            $this->TPL['result'] = $this->upload->display_errors();
        }
        else
        {
            $file = fopen($temp_path.'/'.$this->upload->data('file_name'), "r");
            $AllUsersID = [];
            while (!feof($file))
            {
                array_push($AllUsersID,fgetcsv($file));
            }

            fclose($file);
            unlink($temp_path.'/'.$this->upload->data('file_name'));//delete the file
            $this->TPL['a'] = $AllUsersID;
            $this->TPL['b'] = $ClassID;
            $this->TPL['result'] = $this->enrollment->CSVVerification($ClassID,$AllUsersID);
        }
        $this->enrollMemberByCSV($ClassID);
    }

}

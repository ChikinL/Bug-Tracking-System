<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Submissions extends CI_Controller
{

    var $TPL;

    public function __construct()
    {
        parent::__construct();
        //Your own constructor code
        $this->TPL['active'] = array('project' => false,
            'submission' => true,
            'class' => false,
            'user' => false);
    }

    public function index()
    {
        
    }

    public function showAddSubmissionView()
    {
        $this->userauth->accessChecking('showAddSubmissionView');
        $AllAssignmentsID = $this->assignment->getAssignmentsWithUserID($_SESSION['id']);
        $AllAssignmentsWithDetailInfo = [];
        foreach ($AllAssignmentsID as $OneAssignment)
        {
            $ClassDetail = $this->clas->viewClassDetail($OneAssignment['class_id'])[0];
            $ProjectDetail = $this->project->viewProjectDetail($OneAssignment['project_id'])[0];
            $AllAssignmentsWithDetailInfo[$OneAssignment['assignment_id']] = $ClassDetail['year'] . ' ' . $ClassDetail['semester'] . ' Section ' . $ClassDetail['section'] . ' ' . $ProjectDetail['project_name'];
        }
        $this->TPL['AllAssignmentsID'] = $AllAssignmentsID;
        $this->TPL['AllAssignmentsWithDetailInfo'] = $AllAssignmentsWithDetailInfo;
        $this->template->show('AddSubmissionView', $this->TPL, TRUE);
    }

    public function addAdditionalNote($SubmissionID)
    {
        $this->userauth->accessChecking('addAdditionalNote');
        $this->submission->isSubmissionExist($SubmissionID);
        $this->submission->checkSubmissionOwnership($SubmissionID);
        $SubmissionDetail = $this->submission->getSubmissionWithSubmissionID($SubmissionID)[0];
        $AssignmentID = $SubmissionDetail['assignment_id'];
        $ClassDetail = $this->clas->viewClassDetail($this->assignment->getAssignmentsWithAssignmentID($AssignmentID)[0]['class_id'])[0];
        $ProjectDetail = $this->project->viewProjectDetail($this->assignment->getAssignmentsWithAssignmentID($AssignmentID)[0]['project_id'])[0];

        $this->TPL['SubmissionDetail'] = $SubmissionDetail;
        $this->TPL['ClassDetail'] = $ClassDetail;
        $this->TPL['ProjectDetail'] = $ProjectDetail;
        $this->template->show('AddAdditionalNoteView', $this->TPL, TRUE);
    }

    public function showAllMySubmissionView()
    {
        $this->userauth->accessChecking('showAllMySubmissionView');
        if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'student')
        {
            $this->TPL['AllSubmissions'] = $this->submission->getSubmissionsWithUserID($_SESSION['id']);
            $AllAssignmentsWithDetailInfo = [];
            foreach ($this->TPL['AllSubmissions'] as $OneSubmission)
            {
                $AssignmentDetail = $this->assignment->getAssignmentsWithAssignmentID($OneSubmission['assignment_id'])[0];
                $ClassDetail = $this->clas->viewClassDetail($AssignmentDetail['class_id'])[0];
                $ProjectDetail = $this->project->viewProjectDetail($AssignmentDetail['project_id'])[0];
                $AllAssignmentsWithDetailInfo[$OneSubmission['submission_id']] = $ClassDetail['year'] . ' ' . $ClassDetail['semester'] . ' Section ' . $ClassDetail['section'] . ' ' . $ProjectDetail['project_name'];
            }
            $this->TPL['AllAssignmentsWithDetailInfo'] = $AllAssignmentsWithDetailInfo;
        }
        else if ($_SESSION['role'] == 'professor')
            $this->TPL['AllBelongingClasses'] = $this->clas->findProfessorOwnClass();
        $this->template->show('AllMySubmissionView', $this->TPL, TRUE);
    }

    public function showSubmissionDetail($SubmissionID)
    {
        $this->userauth->accessChecking('showSubmissionDetail');
        $this->submission->isSubmissionExist($SubmissionID);
        $this->submission->checkSubmissionOwnership($SubmissionID);
        $SubmissionDetail = $this->submission->getSubmissionWithSubmissionID($SubmissionID)[0];
        $AssignmentID = $SubmissionDetail['assignment_id'];
        $ClassDetail = $this->clas->viewClassDetail($this->assignment->getAssignmentsWithAssignmentID($AssignmentID)[0]['class_id'])[0];
        $ProjectDetail = $this->project->viewProjectDetail($this->assignment->getAssignmentsWithAssignmentID($AssignmentID)[0]['project_id'])[0];
        if (!empty($SubmissionDetail['next_additional_note']))
            $this->TPL['AllRelatedNotes'] = $this->submission->getAllRelatedNotes($SubmissionDetail['next_additional_note']);
        $this->TPL['SubmissionDetail'] = $SubmissionDetail;
        $this->TPL['ClassDetail'] = $ClassDetail;
        $this->TPL['ProjectDetail'] = $ProjectDetail;
        $this->TPL['SubmitterDetail'] = $this->user->getOneUserDetail($SubmissionDetail['user_id'])[0];
        $this->template->show('SubmissionDetailView', $this->TPL, TRUE);
    }


    public function addSubmissionVerification()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            $this->userauth->redirect(base_url() . "index.php?/Submissions/showAddSubmissionView");
        }

        $this->userauth->accessChecking('addSubmissionVerification');

        $BugName = $this->input->post("BugName");
        $Assignment = $this->input->post("Assignment");
        $Description = $this->input->post("Description");
        $Area = $this->input->post("Area");
        $TestCase = $this->input->post("TestCase");
        $Workaround = $this->input->post("Workaround");

        $IMG_path = realpath(APPPATH . '/assets/SubmissionImages');
        $config['upload_path'] = $IMG_path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp|tif';
        $config['max_size'] = 2048;

        $this->load->library('upload', $config); //did not successfully upload 

        if (!$this->upload->do_upload('IMGfile'))
        {
            $this->TPL['result'] = $this->upload->display_errors();
            $this->TPL['submitted']['BugName'] = $BugName;
            $this->TPL['submitted']['Assignment'] = $Assignment;
            $this->TPL['submitted']['Description'] = $Description;
            $this->TPL['submitted']['Area'] = $Area;
            $this->TPL['submitted']['TestCase'] = $TestCase;
            $this->TPL['submitted']['Workaround'] = $Workaround;
        }
        else
        {
            $Oldname = $this->upload->data('file_name'); // server file name
            $OldnameWithoutExtension = substr($Oldname, 0, strrpos($Oldname, "."));
            $Extension = substr($Oldname, strrpos($Oldname, "."));
            $salt = mt_rand();
            $hash = sha1($OldnameWithoutExtension . $salt);
            $NewName = $hash . $Extension;
            rename($IMG_path . '/' . $Oldname, $IMG_path . '/' . $NewName);


            $this->TPL['result'] = $this->submission->addSubmissionVerification($BugName, $Assignment, $Description, $Area, $TestCase, $Workaround, $NewName);
            if (strpos($this->TPL['result'], 'color: red') !== false) //Failed to Added Submission
            {
                unlink($IMG_path . '/' . $NewName); //delete the file
                $this->TPL['submitted']['BugName'] = $BugName;
                $this->TPL['submitted']['Assignment'] = $Assignment;
                $this->TPL['submitted']['Description'] = $Description;
                $this->TPL['submitted']['Area'] = $Area;
                $this->TPL['submitted']['TestCase'] = $TestCase;
                $this->TPL['submitted']['Workaround'] = $Workaround;
            }
        }
        $this->showAddSubmissionView();
    }

    public function addAdditionalNoteVerification($SubmissionID)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
            $this->userauth->redirect(base_url() . "index.php?/Submissions/showAllMySubmissionView");

        $this->userauth->accessChecking('addAdditionalNoteVerification');
        $this->submission->isSubmissionExist($SubmissionID);
        $this->submission->checkSubmissionOwnership($SubmissionID);
        $AdditionalNote = $this->input->post("AdditionalNote");

        $IMG_path = realpath(APPPATH . '/assets/SubmissionImages');
        $config['upload_path'] = $IMG_path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp|tif';
        $config['max_size'] = 2048;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('IMGfile'))//did not successfully upload 
        {
            $this->TPL['result'] = $this->upload->display_errors();
            if (strpos($this->TPL['result'], 'You did not select a file to upload.') !== false)
            {
                $this->TPL['result'] = $this->submission->addAdditionalNoteVerification($SubmissionID, $AdditionalNote, '');
                if (strpos($this->TPL['result'], 'color: red') !== false) //Failed to Added Submission
                    $this->TPL['submitted']['AdditionalNote'] = $AdditionalNote;
            }
        }
        else
        {
            $Oldname = $this->upload->data('file_name'); // server file name
            $OldnameWithoutExtension = substr($Oldname, 0, strrpos($Oldname, "."));
            $Extension = substr($Oldname, strrpos($Oldname, "."));
            $salt = mt_rand();
            $hash = sha1($OldnameWithoutExtension . $salt);
            $NewName = $hash . $Extension;
            rename($IMG_path . '/' . $Oldname, $IMG_path . '/' . $NewName);

            $this->TPL['result'] = $this->submission->addAdditionalNoteVerification($SubmissionID, $AdditionalNote, $NewName);
            if (strpos($this->TPL['result'], 'color: red') !== false) //Failed to Added Submission
            {
                unlink($IMG_path . '/' . $NewName); //delete the file
                $this->TPL['submitted']['AdditionalNote'] = $AdditionalNote;
            }
        }
        $this->addAdditionalNote($SubmissionID);
    }

    public function searchSubmissionsWithAssignmentID($AssignmentID)
    {
        $this->userauth->accessChecking('searchSubmissionsWithAssignmentID');
        $this->TPL['FoundSubmissions'] = $this->submission->searchSubmissionsWithAssignmentID($AssignmentID);
        echo json_encode($this->TPL['FoundSubmissions']);
    }

}

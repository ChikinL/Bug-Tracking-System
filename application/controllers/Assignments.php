<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Assignments extends CI_Controller
{

    var $TPL;

    public function __construct()
    {
        parent::__construct();
        //Your own constructor code
    }

    public function index()
    {
        
    }

    public function getAssignmentsWithProjectID($ProjectID)
    {
        $this->userauth->accessChecking('getAssignmentsWithProjectID');
        $this->project->isProjectExist($ProjectID);
        $this->TPL['active'] = array('project' => true,'submission' => false,'class' => false,'user' => false);
        $this->TPL['AllClasses'] = $this->clas->getAllClasses();
        $this->TPL['AssignedClasses'] = $this->assignment->getAssignmentsWithProjectID($ProjectID);
        $this->TPL['SelectedProject'] = $this->project->viewProjectDetail($ProjectID);
        $this->TPL['AssignedClassesID'] = [];
        foreach ($this->TPL['AssignedClasses'] as $AssignedClass)
        {
            array_push($this->TPL['AssignedClassesID'], $AssignedClass['class_id']);
        }
        $this->template->show('AssignmentOfAProjectView', $this->TPL, TRUE);
    }
    
    public function getAssignmentsWithClassID($ClassID)
    {
        $this->userauth->accessChecking('getAssignmentsWithClassID');
        $this->clas->isClassExist($ClassID);
        $this->clas->checkClassOwnership($ClassID);
        $this->TPL['active'] = array('project' => false,'submission' => false,'class' => true,'user' => false);
        $this->TPL['AllProjects'] = $this->project->getAllProjects();
        $this->TPL['AssignedProjects'] = $this->assignment->getAssignmentsWithClassID($ClassID);
        $this->TPL['SelectedClass'] = $this->clas->viewClassDetail($ClassID);
        $this->TPL['AssignedProjectsID'] = [];
        foreach ($this->TPL['AssignedProjects'] as $AssignedProject)
        {
            array_push($this->TPL['AssignedProjectsID'], $AssignedProject['project_id']);
        }
        $this->template->show('AssignmentOfAClassView', $this->TPL, TRUE);
    }
    

    public function assignProjectToAClass($ProjectID, $ClassID)
    {
        $this->userauth->accessChecking('assignProjectToAClass');
        $this->project->isProjectExist($ProjectID);
        $this->clas->isClassExist($ClassID);
        $this->clas->checkClassOwnership($ClassID);
        $this->assignment->assignProjectToAClass($ProjectID, $ClassID);
        $this->userauth->redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function removeProjectFromAClass($ProjectID, $ClassID)
    {
        $this->userauth->accessChecking('removeProjectFromAClass');
        $this->project->isProjectExist($ProjectID);
        $this->clas->isClassExist($ClassID);
        $this->clas->checkClassOwnership($ClassID);
        $this->assignment->removeProjectFromAClass($ProjectID, $ClassID);
        $this->userauth->redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function searchAssignmentsWithClassID($ClassID)
    {
        $this->userauth->accessChecking('searchAssignmentsWithClassID');
        $this->clas->checkClassOwnership($ClassID);
        $this->TPL['FoundAssignments'] = $this->assignment->searchAssignmentsWithDetailByClassID($ClassID);
        echo json_encode($this->TPL['FoundAssignments']);
    } 

}

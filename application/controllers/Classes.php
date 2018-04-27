<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Classes extends CI_Controller
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

    public function showAllClassesView()
    {
        $this->userauth->accessChecking('showAllClassesView');
        $this->TPL['AllClasses'] = $this->clas->getAllClasses();
        $this->template->show('AllClassesView', $this->TPL, TRUE);
    }
    
    public function viewMyClasses()
    {
        $this->userauth->accessChecking('viewMyClasses');
        $this->TPL['AllClasses'] = $this->clas->findProfessorOwnClass();
        $this->template->show('AllClassesView', $this->TPL, TRUE);
    }
    
    public function showAddClassView()
    {
        $this->userauth->accessChecking('showAddClassView');
        $this->TPL['AllProjects'] = $this->project->getAllProjects();
        $this->template->show('AddClassView', $this->TPL, TRUE);
    }
    
    public function deleteOneClass($ClassID)
    {
        $this->userauth->accessChecking('deleteOneClass');
        $this->clas->isClassExist($ClassID);
        $this->TPL['result'] = $this->clas->deleteOneClass($ClassID);
        $this->showAllClassesView();
    }
    
    public function showEditClassView($ClassID)
    {
        $this->userauth->accessChecking('showEditClassView');
        $this->clas->isClassExist($ClassID);
        $this->TPL['TheClass'] = $this->clas->viewClassDetail($ClassID);
        $this->template->show('EditClassDetailView', $this->TPL, TRUE);
    }
    
    
    public function addOneClassVerification()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            $this->userauth->redirect(base_url() . "index.php?/Classes/showAddClassView");
        }
         $this->userauth->accessChecking('addOneClassVerification');
        
        $Year = $this->input->post("Year");
        $Semester = $this->input->post("Semester");
        $Section = $this->input->post("Section");
        $Projects = $this->input->post("Projects");
        
        $this->TPL['result'] = $this->clas->addOneClassVerification($Year,$Semester,$Section,$Projects);
        $this->showAddClassView();
    }
    
    public function editOneClassVerification($ClassID)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            $this->userauth->redirect(base_url() . "index.php?/Classes/showEditClassView/$ClassID");
        }
         $this->userauth->accessChecking('editOneClassVerification');
        $this->clas->isClassExist($ClassID);
        $Year = $this->input->post("Year");
        $Semester = $this->input->post("Semester");
        $Section = $this->input->post("Section");
        
        $this->TPL['result'] = $this->clas->editOneClassVerification($Year,$Semester,$Section,$ClassID);
        $this->showEditClassView($ClassID);
    }


}

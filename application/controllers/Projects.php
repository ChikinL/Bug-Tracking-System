<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends CI_Controller
{

    var $TPL;

    public function __construct()
    {
        parent::__construct();
        //Your own constructor code
        $this->TPL['active'] = array('project' => true,
            'submission' => false,
            'class' => false,
            'user' => false);
    }

    public function index()
    {
        
    }

    public function showProjectDetailView($ProjectID)
    {
        $this->userauth->accessChecking('showProjectDetailView');
        $this->project->isProjectExist($ProjectID);
        $this->TPL['ProjectsWithDetail'] = $this->project->viewProjectDetail($ProjectID);
        $this->template->show('ProjectDetailView', $this->TPL, TRUE);
    }
    
    public function showEditProjectDetailView($ProjectID)
    {
        $this->userauth->accessChecking('showEditProjectDetailView');
        $this->project->isProjectExist($ProjectID);
        $this->TPL['ProjectsWithDetail'] = $this->project->viewProjectDetail($ProjectID);
        $this->template->show('EditProjectDetailView', $this->TPL, TRUE);
    }

    public function showAddProjectView()
    {
        $this->userauth->accessChecking('showAddProjectView');
        if ($_SESSION['role'] == 'admin')
            $this->TPL['AllClasses'] = $this->clas->getAllClasses();
        else if ($_SESSION['role'] == 'professor')
            $this->TPL['AllClasses'] = $this->clas->findProfessorOwnClass();
        
        $this->template->show('AddProjectView', $this->TPL, TRUE);
    }

    public function showAllProjectsView()
    {
        $this->userauth->accessChecking('showAllProjectsView');
        $this->TPL['AllProjects'] = $this->project->getAllProjects();
        $this->template->show('AllProjectsView', $this->TPL, TRUE);
    }
    
    public function deleteOneProject($ProjectID)
    {
        $this->userauth->accessChecking('deleteOneProject');
        $this->project->isProjectExist($ProjectID);
        $this->TPL['result'] = $this->project->deleteOneProject($ProjectID);
        $this->showAllProjectsView();
    }
    
    public function addProjectVerification()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            $this->userauth->redirect(base_url() . "index.php?/Projects/showAddProjectView");
        }
         $this->userauth->accessChecking('addProjectVerification');
        
        $ProjectName = $this->input->post("ProjectName");
        $Description = $this->input->post("Description");
        $Classes = $this->input->post("Classes");
        
        $this->TPL['result'] = $this->project->addProjectVerification($ProjectName,$Description,$Classes);
        $this->showAddProjectView();
    }
    
    public function updateProjectVerification($ProjectID)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            $this->userauth->redirect(base_url() . "index.php?/Projects/showEditProjectDetailView/$ProjectID");
        }
        
        $this->userauth->accessChecking('updateProjectVerification');
        $this->project->isProjectExist($ProjectID);
        $ProjectName = $this->input->post("ProjectName");
        $Description = $this->input->post("Description");
        
        $this->TPL['result'] = $this->project->updateProjectVerification($ProjectID,$ProjectName,$Description);
        
         $this->showEditProjectDetailView($ProjectID);
    }


}

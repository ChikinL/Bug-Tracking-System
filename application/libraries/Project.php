<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Project
{

    
    function getAllProjects()
    {
        $CI = & get_instance();
        $query = $CI->db->query("SELECT * FROM `projects` ORDER BY `project_name` ");
        return $query->result_array();
    }
    
    function viewProjectDetail($ProjectID)
    {
        $CI = & get_instance();
        $query = $CI->db->query("SELECT * FROM `projects` WHERE `project_id` = $ProjectID ");
        return $query->result_array();
    }
    
    function deleteOneProject($ProjectID)
    {
        $CI = & get_instance();
        $CI->db->query("DELETE FROM `assignments` WHERE `project_id` = '$ProjectID'");
        $CI->db->query("DELETE FROM `projects` WHERE `project_id` = '$ProjectID'");
        if ($CI->db->affected_rows() == 0)
            return $this->generateReturnInfo(["Failed to Delete the Project"], "Warning");
        else
            return $this->generateReturnInfo(["Successfully Deleted the Project"], "Success");
    }
    
    /**
     * @return string
     * @desc A common function to handle text input.
     */
    function blankAndSemicolonCheck($Submission, $Field)
    {
        if (empty($Submission))
        {
            return "$Field cannot be blank!";
        }
        elseif (strpos($Submission, ';') !== false)
        {
            return "$Field should NOT contain ';'";
        }
        else
        {
            return "";
        }
    }
    
    /**
     * @return String
     * @desc Verify if all input are valid
     */
    function addProjectVerification($ProjectName,$Description,$Classes)
    {
        
        $projectNameVerificationResult = $this->blankAndSemicolonCheck($ProjectName,"Project Name");
        $descriptionVerificationResult = $this->blankAndSemicolonCheck($Description, "Description");

        if (empty($projectNameVerificationResult) && empty($descriptionVerificationResult))
        {
            $CI = & get_instance();
            $CI->db->query("INSERT INTO projects (project_name, description) VALUES ('$ProjectName', '$Description')");
            if ($CI->db->affected_rows() != 0)
            {
                $lastProjectID = $CI->db->query("SELECT MAX(`project_id`) as ID FROM `projects`")->result_array()[0]['ID'];
                if ($Classes)
                {
                    foreach ($Classes as $Class)
                    {
                        $CI->db->query("INSERT INTO assignments (class_id, project_id) VALUES ('$Class', '$lastProjectID')");
                    }
                }
                return $this->generateReturnInfo(["Successfully Added Project"], "Success");
            }
            else
                return $this->generateReturnInfo(["Failed to Add Project"], "Warning");
        }
        else
             return $this->generateReturnInfo([$projectNameVerificationResult,$descriptionVerificationResult], "Warning");
       
    }
    
    /**
     * @return String
     * @desc Verify if all input are valid
     */
    function updateProjectVerification($ProjectID,$ProjectName,$Description)
    {
        $projectNameVerificationResult = $this->blankAndSemicolonCheck($ProjectName,"Project Name");
        $descriptionVerificationResult = $this->blankAndSemicolonCheck($Description, "Description");
        
        if (empty($projectNameVerificationResult) && empty($descriptionVerificationResult))
        {
            $CI = & get_instance();
            $CI->db->query("UPDATE `projects` SET `project_name` = '$ProjectName', `description` = '$Description' WHERE `project_id` = '$ProjectID';");
            
            if ($CI->db->affected_rows() == 0)
                return $this->generateReturnInfo(["Failed to Update Project"], "Warning");
            else
                return $this->generateReturnInfo(["Successfully Updated Project"], "Success");
        }
        else
            return $this->generateReturnInfo([$projectNameVerificationResult,$descriptionVerificationResult], "Warning");
    }
    
    /**
     * @return string
     * @desc A common function to generate formatted message
     */
    function generateReturnInfo($StringArr, $Mode)
    {
        $Info = '';
        foreach ($StringArr as $String)
        {
            if ($Mode == "Warning")
                $Info .= "<span style=\"color: red\">";
            elseif ($Mode == "Success")
                $Info .= "<span style=\"color: green\">";
            $Info .= $String;
            $Info .= "</span><br/>";
        }
        return $Info;
    }
    
    /**
     * @return void
     * @desc  handle user input invalid parameter in URL
     */
    function isProjectExist($ProjectID)
    {
        $CI = & get_instance();
        $NumberofRow = $CI->db->query("SELECT * FROM `projects` WHERE `project_id` = '$ProjectID' ")->num_rows();
        if ($NumberofRow == 0)
        {

            header("Location: " . $_SESSION['basepage'] . '/notFound'); 
            exit();
        }
    }

}

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Assignment
{
     /**
     * @return array
     * @desc get an Assignment with detail information by Assignment ID
     */
    function getAssignmentsWithAssignmentID($AssignmentID)
    {
        $CI = & get_instance();
        $query = $CI->db->query("SELECT * FROM  `assignments` WHERE  `assignment_id` =  '$AssignmentID'");
        return $query->result_array();
    }
    
    /**
     * @return array
     * @desc get an Assignment with detail information by Project ID
     */
    function getAssignmentsWithProjectID($ProjectID)
    {
        $CI = & get_instance();
        $query = $CI->db->query("SELECT * FROM  `assignments` WHERE  `project_id` =  '$ProjectID'");
        return $query->result_array();
    }

    /**
     * @return array
     * @desc get an Assignment with detail information by Class ID
     */
    function getAssignmentsWithClassID($ClassID)
    {
        $CI = & get_instance();
        $query = $CI->db->query("SELECT * FROM  `assignments` WHERE  `class_id` =  '$ClassID'");
        return $query->result_array();
    }

    /**
     * @return array
     * @desc get all current user enrolled class assignment
     */
    function getAssignmentsWithUserID($UserID)
    {
        $CI = & get_instance();
        $AllClassesUserHas = $CI->db->query("SELECT * FROM  `enrollments` WHERE  `user_id` =  '$UserID' ORDER BY `class_id` DESC ")->result_array();
        $AllAssignmentsUserHas = [];
        foreach ($AllClassesUserHas as $Class)
        {
            $ClassID = $Class ['class_id'];
            $AllAssignmentsOfOneClass = $CI->db->query("SELECT * FROM  `assignments` WHERE  `class_id` =  '$ClassID' ORDER BY `assignment_id` ASC")->result_array(); //[0]['class_id'/'project_id']
            foreach ($AllAssignmentsOfOneClass as $OneAssignment)
                array_push($AllAssignmentsUserHas, $OneAssignment);
        }
        return $AllAssignmentsUserHas;
    }

    function assignProjectToAClass($ProjectID, $ClassID)
    {
        $CI = & get_instance();
        $query = $CI->db->query("INSERT INTO `assignments` (`project_id`, `class_id`) VALUES ('$ProjectID', '$ClassID');");
    }

    function removeProjectFromAClass($ProjectID, $ClassID)
    {
        $CI = & get_instance();
        $query = $CI->db->query("SELECT * FROM `assignments` WHERE `project_id` = '$ProjectID' && `class_id` = '$ClassID';");
        $AssignmentID = $query->result_array()[0]['assignment_id'];
        $CI->db->query("DELETE FROM `submissions` WHERE `assignment_id` = '$AssignmentID';");
        $query = $CI->db->query("DELETE FROM `assignments` WHERE `project_id` = '$ProjectID' && `class_id` = '$ClassID';");
    }
    
    function searchAssignmentsWithDetailByClassID($ClassID)
    {
        $CI = & get_instance();
        $query = $CI->db->query("SELECT * 
FROM  `assignments` 
JOIN  `projects` ON  `assignments`.`project_id` =  `projects`.`project_id` 
WHERE  `assignments`.`class_id` = '$ClassID'
ORDER BY  `projects`.`project_name` DESC ");
        return $query->result_array();
    }

}

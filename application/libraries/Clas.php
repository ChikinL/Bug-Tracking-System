<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clas
{

    /**
     * @return array
     * @desc return all class in the system with detail information(enrolled professor, year, section, semester...)
     */
    function getAllClasses()
    {
        $CI = & get_instance();
        $query = $CI->db->query("SELECT  `classes`.`class_id` , CONCAT(  `first_name` ,  ' ',  `last_name` ) AS  `professorname` ,  `year` , `semester` ,  `section` , `role`
FROM  `classes` 
LEFT JOIN  `enrollments` ON  `classes`.`class_id` =  `enrollments`.`class_id` 
LEFT JOIN  `users` ON  `users`.`user_id` =  `enrollments`.`user_id` 
ORDER BY  `classes`.`year` DESC, `role` ASC  ");

        $AllEnrollments = $query->result_array();
        $ids = [];
        $return = [];

        foreach ($AllEnrollments as $OneEnrollment)
        {
            if (!in_array($OneEnrollment['class_id'], $ids))
            {
                array_push($ids, $OneEnrollment['class_id']);
                if ($OneEnrollment['role'] == 'student')
                    $OneEnrollment['professorname'] = '';
                array_push($return, $OneEnrollment);
            }
            else
            {
                if ($OneEnrollment['role'] != 'student')
                {
                    $position = array_search($OneEnrollment['class_id'], $ids);
                    $return[$position]['professorname'] .= '/' . trim($OneEnrollment['professorname']);
                }
            }
        }

        return $return;
    }
    /**
     * @return array
     * @desc return all class that the professor enrolled with detail information(all enrolled professor, year, section, semester...)
     */
    function findProfessorOwnClass()
    {
        $CI = & get_instance();
        $ProfessorID = $_SESSION['id'];
        $query = $CI->db->query("SELECT  `classes`.`class_id` , CONCAT(  `first_name` ,  ' ',  `last_name` ) AS  `professorname` ,  `year` , `semester` ,  `section` , `role`
FROM  `classes` 
LEFT JOIN  `enrollments` ON  `classes`.`class_id` =  `enrollments`.`class_id` 
LEFT JOIN  `users` ON  `users`.`user_id` =  `enrollments`.`user_id`
WHERE  `users`.`user_id` =  '$ProfessorID'
ORDER BY  `classes`.`year` DESC, `role` ASC  ");

        return $query->result_array();
        ;
    }

    /**
     * @return string
     * @desc delete a class and return result
     */
    function deleteOneClass($ClassID)
    {
        $CI = & get_instance();
        $CI->db->query("DELETE FROM `enrollments` WHERE `class_id` = '$ClassID'");
        $query = $CI->db->query("SELECT * FROM `assignments` WHERE `class_id` = '$ClassID';")->result_array();
        foreach ($query as $OneAssignment)
        {
            $AssignmentID = $OneAssignment['assignment_id'];
            $CI->db->query("DELETE FROM `submissions` WHERE `assignment_id` = '$AssignmentID';");
        }
        $CI->db->query("DELETE FROM `assignments` WHERE `class_id` = '$ClassID'");
        $CI->db->query("DELETE FROM `classes` WHERE `class_id` = '$ClassID'");
        if ($CI->db->affected_rows() == 0)
            return $this->generateReturnInfo(["Failed to Delete the Class"], "Warning");
        else
            return $this->generateReturnInfo(["Successfully Deleted the Class"], "Success");
    }

    function viewClassDetail($ClassID)
    {
        $CI = & get_instance();
        $query = $CI->db->query("SELECT * FROM `classes` WHERE `class_id` = $ClassID ");
        return $query->result_array();
    }

    /**
     * @return String
     * @desc Verify if all input are valid
     */
    function addOneClassVerification($Year, $Semester, $Section, $Projects)
    {
        $YearVerificationResult = $this->blankAndSemicolonCheck($Year, "Year");

        if (empty($YearVerificationResult))
        {
            $CI = & get_instance();
            $NumberofRow = $CI->db->query("SELECT * FROM `classes` WHERE `year` = '$Year' && `semester` = '$Semester' && `section` = '$Section'")->num_rows();
            if ($NumberofRow != 0)
            {
                return $this->generateReturnInfo(["Failed to Added Class", "There Might be a Class Has Same Year, Semester and Section"], "Warning");
            }
            else
            {
                $CI->db->query("INSERT INTO `classes` (`year`, `semester`, `section`) VALUES ('$Year', '$Semester', '$Section')");
                if ($CI->db->affected_rows() != 0)
                {
                    $lastClassID = $CI->db->query("SELECT MAX(`class_id`) as ID FROM `classes`")->result_array()[0]['ID'];
                    if ($Projects)
                    {
                        foreach ($Projects as $Project)
                        {
                            $CI->db->query("INSERT INTO assignments (project_id, class_id) VALUES ('$Project', '$lastClassID')");
                        }
                    }
                    return $this->generateReturnInfo(["Successfully Added Class"], "Success");
                }
                else
                    return $this->generateReturnInfo(["Failed to Add Class"], "Warning");
            }
        }
        else
            return $this->generateReturnInfo([$YearVerificationResult], "Warning");
    }

    /**
     * @return String
     * @desc Verify if all input are valid
     */
    function editOneClassVerification($Year, $Semester, $Section, $ClassID)
    {
        $YearVerificationResult = $this->blankAndSemicolonCheck($Year, "Year");
        if (empty($YearVerificationResult))
        {
            $CI = & get_instance();
            $NumberofRow = $CI->db->query("SELECT * FROM `classes` WHERE `year` = '$Year' && `semester` = '$Semester' && `section` = '$Section'")->num_rows();
            if ($NumberofRow != 0)
            {
                return $this->generateReturnInfo(["Failed to Update Class", "There Might be a Class Has Same Year, Semester and Section"], "Warning");
            }
            else
            {
                $CI->db->query("UPDATE `classes` SET `year` = '$Year', `semester` = '$Semester', `section` = '$Section' WHERE `class_id` = '$ClassID';");
                if ($CI->db->affected_rows() != 0)
                    return $this->generateReturnInfo(["Successfully Updated Class"], "Success");
                else
                    return $this->generateReturnInfo(["Failed to Update Class"], "Warning");
            }
        }
        else
            return $this->generateReturnInfo([$YearVerificationResult], "Warning");
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
     * @desc verify if the class exist
     */
    function isClassExist($ClassID)
    {
        $CI = & get_instance();
        $NumberofRow = $CI->db->query("SELECT * FROM `classes` WHERE `class_id` = '$ClassID' ")->num_rows();
        if ($NumberofRow == 0)
        {

            header("Location: " . $_SESSION['basepage'] . '/notFound');
            exit();
        }
    }

    /**
     * @return void
     * @desc verify if the current professor is enrolled in the class
     */
    function checkClassOwnership($ClassID)
    {

        if ($_SESSION['role'] == 'professor')
        {
            $CI = & get_instance();
            $UserID = $_SESSION['id'];
            $NumberofRow = $CI->db->query("SELECT * FROM `enrollments` WHERE `user_id` = '$UserID' AND `class_id` = '$ClassID' ")->num_rows();
            if ($NumberofRow == 0)
            {

                header("Location: " . $_SESSION['basepage'] . '/noRightToAccess');
                exit();
            }
        }
    }

}

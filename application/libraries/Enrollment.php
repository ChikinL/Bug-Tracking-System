<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Enrollment
{

    function getEnrollmentsWithClassID($ClassID)
    {
        $CI = & get_instance();
        $query = $CI->db->query("SELECT *
FROM  `enrollments` 
WHERE  `class_id` =  '$ClassID'");
        return $query->result_array();
    }

    function enrollMemberToAClass($UserID, $ClassID)
    {
        $CI = & get_instance();
        $query = $CI->db->query("INSERT INTO `enrollments` (`user_id`, `class_id`) VALUES ('$UserID', '$ClassID');");
    }

    function removeMemberFromAClass($UserID, $ClassID)
    {
        $CI = & get_instance();
        $query = $CI->db->query("DELETE FROM `enrollments` WHERE `user_id` = '$UserID' && `class_id` = '$ClassID';");
    }

    function changeMembersOfAClass($Members, $ClassID)
    {
        $CI = & get_instance();
        //first, delete all member in current class
        if ($_SESSION['role'] == 'admin')
        {
            $CI->db->query("DELETE FROM `enrollments` WHERE `class_id` = '$ClassID';");
        }
        else if ($_SESSION['role'] == 'professor')
        {
            $CI->db->query("DELETE FROM `enrollments` WHERE `class_id` = '$ClassID' AND `user_id` NOT IN (SELECT `user_id` FROM `users` where `role` = 'professor');");
        }
        //second, re-enroll all selected users 
        if ($Members)
        {
            foreach ($Members as $UserID)
            {
                $CI->db->query("INSERT INTO `enrollments` (class_id, user_id) VALUES ('$ClassID', '$UserID')");
            }
        }
    }

    /**
     * @return String
     * @desc Verify if the uploaded CSV file is valid and return the result to user
     */
    function CSVVerification($ClassID, $AllUsersID)
    {
        $back = '';
        $didAdd = FALSE;
        $CI = & get_instance();
        //first, delete all member in current class
        if ($_SESSION['role'] == 'admin')
        {
            $CI->db->query("DELETE FROM `enrollments` WHERE `class_id` = '$ClassID';");
        }
        else if ($_SESSION['role'] == 'professor')
        {
            $CI->db->query("DELETE FROM `enrollments` WHERE `class_id` = '$ClassID' AND `user_id` NOT IN (SELECT `user_id` FROM `users` where `role` = 'professor');");
        }


        //second, re-enroll all users that are in CSV file
        foreach ($AllUsersID as $UserID)
        {
            $UserID = $UserID[0];
            if ($UserID != null)
            {
                $LoginNumerVerificationResult = $this->blankAndSemicolonCheck($UserID, "User ID");

                if (empty($LoginNumerVerificationResult))
                {
                    $isUserInSystem = $CI->db->query("SELECT * FROM `users` WHERE `user_id` = $UserID ; ")->num_rows();
                    if ($isUserInSystem)
                    {
                        if ($_SESSION['role'] == 'professor')
                        {
                            $NumberofRow = $CI->db->query("SELECT * FROM `users` WHERE `user_id` = $UserID AND `role` = 'professor'; ")->num_rows();
                            if ($NumberofRow != 0) //This user is a professor
                            {
                                $back .= $this->generateReturnInfo(["Failed to Add User '$UserID': You Can NOT Enroll Professor"], "Warning");
                            }
                            else
                            {
                                $CI->db->query("INSERT INTO `enrollments` (class_id, user_id) VALUES ('$ClassID', '$UserID')");
                                if ($CI->db->affected_rows() == 0)
                                    $back .= $this->generateReturnInfo(["Failed to Enroll Users '$UserID'"], "Warning");
                                else
                                    $didAdd = TRUE;
                            }
                        }
                        else if ($_SESSION['role'] == 'admin')
                        {
                            $CI->db->query("INSERT INTO `enrollments` (class_id, user_id) VALUES ('$ClassID', '$UserID')");
                            if ($CI->db->affected_rows() == 0)
                                $back .= $this->generateReturnInfo(["Failed to Enroll Users '$UserID'"], "Warning");
                            else
                                $didAdd = TRUE;
                        }
                    }
                    else
                    {
                        $back .= $this->generateReturnInfo(["Failed to Enroll Users '$UserID': This User Is NOT In System Yet, Please Ask Admin To Add In"], "Warning");
                    }
                }
                else
                {
                    $back .= $this->generateReturnInfo(["Failed to Enroll Users '$UserID': ", $LoginNumerVerificationResult], "Warning");
                }
            }
        }
        if (empty($back))
            return $this->generateReturnInfo(["Successfully Added All Users"], "Success");
        else
        {
            if ($didAdd)
                return $back . "<br/>" . $this->generateReturnInfo(["Successfully Added Other Users"], "Success");
            else
                return $back;
        }
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
            if ($String != '')
            {
                if ($Mode == "Warning")
                    $Info .= "<span style=\"color: red\">";
                elseif ($Mode == "Success")
                    $Info .= "<span style=\"color: green\">";
                $Info .= $String;
                $Info .= "</span><br/>";
            }
        }
        return $Info;
    }

}

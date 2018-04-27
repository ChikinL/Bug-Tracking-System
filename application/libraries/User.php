<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User
{

    function getAllUser()
    {
        $CI = & get_instance();
        $query = $CI->db->query("SELECT * FROM  `users` WHERE `user_id` != 'admin' ORDER BY `first_name`");
        return $query->result_array();
    }

    /**
     * @return array
     * @desc search any user (first name, last name, role)that matching the provided key
     */
    function searchUsers($Key)
    {
        $CI = & get_instance();
        if ($Key === "%20%20%20%")
            $query = $CI->db->query("SELECT * FROM  `users` WHERE `user_id` != 'admin' ORDER BY `first_name`");
        else
            $query = $CI->db->query("SELECT * FROM `users` WHERE (`user_id` LIKE '%$Key%' OR `first_name` LIKE '%$Key%' OR `last_name` LIKE '%$Key%' OR `role` LIKE '%$Key%') AND `user_id` != 'admin' ORDER BY `first_name`;");
        return $query->result_array();
    }

    function deleteOneUser($UserID)
    {
        $CI = & get_instance();
        $CI->db->query("DELETE FROM `enrollments` WHERE `user_id` = '$UserID' ");
        $CI->db->query("DELETE FROM `users` WHERE `user_id` = '$UserID' ");
        if ($CI->db->affected_rows() == 0)
            return $this->generateReturnInfo(["Failed to Delete the User"], "Warning");
        else
            return $this->generateReturnInfo(["Successfully Deleted the User"], "Success");
    }

    function getOneUserDetail($UserID)
    {
        $CI = & get_instance();
        $query = $CI->db->query("SELECT * FROM  `users` WHERE `user_id` = '$UserID'");
        return $query->result_array();
    }

    /**
     * @return string
     * @desc reset password for a user and return result to the user
     */
    function resetPassword($UserID)
    {
        $CI = & get_instance();
        $salt = mt_rand();
        $HashedPassword = sha1($UserID . $salt);
        $CI->db->query("UPDATE `users` SET `security_question` = '', `answer` = '', `first_time_login` = 'Y', `hashed_password` = '$HashedPassword', `salt` = '$salt'  WHERE `user_id` = '$UserID'");
        if ($CI->db->affected_rows() == 0)
            return $this->generateReturnInfo(["Failed to Reset the User"], "Warning");
        else
            return $this->generateReturnInfo(["Successfully Reset the User"], "Success");
    }

    /**
     * @return String
     * @desc Verify if all input are valid
     */
    function editOneUserVerification($FirstName, $LastName, $LoginNumer, $Role)
    {
        $FirstNameVerificationResult = $this->blankAndSemicolonCheck($FirstName, "First Name");
        $LastNameVerificationResult = $this->blankAndSemicolonCheck($LastName, "Last Name");

        if (empty($FirstNameVerificationResult) && empty($LastNameVerificationResult))
        {
            $CI = & get_instance();
            $CI->db->query("UPDATE `users` SET `first_name` = '$FirstName', `last_name` = '$LastName', `role` = '$Role' WHERE `user_id` = '$LoginNumer';");
            if ($CI->db->affected_rows() == 0)
                return $this->generateReturnInfo(["Failed to Update The User"], "Warning");
            else
                return $this->generateReturnInfo(["Successfully Updated The User"], "Success");
        }
        else
        {
            return $this->generateReturnInfo([$FirstNameVerificationResult, $LastNameVerificationResult], "Warning");
        }
    }

    /**
     * @return String
     * @desc Verify if all input are valid
     */
    function addOneUserVerification($FirstName, $LastName, $LoginNumer, $Role)
    {
        $FirstNameVerificationResult = $this->blankAndSemicolonCheck($FirstName, "First Name");
        $LastNameVerificationResult = $this->blankAndSemicolonCheck($LastName, "Last Name");
        $LoginNumerVerificationResult = $this->blankAndSemicolonCheck($LoginNumer, "Login Number");

        if (empty($FirstNameVerificationResult) && empty($LastNameVerificationResult) && empty($LoginNumerVerificationResult))
        {
            $CI = & get_instance();
            $NumberofRow = $CI->db->query("SELECT * FROM  `users` WHERE  `user_id` =  '$LoginNumer'")->num_rows();
            if ($NumberofRow != 0) //same login number already in database
            {
                return $this->generateReturnInfo(["Failed to Added User", "There Might be a User Has Same Login Number in The System Already"], "Warning");
            }
            else
            {
                $salt = mt_rand();
                $HashedPassword = sha1($LoginNumer . $salt);
                $CI->db->query("INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `role`,`first_time_login`,`changing_password`,`hashed_password`,`salt`) VALUES ('$LoginNumer', '$FirstName', '$LastName', '$Role', 'Y', 'N', '$HashedPassword', '$salt')");

                if ($CI->db->affected_rows() == 0)
                    return $this->generateReturnInfo(["Failed to Added User"], "Warning");
                else
                    return $this->generateReturnInfo(["Successfully Added User"], "Success");
            }
        }
        else
        {
            return $this->generateReturnInfo([$FirstNameVerificationResult, $LastNameVerificationResult, $LoginNumerVerificationResult], "Warning");
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

    /**
     * @return String
     * @desc Verify if the uploaded CSV file is valid and return the result to user
     */
    function CSVVerification($AllUsers)
    {
        $back = '';
        $didAdd = FALSE;
        foreach ($AllUsers as $user)
        {

            if ($user != null)
            {
                $LoginNumerVerificationResult = $this->blankAndSemicolonCheck($user[0], "Login Number");
                $FirstNameVerificationResult = $this->blankAndSemicolonCheck($user[1], "First Name");
                $LastNameVerificationResult = $this->blankAndSemicolonCheck($user[2], "Last Name");
                $RoleVerificationResult = $this->roleCheck($user[3]);

                if (empty($FirstNameVerificationResult) && empty($LastNameVerificationResult) && empty($LoginNumerVerificationResult) && empty($RoleVerificationResult))
                {
                    $CI = & get_instance();
                    $NumberofRow = $CI->db->query("SELECT * FROM  `users` WHERE  `user_id` =  '$user[0]'")->num_rows();
                    if ($NumberofRow != 0) //same login number already in database
                    {
                        $back .= $this->generateReturnInfo(["Failed to Add User '$user[0]': There Might be a User Has Same Login Number in The System Already"], "Warning");
                    }
                    else
                    {
                        $salt = mt_rand();
                        $HashedPassword = sha1($user[0] . $salt);
                        $CI->db->query("INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `role`,`first_time_login`,`changing_password`,`hashed_password`,`salt`) VALUES ('$user[0]', '$user[1]', '$user[2]', '$user[3]', 'Y','N', '$HashedPassword', '$salt')");

                        if ($CI->db->affected_rows() == 0)
                            $back .= $this->generateReturnInfo(["Failed to Add Users '$user[0]'"], "Warning");
                        else
                            $didAdd = TRUE;
                    }
                }
                else
                {
                    $back .= $this->generateReturnInfo(["For Login Number '$user[0]': ". $LoginNumerVerificationResult. $FirstNameVerificationResult. $LastNameVerificationResult. $RoleVerificationResult], "Warning");
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
     * @desc verify if the role value in the uploaded CSV file is valid
     */
    function roleCheck($Submission)
    {
        $RoleVerificationResult = $this->blankAndSemicolonCheck($Submission, "Role");
        if ($RoleVerificationResult == '')
        {
            if (strtolower($Submission) != 'student' && strtolower($Submission) != 'professor')
            {
                $RoleVerificationResult = "Role shoule be either 'student' or 'professor'!";
            }
            return $RoleVerificationResult;
        }
        else
            return $RoleVerificationResult;
    }
    
    /**
     * @return void
     * @desc verify if the user exist
     */
    function isUserExist($UserID)
    {
        $CI = & get_instance();
        $NumberofRow = $CI->db->query("SELECT * FROM `users` WHERE `user_id` = '$UserID' ")->num_rows();
        if ($NumberofRow == 0)
        {

            header("Location: " . $_SESSION['basepage'] . '/notFound'); 
            exit();
        }
    }

}

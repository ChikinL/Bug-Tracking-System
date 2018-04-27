<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Userauth
{

    private $logout_page = "";

    /**
     * Turn off notices so we can have session_start run twice
     */
    function __construct()
    {
        error_reporting(E_ALL & ~E_NOTICE);
        $this->logout_page = base_url() . "index.php?/Login/";
    }

    /**
     * @return string
     * @desc Login handling
     */
    public function login($id, $password)
    {

        session_start();

        // User is already logged in if SESSION variables are good. 
        if ($this->validSessionExists() == true)
        {
            $this->redirect($_SESSION['basepage']);
        }



        // First time users don't get an error message.... 
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
            return;

        // Check login form for well formedness.....if bad, send error message
        $IDCheckResult = $this->blankAndSemicolonCheck($id, "Loging Number");
        $PasswordCheckResult = $this->blankAndSemicolonCheck($password, "Password");
        if (!empty($IDCheckResult) || !empty($PasswordCheckResult))
            return $this->generateReturnInfo([$IDCheckResult, $PasswordCheckResult], "Warning");

        // verify if form's data coresponds to database's data
        if ($this->isPasswordCorrect($id, $password) == false)
        {
            return 'Invalid Username/Password!';
        }
        else
        {
            $CI = & get_instance();
            $query = $CI->db->query("SELECT * FROM users  WHERE `user_id` = '$id'");
            $result = $query->result_array();

            $_SESSION['name'] = $result[0]['first_name'] . " " . $result[0]['last_name'];
            $_SESSION['id'] = $result[0]['user_id'];
            $_SESSION['role'] = $result[0]['role'];
            $_SESSION['basepage'] = base_url() . "index.php?/Home";
            $_SESSION['isLoggedIn'] = "Yes";

            if ($this->isFirstTimeLoggedIn($id))
                $this->redirect(base_url() . "index.php?/Reset/resetAll");
            else
                $this->redirect($_SESSION['basepage']);
        }
    }

    /**
     * @return void
     * @desc The user will be logged out.
     */
    public function logout()
    {
        session_start();
        $_SESSION = array();
        session_destroy();
        header("Location: " . $this->logout_page);
    }

    /**
     * @return bool
     * @desc Verify if user has got a session and if the user's IP corresonds to the IP in the session.
     */
    public function validSessionExists()
    {
        session_start();
        if (isset($_SESSION['isLoggedIn']))
            return true;
        else
            return FALSE;
    }

    /**
     * @return void
     * @desc Verify if login form fields were filled out correctly
     */
    public function formHasValidCharacters($id, $password)
    {
        // check form values for strange characters and length (3-12 characters).
        // if both values have values at this point, then basic requirements met
        if ((empty($id) == false) && (empty($password) == false))
            return true;
        else
            return false;
    }

    /**
     * @return String
     * @desc Verify if password fields were filled out correctly
     */
    function passwordVerification($firstpassword, $secondpassword)
    {
        if (empty($firstpassword) || empty($secondpassword))
        {
            return "Password fields cannot be blank!";
        }
        else if ($firstpassword !== $secondpassword)
        {
            return "Two Password are not matched!";
        }
        else if (strlen(trim($firstpassword)) > 15 || strlen(trim($firstpassword)) < 5 || strlen(trim($secondpassword)) > 15 || strlen(trim($secondpassword)) < 5)
        {
            return "The password should be 5-15 characters in length!";
        }
        elseif (strpos($firstpassword, ';') !== false || strpos($secondpassword, ';') !== false)
        {
            return "Password fields should NOT contain ';'";
        }
        else
        {
            return "";
        }
    }

    /**
     * @return String
     * @desc Verify if Q&A fields were filled out correctly
     */
    function qnaVerification($question, $answer)
    {
        if (empty($question) || empty($answer))
        {
            return "Q&A fields cannot be blank!";
        }
        elseif (strpos($question, ';') !== false || strpos($answer, ';') !== false)
        {
            return "Q&A fields should NOT contain ';'";
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
    function resetALLVerification($firstpassword, $secondpassword, $question, $answer)
    {
        $passwordVerificationResult = $this->passwordVerification($firstpassword, $secondpassword);
        $qnaVerificationResult = $this->qnaVerification($question, $answer);

        if (empty($passwordVerificationResult) && empty($qnaVerificationResult))
        {
            $CI = & get_instance();
            $id = $_SESSION['id'];
            $salt = mt_rand();
            $HashedPassword = sha1($firstpassword.$salt);
            $CI->db->query("UPDATE `users` SET  `first_time_login` = 'N' WHERE `user_id` = '$id' ");
            $CI->db->query("UPDATE `users` SET  `hashed_password` = '$HashedPassword'  WHERE `user_id` = '$id' ");
            $CI->db->query("UPDATE `users` SET  `salt` = '$salt'  WHERE `user_id` = '$id' ");
            $CI->db->query("UPDATE `users` SET  `security_question` = '$question'  WHERE `user_id` = '$id' ");
            $CI->db->query("UPDATE `users` SET  `answer` = '$answer'  WHERE `user_id` = '$id' ");

            $this->redirect($_SESSION['basepage']);
        }
        else
        {
            return $this->generateReturnInfo([$passwordVerificationResult, $qnaVerificationResult], "Warning");
        }
    }

    /**
     * @return String
     * @desc Verify if the new Password is valid
     */
    public function resetPasswordVerification($FirstNewPasswordd, $SecondNewPassword)
    {
        $NewPasswordVerificationResult = $this->passwordVerification($FirstNewPasswordd, $SecondNewPassword);
        if (empty($NewPasswordVerificationResult))
        {
            $CI = & get_instance();
            $id = $_SESSION['id'];
            $salt = mt_rand();
            $HashedPassword = sha1($FirstNewPasswordd.$salt);
            $CI->db->query("UPDATE `users` SET  `changing_password` = 'N' WHERE `user_id` = '$id' ");
            $CI->db->query("UPDATE `users` SET  `hashed_password` = '$HashedPassword'  WHERE `user_id` = '$id' ");
            $CI->db->query("UPDATE `users` SET  `salt` = '$salt'  WHERE `user_id` = '$id' ");
            return "";
        }
        else
            return $this->generateReturnInfo([$NewPasswordVerificationResult], "Warning");
    }

    /**
     * @return String
     * @desc Verify if the Old Password correct
     */
    public function oldPasswordVerification($oldpassword)
    {
        $oldPasswordVerificationResult = $this->blankAndSemicolonCheck($oldpassword, 'Password');
        if (empty($oldPasswordVerificationResult))
        {
            $CI = & get_instance();
            $id = $_SESSION['id'];
            if ($this->isPasswordCorrect($_SESSION['id'], $oldpassword))
            {
                $CI->db->query("UPDATE `users` SET  `changing_password` = 'Y' WHERE `user_id` = '$id' ");
                $this->redirect(base_url() . "index.php?/Reset/resetPassword/validated");
            }
            else
                return $this->generateReturnInfo(["The Old Password is Wrong"], "Warning");
        }
        else
            return $this->generateReturnInfo([$oldPasswordVerificationResult], "Warning");
    }

    /**
     * @return String
     * @desc Verify if the answer for security question correct
     */
    public function answerVerification($answer)
    {
        $answerVerificationResult = $this->blankAndSemicolonCheck($answer, 'Answer');
        if (empty($answerVerificationResult))
        {
            $CI = & get_instance();
            $id = $_SESSION['id'];
            $query = $CI->db->query("SELECT * FROM users  WHERE `user_id` = '$id' AND `answer` = '$answer'");
            $result = $query->result_array();
            if ($result != NULL)
            {
                $CI->db->query("UPDATE `users` SET  `changing_password` = 'Y' WHERE `user_id` = '$id' ");
                $this->redirect(base_url() . "index.php?/Reset/resetPassword/validated");
            }
            else
                return $this->generateReturnInfo(["The Answer is Wrong"], "Warning");
        }
        else
            return $this->generateReturnInfo([$answerVerificationResult], "Warning");
    }

    /**
     * @return bool
     * @desc Verify username and password with MySQL database.
     */
    public function isPasswordCorrect($id, $password)
    {
        if ($this->isThisUserInDB($id))
        {
            $CI = & get_instance();
            $salt = $CI->db->query("SELECT * FROM users  WHERE `user_id` = '$id'")->result_array()[0]['salt'];
            $HashedPassword = sha1($password.$salt);
            $query = $CI->db->query("SELECT * FROM users  WHERE `user_id` = '$id' AND `hashed_password` = '$HashedPassword'");
            $result = $query->result_array();
            if ($result != NULL)
                return true;
            else
                return false;
        }
        else
            return FALSE;
    }

    public function isThisUserInDB($LoginNumber)
    {
        $CI = & get_instance();
        $query = $CI->db->query("SELECT * FROM users  WHERE `user_id` = '$LoginNumber'");
        $result = $query->result_array();
        if ($result != NULL)
            return true;
        else
            return false;
    }

    /**
     * @return bool
     * @desc Verify if user first time logged in
     */
    public function isFirstTimeLoggedIn($id)
    {
        $CI = & get_instance();
        $query = $CI->db->query("SELECT `first_time_login` FROM users  WHERE `user_id` = '$id'");
        if ($query->result_array()[0]['first_time_login'] == 'Y')
            return true;
        else
            return false;
    }

    /**
     * @return bool
     * @desc Verify if user first time logged in
     */
    public function isChangingPassword($id)
    {
        $CI = & get_instance();
        $query = $CI->db->query("SELECT `changing_password` FROM users  WHERE `user_id` = '$id'");
        if ($query->result_array()[0]['changing_password'] == 'Y')
            return true;
        else
            return false;
    }

    /* *****************************************************************************
     * Common Functions
     * ********************************************************************** */

    /**
     * @return void
     * @param string $page
     * @desc Redirect the browser to the value in $page.
     */
    public function redirect($page)
    {
        header("Location: " . $page);
        exit();
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
     * @return string
     * @desc Username getter, not necessary 
     */
    public function getUsername()
    {
        return $_SESSION['id'];
    }

    /**
     * @return string
     * @desc  Each time a page is requested, this function is called to ensure the user is logged in and does not need to change password
     */
    function accessChecking($Section)
    {
        if ($this->validSessionExists())
        {
            if ($this->isFirstTimeLoggedIn($_SESSION['id']))
                $this->redirect(base_url() . "index.php?/Reset/resetAll");
            if ($this->isChangingPassword($_SESSION['id']))
                $this->redirect(base_url() . "index.php?/Reset/resetPassword/validated");
            
            $CI = & get_instance();
            $acl = $CI->config->item('acl');
            if ($acl[$Section][$_SESSION['role']] == FALSE)
            {
                $this->redirect($_SESSION['basepage'].'/noRightToAccess');
            }
        }
        else
        {
            $this->redirect($this->logout_page);
        }
    }
    
    

}

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Submission
{

    /**
     * @return String
     * @desc Verify if all input are valid
     */
    function addSubmissionVerification($BugName, $Assignment, $Description, $Area, $TestCase, $Workaround, $NewName)
    {
        $BugNameVerificationResult = $this->blankAndSemicolonCheck($BugName, "Bug Name");
        $AssignmentVerificationResult = $this->blankAndSemicolonCheck($Assignment, "Assignment");
        $DescriptionVerificationResult = $this->blankAndSemicolonCheck($Description, "Description");
        $AreaVerificationResult = $this->blankAndSemicolonCheck($Area, "Area of Application");
        $TestCaseVerificationResult = $this->blankAndSemicolonCheck($TestCase, "Test Case");
        $WorkaroundVerificationResult = '';
        if (strpos($Workaround, ';') !== false)
            $WorkaroundVerificationResult = "Workaround should NOT contain ';'";

        if (empty($BugNameVerificationResult) && empty($AssignmentVerificationResult) && empty($DescriptionVerificationResult) && empty($AreaVerificationResult) && empty($TestCaseVerificationResult) && empty($WorkaroundVerificationResult))
        {
            $CI = & get_instance();
            $id = $_SESSION['id'];
            $CI->db->query("INSERT INTO `submissions` (`user_id`, `assignment_id`, `next_additional_note`, `bug_name`,`submission_time`,`description`,`test_case`,`image_hashed_file_name`,`area_of_application`,`workaround`) VALUES ('$id', '$Assignment', NULL, '$BugName', CURRENT_TIMESTAMP(), '$Description', '$TestCase', '$NewName', '$Area', '$Workaround')");

            if ($CI->db->affected_rows() == 0)
                return $this->generateReturnInfo(["Failed to Added Submission"], "Warning");
            else
                return $this->generateReturnInfo(["Successfully Added Submission"], "Success");
        }
        else
        {
            return $this->generateReturnInfo([$BugNameVerificationResult, $AssignmentVerificationResult, $DescriptionVerificationResult, $AreaVerificationResult, $TestCaseVerificationResult, $WorkaroundVerificationResult], "Warning");
        }
    }

    /**
     * @return String
     * @desc Verify if all input are valid
     */
    function addAdditionalNoteVerification($SubmissionID, $AdditionalNote, $NewName)
    {
        $AdditionalNoteVerificationResult = $this->blankAndSemicolonCheck($AdditionalNote, "Additional Note");

        if (empty($AdditionalNoteVerificationResult))
        {
            $CI = & get_instance();
            $id = $_SESSION['id'];
            $query = $CI->db->query("SELECT * FROM `submissions` WHERE `submission_id` = '$SubmissionID' ");

            if ($query->num_rows() != 0)
            {
                $CI->db->query("INSERT INTO `additional_note` (`additional_note`, `image_hashed_file_name`) VALUES ('$AdditionalNote', '$NewName')");
                if ($CI->db->affected_rows() != 0)// if inserted additional note successfully 
                {
                    $lastNoteID = $CI->db->query("SELECT MAX(`note_id`) AS ID FROM `additional_note`")->result_array()[0]['ID'];
                    $NextAdditionalNoteForFirstSubmission = $query->result_array()[0]['next_additional_note'];
                    if ($NextAdditionalNoteForFirstSubmission == null) // if the bug does not have any additional note yet
                    {
                        $time = $query->result_array()[0]['submission_time'];
                        $CI->db->query("UPDATE `submissions` SET `next_additional_note` = '$lastNoteID', `submission_time` = '$time' WHERE `submission_id` = '$SubmissionID';");
                        if ($CI->db->affected_rows() == 0)
                        {
                            $CI->db->query("DELETE FROM `additional_note` WHERE `note_id` = '$lastNoteID' ;");
                            return $this->generateReturnInfo(["Failed to Add Additional Note"], "Warning");
                        }
                        else
                            return $this->generateReturnInfo(["Successfully Added Additional Note"], "Success");
                    }
                    else // the bug have at least one additional note
                    {
                        $NextNoteID = $NextAdditionalNoteForFirstSubmission;

                        do// find the last additional note
                        {
                            $ChoosingTheLastNotequery = $CI->db->query("SELECT * FROM `additional_note` WHERE `note_id` = '$NextNoteID' ");
                            $CurrentNoteID = $NextNoteID;
                            $NextNoteID = $ChoosingTheLastNotequery->result_array()[0]['next_note_id'];
                        }
                        while ($NextNoteID != null);

                        // insert the additional note after the last one
                        $CI->db->query("UPDATE `additional_note` SET `next_note_id` = '$lastNoteID' WHERE `note_id` = '$CurrentNoteID';");
                        if ($CI->db->affected_rows() == 0)
                        {
                            $CI->db->query("DELETE FROM `additional_note` WHERE `note_id` = '$lastNoteID' ;");
                            return $this->generateReturnInfo(["Failed to Add Additional Note"], "Warning");
                        }
                        else
                            return $this->generateReturnInfo(["Successfully Added Additional Note"], "Success");
                    }
                }
                else
                    return $this->generateReturnInfo(["Failed to Add Additional Note"], "Warning");
            }
            else
                return $this->generateReturnInfo(["Invalid Submission ID"], "Warning");
        }
        else
            return $this->generateReturnInfo([$AdditionalNoteVerificationResult], "Warning");
    }

     /**
     * @return array
     * @desc get all additional note of a submission
     */
    function getAllRelatedNotes($FirstRelatedNoteID)
    {
        $AllRelatedNotes = [];
        $NextNoteID = $FirstRelatedNoteID;
        $CI = & get_instance();
        do
        {
            $CurrentNote = $CI->db->query("SELECT * FROM `additional_note` WHERE `note_id` = '$NextNoteID' ")->result_array()[0];
            $NextNoteID = $CurrentNote['next_note_id'];
            array_push($AllRelatedNotes, $CurrentNote);
        }
        while ($NextNoteID != null);

        return $AllRelatedNotes;
    }

    function getSubmissionsWithUserID($UserID)
    {
        $CI = & get_instance();
        if ($_SESSION['role'] == 'student')
        {
            $query = $CI->db->query("SELECT * FROM  `submissions` WHERE  `user_id` =  '$UserID' ORDER BY  `assignment_id`  ASC, `submission_time` DESC");
        }
        else if ($_SESSION['role'] == 'admin')
        {
            $query = $CI->db->query("SELECT * FROM  `submissions` ORDER BY  `assignment_id`  ASC, `submission_time` DESC");
        }

        return $query->result_array();
    }

    function getSubmissionWithSubmissionID($SubmissionID)
    {
        $CI = & get_instance();
        $query = $CI->db->query("SELECT * FROM  `submissions` WHERE  `submission_id` =  '$SubmissionID'");
        return $query->result_array();
    }

     /**
     * @return string
     * @desc delete a submission
     */
    function deleteOneSubmission($SubmissionID)
    {
        $CI = & get_instance();
        $Submission = $CI->db->query("SELECT * FROM  `submissions` WHERE  `submission_id` =  '$SubmissionID'")->result_array()[0];
        $SubmissionIMG = $Submission['image_hashed_file_name'];
        $NextNoteID = $Submission['next_additional_note'];

        while (!empty($NextNoteID))
        {
            $CurrentNote = $CI->db->query("SELECT * FROM  `additional_note` WHERE  `note_id` = '$NextNoteID'")->result_array()[0];
            $CurrentNoteID = $CurrentNote['note_id'];
            $NextNoteID = $CurrentNote['next_note_id'];
            $NoteIMG = $CurrentNote['image_hashed_file_name'];
            unlink(realpath(APPPATH . '/assets/SubmissionImages') . '/' . $NoteIMG); //delete the file 
            $CI->db->query("DELETE FROM `additional_note` WHERE `note_id` = '$CurrentNoteID';");
        }

        unlink(realpath(APPPATH . '/assets/SubmissionImages') . '/' . $SubmissionIMG); //delete the file 
        $CI->db->query("DELETE FROM `submissions` WHERE `submission_id` = '$SubmissionID';");
        if ($CI->db->affected_rows() == 0)
            return $this->generateReturnInfo(["Failed to Delete Submission"], "Warning");
        else
            return $this->generateReturnInfo(["Successfully Deleted Submission"], "Success");
    }

    function searchSubmissionsWithAssignmentID($AssignmentID)
    {
        $CI = & get_instance();
        $query = $CI->db->query("SELECT * FROM  `submissions` 
LEFT JOIN  `assignments` ON  `submissions`.`assignment_id` =  `assignments`.`assignment_id`
LEFT JOIN  `projects` ON  `assignments`.`project_id` =  `projects`.`project_id` 
LEFT JOIN  `classes` ON  `assignments`.`class_id` =  `classes`.`class_id` 
LEFT JOIN  `users` ON  `users`.`user_id` =  `submissions`.`user_id` 
WHERE  `assignments`.`assignment_id` =  '$AssignmentID'
ORDER BY  `submissions`.`submission_time` DESC ");
        return $query->result_array();
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
     * @return void
     * @desc verify if the current user own the submission
     */
    function checkSubmissionOwnership($SubmissionID)
    {
        $UserID = $_SESSION['id'];
        if ($_SESSION['role'] == 'student')
        {
            $CI = & get_instance();
            $NumberofRow = $CI->db->query("SELECT * FROM `submissions` WHERE `user_id` = '$UserID' AND `submission_id` = '$SubmissionID' ")->num_rows();
            if ($NumberofRow == 0)
            {

                header("Location: " . $_SESSION['basepage'] . '/noRightToAccess');
                exit();
            }
        }
        else if ($_SESSION['role'] == 'professor')
        {
            $CI = & get_instance();
            $EnrolledMember = $CI->db->query("SELECT `users`.`user_id` FROM `users` 
                                            JOIN  `enrollments` ON  `enrollments`.`user_id` =  `users`.`user_id` 
                                            JOIN  `classes` ON  `enrollments`.`class_id` =  `classes`.`class_id`
                                            JOIN  `assignments` ON  `classes`.`class_id` =  `assignments`.`class_id`
                                            JOIN  `submissions` ON  `assignments`.`assignment_id` =  `submissions`.`assignment_id`
                                                      where `submissions`.`submission_id` = $SubmissionID ")->result_array();
            $isUserEnrolled = FALSE;
            foreach ($EnrolledMember as $Member)
            {
                if ($Member['user_id'] == $UserID)
                    $isUserEnrolled = TRUE;
            }
                
            if (!$isUserEnrolled)
            {

                header("Location: " . $_SESSION['basepage'] . '/noRightToAccess');
                exit();
            }
        }
    }

    /**
     * @return void
     * @desc verify if the submission exist
     */
    function isSubmissionExist($SubmissionID)
    {
        $CI = & get_instance();
        $NumberofRow = $CI->db->query("SELECT * FROM `submissions` WHERE `submission_id` = '$SubmissionID' ")->num_rows();
        if ($NumberofRow == 0)
        {

            header("Location: " . $_SESSION['basepage'] . '/notFound');
            exit();
        }
    }

}

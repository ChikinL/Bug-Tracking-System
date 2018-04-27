<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['acl'] = array(
'Home'=> array('admin' => true, 'professor' => true, 'student' => true),
    
//Users    
'Users' => array('admin' => true, 'professor' => false, 'student' => false),
 
 //Projects
'showProjectDetailView' => array('admin' => true, 'professor' => true, 'student' => false),
'showEditProjectDetailView' => array('admin' => true, 'professor' => false, 'student' => false),
'showAddProjectView' => array('admin' => true, 'professor' => true, 'student' => false),
'showAllProjectsView' => array('admin' => true, 'professor' => false, 'student' => false),
'deleteOneProject' => array('admin' => true, 'professor' => false, 'student' => false),
'addProjectVerification' => array('admin' => true, 'professor' => true, 'student' => false),
'updateProjectVerification' => array('admin' => true, 'professor' => false, 'student' => false),
    
   //Classes 
'showAllClassesView' => array('admin' => true, 'professor' => false, 'student' => false),
'viewMyClasses' => array('admin' => true, 'professor' => true, 'student' => false),
'showAddClassView' => array('admin' => true, 'professor' => false, 'student' => false),
'deleteOneClass' => array('admin' => true, 'professor' => false, 'student' => false),
'showEditClassView' => array('admin' => true, 'professor' => false, 'student' => false),
'addOneClassVerification' => array('admin' => true, 'professor' => false, 'student' => false),
'editOneClassVerification' => array('admin' => true, 'professor' => false, 'student' => false),
  
    //Assignments
'getAssignmentsWithProjectID' => array('admin' => true, 'professor' => false, 'student' => false),  
'getAssignmentsWithClassID' => array('admin' => true, 'professor' => true, 'student' => false),//BELONG
'assignProjectToAClass' => array('admin' => true, 'professor' => true, 'student' => false),//BELONG
'removeProjectFromAClass' => array('admin' => true, 'professor' => true, 'student' => false),//BELONG
'searchAssignmentsWithClassID' => array('admin' => false, 'professor' => true, 'student' => false),//BELONG
 
    //Enrollments
'getEnrollmentsWithClassID' => array('admin' => true, 'professor' => true, 'student' => false),//BELONG
'enrollMemberToAClass' => array('admin' => true, 'professor' => true, 'student' => false),//BELONG
'removeMemberFromAClass' => array('admin' => true, 'professor' => true, 'student' => false),//BELONG
'changeMembersOfAClass' => array('admin' => true, 'professor' => true, 'student' => false),//BELONG
'enrollMemberByCSV' => array('admin' => true, 'professor' => true, 'student' => false),//BELONG
    
    //Submissions
'showAddSubmissionView' => array('admin' => false, 'professor' => false, 'student' => true),
'addAdditionalNote' => array('admin' => false, 'professor' => false, 'student' => true),//BELONG
'showAllMySubmissionView' => array('admin' => true, 'professor' => true, 'student' => true),
'showSubmissionDetail' => array('admin' => true, 'professor' => true, 'student' => true),//BELONG
'addSubmissionVerification' => array('admin' => false, 'professor' => false, 'student' => true),
'addAdditionalNoteVerification' => array('admin' => false, 'professor' => false, 'student' => true),//BELONG
'searchSubmissionsWithAssignmentID' => array('admin' => false, 'professor' => true, 'student' => false),//BELONG
    
                      );
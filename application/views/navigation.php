<ul class="mainlist">
    
    
<? if ($_SESSION['role'] == "admin" || $_SESSION['role'] == "professor" ) { ?>
    <li ><a <? if ($active['project']) { ?> class="active" <? } ?>>Projects</a>
        <ul>
            <li class="sublist" ><a href="<?= base_url(); ?>index.php?/Projects/showAddProjectView"><img class="listicon" src="<?= assetUrl(); ?>img/add_16.png">Add Project</a></li>
            <? if ($_SESSION['role'] == "admin") { ?>
            <li class="sublist" ><a href="<?= base_url(); ?>index.php?/Projects/showAllProjectsView"><img class="listicon" src="<?= assetUrl(); ?>img/search_16.png">View All Projects</a></li> 
            <? } ?>
        </ul>
    </li>
<? } ?>
    
    
    
 <li ><a <? if ($active['submission']) { ?> class="active" <? } ?>>Submissions</a>
        <ul>
            <? if ($_SESSION['role'] == "student") { ?>
            <li class="sublist" ><a href="<?= base_url(); ?>index.php?/Submissions/showAddSubmissionView"><img class="listicon" src="<?= assetUrl(); ?>img/add_16.png">Add Submission</a></li>
            <li class="sublist" ><a href="<?= base_url(); ?>index.php?/Submissions/showAllMySubmissionView"><img class="listicon" src="<?= assetUrl(); ?>img/search_16.png">View My Submissions</a></li>
            <? } ?>
            <? if ($_SESSION['role'] != "student") { ?>
            <li class="sublist" ><a href="<?= base_url(); ?>index.php?/Submissions/showAllMySubmissionView"><img class="listicon" src="<?= assetUrl(); ?>img/search_16.png">View All Submissions</a></li>
            <? } ?>
        </ul>
 </li>
 
 
 
  <? if ($_SESSION['role'] == "admin" || $_SESSION['role'] == "professor" ) { ?>
    <li><a<? if ($active['class']) { ?> class="active" <? } ?>>Classes</a>
         <ul>
             
             <? if ($_SESSION['role'] == "professor") { ?>
            <li class="sublist" ><a href="<?= base_url(); ?>index.php?/Classes/viewMyClasses"><img class="listicon" src="<?= assetUrl(); ?>img/search_16.png">View My Classes</a></li> 
            <? } ?> 
            <? if ($_SESSION['role'] == "admin") { ?>
            <li class="sublist" ><a href="<?= base_url(); ?>index.php?/Classes/showAddClassView"><img class="listicon" src="<?= assetUrl(); ?>img/add_16.png">Add Class</a></li>
            <li class="sublist" ><a href="<?= base_url(); ?>index.php?/Classes/showAllClassesView"><img class="listicon" src="<?= assetUrl(); ?>img/search_16.png">View All Classes</a></li>
            <? } ?>
            
        </ul>
    </li>
  <? } ?>
 
 
 
 <? if ($_SESSION['role'] == "admin") { ?>
    <li><a <? if ($active['user']) { ?> class="active" <? } ?>>Users</a>
          <ul>
            <li class="sublist" ><a href="<?= base_url(); ?>index.php?/Users/showAddUserView/One"><img class="listicon" src="<?= assetUrl(); ?>img/adduser_16.png">Add User</a></li>
            <li class="sublist" ><a href="<?= base_url(); ?>index.php?/Users/showAllUsersView"><img class="listicon" src="<?= assetUrl(); ?>img/users_16.png">Manage All Users</a></li>
        </ul>
    </li>
 <? } ?>
 
 
 
 
 <li style="float: right; text-align: right;"><a class="rightsection">Hello, <?= $_SESSION['name']; ?>
        <img style="vertical-align:middle; margin-left: 5px" src="<?= assetUrl(); ?>img/<?= $_SESSION['role']?>_24.png"></a>
        <ul style="text-align: right">
        <? if ($_SESSION['role'] != "admin") { ?>
         <li class="sublist" ><a class="rightsection" href="<?= base_url(); ?>index.php?/Reset/resetPassword/ByPassword"><img style="float: left" class="listicon" src="<?= assetUrl(); ?>img/key_16.png">Change Password</a></li>
          <? } ?>
         <li class="sublist" ><a class="rightsection" href="<?= base_url(); ?>index.php?/Login/logout"><img style="float: left" class="listicon" src="<?= assetUrl(); ?>img/logout_16.png">Log Out</a></li>
        </ul>
 </li>
 
</ul>
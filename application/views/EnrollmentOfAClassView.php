<h3 style="color: red">System will remove all the <? if ($_SESSION['role'] == 'admin'){ echo 'members'; } else if ($_SESSION['role'] == 'professor'){ echo 'students'; }?> of current class, and re-enroll all selected users</h3>
<h3>Class : <?= $SelectedClass[0]['year'] . '/' . $SelectedClass[0]['semester'] . '/' . $SelectedClass[0]['section'] ?></h3>

<form action="<?= base_url(); ?>index.php?/Enrollments/changeMembersOfAClass/<?= $SelectedClass[0]['class_id'] ?>" method="post">
    <table class="listing" id="enrollment">
        <tr>
            <th>Select</th>
            <th>Student/Faculty Number</th>
            <th>Name</th>
            <th>Role</th>
            <th>Add to This Class</th>
            <th>Remove from This Class</th>
        </tr>
        <? foreach ($AllUsers as $User){ ?>
            <? if (in_array($User['user_id'], $EnrolledUsersID) && $_SESSION['role'] != $User['role']){ ?>
                <tr>
                    <td><input type="checkbox" name="Members[]" value="<?= $User['user_id'] ?>"checked ></td>
                    <td><?= $User['user_id'] ?></td>
                    <td><?= $User['first_name'] . ' ' . $User['last_name'] ?></td>
                    <td><?= ucfirst($User['role']) ?></td>
                    <td>Added</td>
                    <td><a href="<?= base_url() ?>index.php?/Enrollments/removeMemberFromAClass/<?= $User['user_id'] . '/' . $SelectedClass[0]['class_id'] ?>">X</a></td>
                </tr>
            <? } ?>
        <? } ?>
        <? foreach ($AllUsers as $User){ ?>
            <? if (!in_array($User['user_id'], $EnrolledUsersID)  && $_SESSION['role'] != $User['role']){ ?>
                <tr>
                    <td><input type="checkbox" name="Members[]" value="<?= $User['user_id'] ?>"></td>
                    <td><?= $User['user_id'] ?></td>
                    <td><?= $User['first_name'] . ' ' . $User['last_name'] ?></td>
                    <td><?= ucfirst($User['role']) ?></td>
                    <td><a href="<?= base_url() ?>index.php?/Enrollments/enrollMemberToAClass/<?= $User['user_id'] . '/' . $SelectedClass[0]['class_id'] ?>">Add</a></td>
                    <td>Not in this class yet</td>
                </tr>
            <? } ?>
        <? } ?>
    </table>
    <br/>
    <input type="submit" value="Submit" >
    <? if ($_SESSION['role'] == 'admin'){ ?>
    <input type="button" value="Back" onclick="window.location = '<?= base_url(); ?>index.php?/Classes/showAllClassesView'" >
    <br/><br/>
    <input type="button" value="Enroll Members By CSV" onclick="window.location = '<?= base_url(); ?>index.php?/Enrollments/enrollMemberByCSV/<?=  $SelectedClass[0]['class_id'] ?>'" >
    <? } elseif ($_SESSION['role'] == 'professor'){ ?>
    <input type="button" value="Back" onclick="window.location = '<?= base_url(); ?>index.php?/Classes/viewMyClasses'" >
    <br/><br/>
    <input type="button" value="Enroll Students By CSV" onclick="window.location = '<?= base_url(); ?>index.php?/Enrollments/enrollMemberByCSV/<?=  $SelectedClass[0]['class_id'] ?>'" >
    <? } ?>
    
</form>


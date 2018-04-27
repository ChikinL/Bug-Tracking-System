<form id="AddSubmissionForm" style=" display: inline-block;" action="<?= base_url(); ?>index.php?/Submissions/addSubmissionVerification" method="post" enctype="multipart/form-data">
    <table>
        <tr>
            <td><label for="BugName">Bug Name*</label></td>
            <td><input type="text" id="BugName" name="BugName" placeholder="Please input the Bug name" size="25" required value="<?= $submitted['BugName'] ?>"></td>
        </tr>
        <tr>
            <td><label>Assignment*</label></td>
            <td>
                <? if (sizeof($AllAssignmentsID)!= 0) { ?>
                <select form="AddSubmissionForm" name="Assignment">
                    <? foreach ($AllAssignmentsID as $Assignment){?>
                    <option value="<?= $Assignment['assignment_id'] ?>" <? if ($Assignment['assignment_id'] == $submitted['Assignment'] ) { echo selected; } ?>> <?= $AllAssignmentsWithDetailInfo[$Assignment['assignment_id']] ?></option>
                    <? } ?>
                </select>
                <? } else { ?>
                <span style="color: red">Please Wait Until You Get An Assignment</span>
                <? } ?>
            </td>
        </tr>
        <tr>
            <td><label for="Description">Description*</label></td>
            <td><textarea id="Description" name="Description" form="AddSubmissionForm" rows="5" cols="50" required placeholder="Please input some description here..." ><?= $submitted['Description'] ?></textarea></td>
        </tr>
        <tr>
            <td><label for="Area">Area of Application*</label></td>
            <td><textarea id="Area" name="Area" form="AddSubmissionForm" rows="5" cols="50" required placeholder="Please specify where the bug located in the application..." ><?= $submitted['Area'] ?></textarea></td>
        </tr>
        <tr>
            <td><label for="TestCase">Test Case*</label></td>
            <td><textarea id="TestCase" name="TestCase" form="AddSubmissionForm" rows="5" cols="50" required placeholder="Please input a test case that reveals the bug..." ><?= $submitted['TestCase'] ?></textarea></td>
        </tr>
        <tr>
            <td><label for="Workaround">Workaround</label></td>
            <td><textarea id="Workaround" name="Workaround" form="AddSubmissionForm" rows="5" cols="50"  placeholder="Please input workaround if existed..." ><?= $submitted['Workaround'] ?></textarea></td>
        </tr>
        <tr>
            <td><label for="IMGfile">Select a Image file:</label></td>
            <td><input type="file" accept="image/*"  id="IMGfile" name="IMGfile" required></td>
        </tr>
        <tr>
            <td>
                <input type="submit" value="Submit" >
                <input type="reset" value="Clean" >
            </td>
            <td>
                <div style="color: red"><?= $result ?></div>
            </td>
        </tr>
    </table>

</form> 
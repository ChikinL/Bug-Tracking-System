<form id="AddProjectForm" style=" display: inline-block;" action="<?= base_url(); ?>index.php?/Projects/addProjectVerification" method="post">
    <table style=" ">
        <tr>
            <td><label for="ProjectName">Project Name*</label></td>
            <td><input type="text" id="ProjectName" name="ProjectName" placeholder="Please input the project name" size="27" required="required"></td>
        </tr>
        <tr>
            <td><label for="Description">Description*</label></td>
            <td><textarea id="Description" name="Description" form="AddProjectForm" rows="5" cols="50" required="required" placeholder="Please input some description here"></textarea></td>
        </tr>
        <tr>
            <td><label>Classes to Add to</label></td>
            <td>
                <? if (sizeof($AllClasses)!= 0) { ?>
                <select form="AddProjectForm" name="Classes[]" multiple>
                    <? foreach ($AllClasses as $Class){?>
        
                    <option value="<?= $Class['class_id'] ?>"><?= $Class['year']." ".$Class['semester']." Section".$Class['section'] ?></option>
                    <? } ?>
                </select>
                <? } else { ?>
                &nbsp;&nbsp;No Class In The System Yet
                <? } ?>
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" value="Add" >
                <input type="reset" value="Clean" >
            </td>
            <td>
                <pre><?= $result ?></pre>
            </td>
        </tr>
    </table>

</form> 
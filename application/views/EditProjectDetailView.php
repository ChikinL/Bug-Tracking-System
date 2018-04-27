<form id="UpdateProjectForm" style=" display: inline-block;" action="<?= base_url(); ?>index.php?/Projects/updateProjectVerification/<?= $ProjectsWithDetail[0]['project_id'] ?>" method="post">
    <table>
        <tr>
            <td><label for="ProjectName">Project Name*</label></td>
            <td><input type="text" id="ProjectName" name="ProjectName" placeholder="Please input the project name" size="25" required="required" value="<?= $ProjectsWithDetail[0]['project_name'] ?>"></td>
        </tr>
        <tr>
            <td><label for="Description">Description*</label></td>
            <td><textarea id="Description" name="Description" form="UpdateProjectForm" rows="5" cols="50" required="required" placeholder="Please input some description here"><?= $ProjectsWithDetail[0]['description'] ?></textarea></td>
        </tr>
        <tr>
            <td>
                <input type="submit" value="Edit" >
                <input type="button" value="Back" onclick="window.location='<?= base_url(); ?>index.php?/Projects/showAllProjectsView'" >
            </td>
            <td>
                <pre><?= $result ?></pre>
            </td>
        </tr>
    </table>

</form> 
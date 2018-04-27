<h3>Class : <?= $SelectedClass[0]['year'].'/'.$SelectedClass[0]['semester'].'/'.$SelectedClass[0]['section'] ?></h3>


<table class="listing">
    <tr>
        <th>Project Name</th>
        <th>Details</th>
        <th>Add to This Class</th>
        <th>Remove from This Class</th>
    </tr>
    <? foreach ($AllProjects as $Project)
    {
        ?>
        <tr>
            <td><?= $Project['project_name'] ?></td>
            <td><a href="<?= base_url() ?>index.php?/Projects/showProjectDetailView/<?= $Project['project_id'] ?>">View</a></td>
            
            <?if (in_array($Project['project_id'], $AssignedProjectsID)){?>
                <td>Added</td>
                <td><a href="<?= base_url() ?>index.php?/Assignments/removeProjectFromAClass/<?= $Project['project_id'].'/'.$SelectedClass[0]['class_id'] ?>">X</a></td>
            <?}else{?>
                <td><a href="<?= base_url() ?>index.php?/Assignments/assignProjectToAClass/<?= $Project['project_id'].'/'.$SelectedClass[0]['class_id'] ?>">Add</a></td>
                <td>Not in this class yet</td>
            <? } ?>
                
        </tr>
<? } ?>
</table>
<br/>
 <input type="button" value="Back" onclick="window.location='<?= base_url(); ?>index.php?/Classes/viewMyClasses'" >

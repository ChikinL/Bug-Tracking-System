<h3>Project Name: <?= $SelectedProject[0]['project_name'] ?></h3>
<table class="listing">
    <tr>
        <th>Year</th>
        <th>Semester</th>
        <th>Section</th>
        <th>Instructor</th>
        <th>Add to Class</th>
        <th>Remove from Class</th>
    </tr>
    <?foreach ($AllClasses as $Class){?>
        <tr>
            <td><?= $Class['year'] ?></td>
            <td><?= $Class['semester'] ?></td>
            <td><?= $Class['section'] ?></td>
            <td><?= $Class['professorname'] ?></td>
            
            <?if (in_array($Class['class_id'], $AssignedClassesID)){?>
                <td>Added</td>
                <td><a href="<?= base_url() ?>index.php?/Assignments/removeProjectFromAClass/<?= $SelectedProject[0]['project_id'].'/'.$Class['class_id'] ?>">X</a></td>
            <?}else{?>
                <td><a href="<?= base_url() ?>index.php?/Assignments/assignProjectToAClass/<?= $SelectedProject[0]['project_id'].'/'.$Class['class_id'] ?>">Add</a></td>
                <td>Not in this class yet</td>
            <? } ?>
                
            
        </tr>
<? } ?>
</table>
<br/>
 <input type="button" value="Back" onclick="window.location='<?= base_url(); ?>index.php?/Projects/showAllProjectsView'" >
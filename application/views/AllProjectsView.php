<h3><?= $result ?></h3>
<table class="listing">
    <tr>
        <th>Project Name</th>
        <th>Classes</th>
        <th>Details</th>
        <th>Delete</th>
    </tr>
    <? foreach ($AllProjects as $Project)
    {
        ?>
        <tr>
            <td><?= $Project['project_name'] ?></td>
            <td><a href="<?= base_url() ?>index.php?/Assignments/getAssignmentsWithProjectID/<?= $Project['project_id'] ?>">ViewAndEdit</a></td>
            <td><a href="<?= base_url() ?>index.php?/Projects/showEditProjectDetailView/<?= $Project['project_id'] ?>">Edit</a>/<a href="<?= base_url() ?>index.php?/Projects/showProjectDetailView/<?= $Project['project_id'] ?>">View</a></td>
            <td><a href="<?= base_url() ?>index.php?/Projects/deleteOneProject/<?= $Project['project_id'] ?>">X</a></td>
        </tr>
<? } ?>
</table>
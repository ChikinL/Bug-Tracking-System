<p><?= $result ?></p>
<table class="listing">
    <tr>
        <th>Year</th>
        <th>Semester</th>
        <th>Section</th>
        <? if ($_SESSION['role'] == 'admin'){ ?>
        <th>Instructor</th>
      <? } ?>
        <th>Projects</th>
        <? if ($_SESSION['role'] == 'admin'){ ?>
        <th>Members</th>
         <? } else if ($_SESSION['role'] == 'professor'){ ?>
         <th>Students</th>
        <? } ?>
        <? if ($_SESSION['role'] == 'admin'){ ?>
        <th>Edit Class</th>
        <th>Delete</th>
      <? } ?>
    </tr>
    <? foreach ($AllClasses as $Class){ ?>
        <tr>
            <td><?= $Class['year'] ?></td>
            <td><?= $Class['semester'] ?></td>
            <td><?= $Class['section'] ?></td>
            <? if ($_SESSION['role'] == 'admin'){ ?>
            <td><?= $Class['professorname'] ?></td>
            <? } ?>
            <td><a href="<?= base_url(); ?>index.php?/Assignments/getAssignmentsWithClassID/<?= $Class['class_id']?>">View And Edit</a></td>
            <td><a href="<?= base_url(); ?>index.php?/Enrollments/getEnrollmentsWithClassID/<?= $Class['class_id']?>">View And Edit</a></td>
            <? if ($_SESSION['role'] == 'admin'){ ?>
            <td><a href="<?= base_url(); ?>index.php?/Classes/showEditClassView/<?= $Class['class_id']?>">Edit</a></td>
            <td><a href="<?= base_url(); ?>index.php?/Classes/deleteOneClass/<?= $Class['class_id']?>">X</a></td>
            <? } ?>
        </tr>
<? } ?>
</table>
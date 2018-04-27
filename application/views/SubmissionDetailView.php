
<h3><?= $ClassDetail['year'] . ' ' . $ClassDetail['semester'] . ' Section ' . $ClassDetail['section'] . ' ' . $ProjectDetail['project_name'] . ' ' . $SubmissionDetail['bug_name'] ?> <? if ($_SESSION['role'] != 'student') { ?> <?= 'by '. $SubmitterDetail['first_name']. ' ' . $SubmitterDetail['last_name'] ?> <? } ?></h3>    
    <table>
        <tr>
            <td><label>Description*</label></td>
            <td><textarea  rows="5" cols="50" readonly ><?= $SubmissionDetail['description'] ?></textarea></td>
        </tr>
        <tr>
            <td><label>Area of Application*</label></td>
            <td><textarea rows="5" cols="50" readonly ><?= $SubmissionDetail['area_of_application'] ?></textarea></td>
        </tr>
        <tr>
            <td><label>Test Case*</label></td>
            <td><textarea rows="5" cols="50" readonly ><?= $SubmissionDetail['test_case'] ?></textarea></td>
        </tr>
        <tr>
            <td><label>Workaround</label></td>
            <td><textarea rows="5" cols="50" readonly ><?= $SubmissionDetail['workaround'] ?></textarea></td>
        </tr>
        <tr>
            <td><label>Image</label></td>
            <td><img width="90%" src="<?= assetUrl(); ?>SubmissionImages/<?= $SubmissionDetail['image_hashed_file_name'] ?>"></td>
        </tr>
         <?if ($AllRelatedNotes ){ ?>
        <? $count = 0; foreach ($AllRelatedNotes as $Note){ $count++; ?>
        <tr>
            <td><label>Additional Note <?= $count ?></label></td>
            <td><textarea rows="5" cols="50" readonly ><?= $Note['additional_note'] ?></textarea></td>
        </tr>
        <? if (!empty($Note['image_hashed_file_name'])) { ?>
        <tr>
            <td><label>Image</label></td>
            <td><img width="90%" src="<?= assetUrl(); ?>SubmissionImages/<?= $Note['image_hashed_file_name'] ?>"></td>
        </tr>
        <? } ?>
        <? } ?>
        <? } ?>
        <tr>
            <td>
                <? if ($_SESSION['role'] == 'student') { ?>
                <input type="button" value="Add Additional Note" onclick="window.location='<?= base_url(); ?>index.php?/Submissions/addAdditionalNote/<?= $SubmissionDetail['submission_id']?>'" >
                <? } ?>
                <? if ($_SESSION['role'] != 'professor') { ?>
                <input type="button" value="Back" onclick="window.location='<?= base_url(); ?>index.php?/Submissions/showAllMySubmissionView'" >
                <? } ?>
            </td>
            <td>
                <div style="color: red"><?= $result ?></div>
            </td>
        </tr>
    </table>
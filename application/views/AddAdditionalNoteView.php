<h3><?= $ClassDetail['year'] . ' ' . $ClassDetail['semester'] . ' Section ' . $ClassDetail['section'] . ' ' . $ProjectDetail['project_name'] . ' ' . $SubmissionDetail['bug_name'] ?></h3>

<form id="AddAdditionalNoteForm" style=" display: inline-block;" action="<?= base_url(); ?>index.php?/Submissions/addAdditionalNoteVerification/<?= $SubmissionDetail['submission_id'] ?>" method="post" enctype="multipart/form-data">
    <table>
        <tr>
            <td><label for="AdditionalNote">Additional Note*</label></td>
            <td><textarea id="AdditionalNote" name="AdditionalNote" form="AddAdditionalNoteForm" rows="5" cols="50" required placeholder="Please input additional note here..." ><?= $submitted['AdditionalNote'] ?></textarea></td>
        </tr>
        <tr>
            <td><label for="IMGfile">Select a Image file:</label></td>
            <td><input type="file" accept="image/*"  id="IMGfile" name="IMGfile" ></td>
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
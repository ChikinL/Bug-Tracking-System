<h3 style="color: red">System will remove all the <? if ($_SESSION['role'] == 'admin'){ echo 'members'; } else if ($_SESSION['role'] == 'professor'){ echo 'students'; }?> of current class, and re-enroll all user specified in the CSV file</h3>
<h3>Class : <?= $SelectedClass[0]['year'] . '/' . $SelectedClass[0]['semester'] . '/' . $SelectedClass[0]['section'] ?></h3>
<form id="EnrollMembersForm" style=" display: inline-block;" action="<?= base_url(); ?>index.php?/Enrollments/CSVVerification/<?= $SelectedClass[0]['class_id']; ?>" method="post" enctype="multipart/form-data">
    <label for="CSVfile">Select a CSV file:&nbsp&nbsp</label> <input type="file" accept=".csv"  id="CSVfile" name="CSVfile" required>
    <br/>
    
    <div style="color: red"><?= $result ?></div>
    <div >
        <h3  style="display: inline; vertical-align: top ">The Format of CSV File</h3> &nbsp&nbsp
        <textarea style="display: inline; resize: none;" rows="5" cols="50" readonly >
user_id1
user_id2
user_id3
user_id4
.......
        </textarea>
    </div>
    <input type="submit" value="Submit"> <input type="button" value="Back" onclick="window.location = '<?= base_url(); ?>index.php?/Enrollments/getEnrollmentsWithClassID/<?=  $SelectedClass[0]['class_id'] ?>'" >
</form> 
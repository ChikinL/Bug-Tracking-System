<p><?= $result ?></p>

<? if ($_SESSION['role'] == 'professor') { ?>
<? if (sizeof($AllBelongingClasses)!= 0) { ?>
<select id="SelectedClass" name="SelectedClass"   onchange="getCorrespondingAssignments()">
    <? foreach ($AllBelongingClasses as $Class){?>
    <option value="<?= $Class['class_id'] ?>"><?= $Class['year']." ".$Class['semester']." Section".$Class['section'] ?></option>
    <? } ?>
</select>
<select id="SelectedAssignment" name="SelectedAssignment" onchange="getCorrespondingSubmissions()"></select>
    <? } ?>
<? } ?>
<table id="CorrespondingSubmission" class="listing">
    <tr>
        <th>Date/Time of Bug Found</th>
        <th>Assignment</th>
        <th>Bug Name</th>
        <? if ($_SESSION['role'] == 'student') { ?> 
        <th>Additional Note</th>
        <? } ?>
        <th>Details</th>
    </tr>
    <? if ($_SESSION['role'] != 'professor') { ?>
    <? foreach ($AllSubmissions as $Submission) { ?>
        <tr>
            <td><?= $Submission['submission_time'] ?></td>
            <td><?= $AllAssignmentsWithDetailInfo[$Submission['submission_id']] ?></td>
            <td><?= $Submission['bug_name'] ?></td>
            <? if ($_SESSION['role'] == 'student') { ?>
            <td><a href="<?= base_url(); ?>index.php?/Submissions/addAdditionalNote/<?= $Submission['submission_id']?>">Add</a></td>
            <? } ?>
            <td><a href="<?= base_url(); ?>index.php?/Submissions/showSubmissionDetail/<?= $Submission['submission_id']?>">View</a></td>
        </tr>
<? } ?>
<? } ?>
</table>

<script src="<?= assetUrl(); ?>js/jquery.min.js"></script>
<script>
    
        $(document).ready(function () {
           <? if ($_SESSION['role'] == 'professor') {?>
            getCorrespondingAssignments();
            getCorrespondingSubmissions();
            <? } ?>
            
        });
        
        function getCorrespondingAssignments()
        {
            var SelectedClassID = $("#SelectedClass").val();
            url = "<?= base_url(); ?>index.php?/Assignments/searchAssignmentsWithClassID/" + SelectedClassID;

                $.get(url, function (data) {
                    html = "";
                    Assignments = JSON.parse(data);
                    
                    html += "<option disabled selected>Please Select a Project</option>";
                    for (i = 0; i < Assignments.length; i++)
                    {
                        html += "<option value=\""+ Assignments[i]['assignment_id'] + "\">";
                        html += Assignments[i]['project_name'];
                        html += "</option>";
                    }
                    $("#SelectedAssignment").html(html);
                });
                
               html = "";
                    html += "<tr>";
                    html += "<th>Date/Time of Bug Found</th>";
                    html += "<th>Assignment</th>";
                    html += "<th>Student Name</th>";
                    html += "<th>Bug Name</th>";
                    html += "<th>Details</th>";
                    html += "</tr>";
                    $("#CorrespondingSubmission").html(html);
        }
        
        function getCorrespondingSubmissions()
        {
            var SelectedAssignmentID = $("#SelectedAssignment").val();
            url = "<?= base_url(); ?>index.php?/Submissions/searchSubmissionsWithAssignmentID/" + SelectedAssignmentID;

                $.get(url, function (data) {
                    html = "";
                    html += "<tr>";
                    html += "<th>Date/Time of Bug Found</th>";
                    html += "<th>Assignment</th>";
                    html += "<th>Student Name</th>";
                    html += "<th>Bug Name</th>";
                    html += "<th>Details</th>";
                    html += "</tr>";
                    FoundSubmissions = JSON.parse(data);
                    for (i = 0; i < FoundSubmissions.length; i++)
                    {
                         html += "<tr>";
                        html += "<td>"+ FoundSubmissions[i]['submission_time']+"</td>";
                        html += "<td>"+ FoundSubmissions[i]['year']+" "+ FoundSubmissions[i]['semester']+" Section "+ FoundSubmissions[i]['section']+' '+FoundSubmissions[i]['project_name']+"</td>";
                        html += "<td>"+ FoundSubmissions[i]['first_name']+' '+FoundSubmissions[i]['last_name']+"</td>";
                        html += "<td>"+ FoundSubmissions[i]['bug_name']+"</td>";
                        html += "<td>" + "<a href=<?= base_url(); ?>index.php?/Submissions/showSubmissionDetail/" + FoundSubmissions[i]['submission_id'] + "  target=\"_blank\">View</a>" + "</td>";
                        html += "</tr>";
                    }
                    $("#CorrespondingSubmission").html(html);
                });
        }

</script>
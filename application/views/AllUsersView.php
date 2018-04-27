<h4><?= $result ?></h4>
<input type="text" name="search" id="search" size="35" placeholder="Search Keyword in Here">
<table class="listing" id="AllUsers">
    <tr>
        <th>Login Number</th>
        <th>Name</th>
        <th>Role</th>
        <th>Edit</th>
        <th>Reset Password</th>
        <th>Delete</th>
    </tr>
    <? foreach ($AllUsers as $User)
    {
        ?>
        <tr>
            <td><?= $User['user_id'] ?></td>
            <td><?= $User['first_name'].' '.$User['last_name'] ?></td>
            <td><?= ucfirst($User['role']) ?></td>
            <td><a href="<?= base_url() ?>index.php?/Users/showEditUserView/<?= $User['user_id'] ?>">Edit</a></td>
            <td><? if ($User['first_time_login'] == "Y") { ?>Initialized<? } else {?> <a href="<?= base_url() ?>index.php?/Users/resetPassword/<?= $User['user_id'] ?>">Reset</a> <? } ?></td>
            <td><a href="<?= base_url() ?>index.php?/Users/deleteOneUser/<?= $User['user_id'] ?>">X</a></td>
            
            
        </tr>
<? } ?>
</table>
<script src="<?= assetUrl(); ?>js/jquery.min.js"></script>
<script>
        $(document).ready(function () {
            $("#search").keyup(function () {
                searchingStr = $("#search").val();
                searchingStr = searchingStr.trim();

                if (searchingStr)
                    url = "<?= base_url(); ?>index.php?/Users/search/" + searchingStr;
                else
                    url = "<?= base_url(); ?>index.php?/Users/search/   %";

                $.get(url, function (data) {
                    html = "";
                    html += "<tr>";
                    html += "<th>Login Number</th>";
                    html += "<th>Name</th>";
                    html += "<th>Role</th>";
                    html += "<th>Edit</th>";
                    html += "<th>Reset Password</th>";
                    html += "<th>Delete</th>";
                    html += "</tr>";

                    user = JSON.parse(data);

                    for (i = 0; i < user.length; i++)
                    {
                        html += "<tr>";
                        html += "<td>" + user[i]['user_id'] + "</td>";
                        html += "<td>" + user[i]['first_name']+" "+ user[i]['last_name'] + "</td>";
                        html += "<td>" + capitalizeTheFirstLetter(user[i]['role']) + "</td>";
                        html += "<td>" + "<a href=<?= base_url(); ?>index.php?/Users/showEditUserView/" + user[i]['user_id'] + ">Edit</a>" + "</td>";
                        if(user[i]['first_time_login'] == "Y") 
                            html += "<td>" + "Initialized" + "</td>";
                        else
                            html += "<td>" + "<a href=<?= base_url(); ?>index.php?/Users/resetPassword/" + user[i]['user_id'] + ">Reset</a>" + "</td>";
                        html += "<td>" + "<a href=<?= base_url(); ?>index.php?/Users/deleteOneUser/" + user[i]['user_id'] + ">X</a>" + "</td>";
                        html += "</tr>";
                    }
                    $("#AllUsers").html(html);
                });
                return false;
            });
        });


function capitalizeTheFirstLetter(string) 
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}
</script>
<form id="EditUserForm" style=" display: inline-block;" action="<?= base_url(); ?>index.php?/Users/editOneUserVerification/<?= $TheUser[0]['user_id'] ?>" method="post">
    <h4>Login Number <?= $TheUser[0]['user_id'] ?></h4>
        <table>
            <tr>
                <td><label for="FirstName">First Name*</label></td>
                <td><input type="text" id="FirstName" name="FirstName" placeholder="Please Input the First Name" value="<?= $TheUser[0]['first_name'] ?>" size="35" required="required"></td>
            </tr>
            <tr>
                <td><label for="LastName">Last Name*</label></td>
                <td><input type="text" id="LastName" name="LastName" placeholder="Please Input the Last Name" value="<?= $TheUser[0]['last_name'] ?>" size="35" required="required"></td>
            </tr>
            <tr>
                <td><label for="Role">Role*</label></td>
                <td>
                    <select form="EditUserForm" name="Role" >
                        <option value="professor" <? if ($TheUser[0]['role'] == 'professor') { ?> selected <? } ?>>Professor</option>
                        <option value="student" <? if ($TheUser[0]['role'] == 'student') { ?> selected <? } ?>>Student</option>
                    </select>
                </td>
            </tr> 
            <tr> 
                <td>
                    <input type="submit" value="Edit" >
                    <input type="button" value="Back" onclick="window.location='<?= base_url(); ?>index.php?/Users/showAllUsersView'" >
                </td>
                <td>
                    <pre><?= $result ?></pre>
                </td>
            </tr>
        </table>

    </form>
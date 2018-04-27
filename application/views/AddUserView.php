<? if ($mode == 'CSV'){ ?>
<form id="AddUserForm" style=" display: inline-block;" action="<?= base_url(); ?>index.php?/Users/CSVVerification/" method="post" enctype="multipart/form-data">
    <label for="CSVfile">Select a CSV file:&nbsp&nbsp</label> <input type="file" accept=".csv"  id="CSVfile" name="CSVfile" required>
    <br/>
    
    <div style="color: red"><?= $result ?></div>
    <div >
        <h3  style="display: inline; vertical-align: top ">The Format of CSV File</h3> &nbsp&nbsp
        <textarea style="display: inline; resize: none;" rows="5" cols="50" readonly >
user_id1,first_name1,last_name1,role1
user_id2,first_name2,last_name2,role2
user_id3,first_name3,last_name3,role3
user_id4,first_name4,last_name4,role4
.......
        </textarea>
    </div>
    <input type="submit" value="Submit"> 
</form>  
<? }else{ ?>
    <form id="AddUserForm" style=" display: inline-block;" action="<?= base_url(); ?>index.php?/Users/addOneUserVerification/<?= $mode ?>" method="post">
        <table>
            <tr>
                <td><label for="FirstName">First Name*</label></td>
                <td><input type="text" id="FirstName" name="FirstName" placeholder="Please Input the First Name" size="35" required="required"></td>
            </tr>
            <tr>
                <td><label for="LastName">Last Name*</label></td>
                <td><input type="text" id="LastName" name="LastName" placeholder="Please Input the Last Name" size="35" required="required"></td>
            </tr>
            <tr>
                <td><label for="LoginNumer">Login Number*</label></td>
                <td><input type="text" id="LoginNumer" name="LoginNumer" placeholder="Please Input the Faculty/Student Number" size="35" required="required"></td>
            </tr>
            <tr>
                <td><label for="Role">Role*</label></td>
                <td>
                    <select form="AddUserForm" name="Role" >
                        <option value="professor">Professor</option>
                        <option value="student">Student</option>
                    </select>
                </td>
            </tr>
            <tr> 
                <td>
                    <input type="submit" value="Add" >
                    <input type="reset" value="Clean" >
                </td>
                <td>
                    <pre><?= $result ?></pre>
                </td>
            </tr>
        </table>

    </form>
<? } ?>
<br/>
<br/>
<br/>
<ul class="mainlist">
    <li><a href="<?= base_url(); ?>index.php?/Users/showAddUserView/One" <? if ($mode == 'One')
{ ?> class="active" <? } ?>>Add One User</a></li>
    <li><a href="<?= base_url(); ?>index.php?/Users/showAddUserView/CSV" <? if ($mode == 'CSV')
{ ?> class="active" <? } ?>>Upload A CSV File</a></li>
</ul>
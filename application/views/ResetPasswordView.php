
<div class="hc" style="margin-top: 100px; margin-bottom: 200px;">
    <? if ($mode != 'validated'){ ?>
    <p>Hello <i><?= $_SESSION['name'] ?></i> <br/>
         For changing your password, you can either enter your old password or answer security question<br/>
         Once you are validated, you will be asked to provide a new password and you can't enter the system until you do so
    </p>

    <? if ($mode == 'ByPassword'){ ?>
        <form style=" display: inline-block;" action="<?= base_url(); ?>index.php?/Reset/oldPasswordVerification" method="post">
            <input type="password" id="oldpassword" name="oldpassword"  placeholder="Please input your old password" size="45" required>
            <br/><br/>
            <input type="submit" value="Submit" style="float: left;">
        </form> 
    <? } elseif ($mode == 'ByQNA') { ?>
        <form style=" display: inline-block;" action="<?= base_url(); ?>index.php?/Reset/answerVerification" method="post">
            <input type="text" id="question" name="question" size="45" value="<?= $TheUser[0]['security_question'] ?>" readonly>
            <br>
            <input type="text" id="answer" name="answer" placeholder="Please input your answer for the secruity question" size="45" required>
            <br/><br/>
            <input type="submit" value="Submit" style="float: left;">
        </form> 
    <? } ?>
    <br/><br/><?= $result ?>
    <ul class="mainlist" style=" display: inline-block;">
        <li><a href="<?= base_url(); ?>index.php?/Reset/resetPassword/ByPassword" <? if ($mode == 'ByPassword') { ?> class="active" <? } ?>>Entering Old Password</a></li>
        <li><a href="<?= base_url(); ?>index.php?/Reset/resetPassword/ByQNA" <? if ($mode == 'ByQNA') {  ?> class="active" <? } ?>>Answering Security Question</a></li>
    </ul>
    <? } else { ?>
    <p>Hello <i><?= $_SESSION['name'] ?></i>,  You are verified<br/>
         Your account is on "Changing Password" status<br/>
         You will not be able to enter system until you provide a new password
    </p>
         <form style=" display: inline-block;" action="<?= base_url(); ?>index.php?/Reset/resetPasswordVerification" method="post">
            <input type="password" id="FirstNewPassword" name="FirstNewPassword"  placeholder="Please input your new password" size="45" required><br/>
            <input type="password" id="SecondNewPassword" name="SecondNewPassword"  placeholder="Please input your new password again" size="45" required>
            <br/><br/><?= $result ?>
            <input type="submit" value="Submit" style="float: left;">
            <input type="button" value="Logout" onclick="window.location='<?= base_url(); ?>index.php?/Login/logout'" >
        </form> 
     <? } ?>
</div>

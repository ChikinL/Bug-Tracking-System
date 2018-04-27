<div class="hc" style="margin-top: 100px; margin-bottom: 200px;">
    <p>Hello <i><?= $_SESSION['name'] ?></i></p>
    <p>This is your first time login or your password have been reset</p>
    
    <form style=" display: inline-block;" action="<?= base_url(); ?>index.php?/Reset/resetALLVerification" method="post">

        <input type="password" id="firstpassword" name="firstpassword"  placeholder="Please input your new password" size="45">
        <br>
        <input type="password" id="secondpassword" name="secondpassword"  placeholder="Please input your new password again" size="45">
        <br><?= $result ?>
        <br><br>

        
        <input type="text" id="question" name="question" placeholder="Please input your secruity question" size="45">
        <br>
        <input type="text" id="answer" name="answer" placeholder="Please input your answer for the secruity question" size="45">
        <br><br>
        <input type="submit" value="Submit" style="float: left;">
        <input type="button" value="Logout" onclick="window.location='<?= base_url(); ?>index.php?/Login/logout'" >



    </form> 



</div>

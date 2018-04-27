<div class="hc" style="margin-top: 100px; margin-bottom: 200px;">
    <p style="color: red"><?= $result ?></p>
    <form style=" display: inline-block;" action="<?= base_url(); ?>index.php?/Reset/answerVerification" method="post">
            <input type="text" id="question" name="question" size="47" value="<?= $TheUser[0]['security_question'] ?>" readonly>
            <br>
            <input type="text" id="answer" name="answer" placeholder="Please input your answer for the secruity question" size="47" required>
            <br/><br/>
            <input type="submit" value="Submit" style="float: left;">
            <input type="button" value="Back" onclick="window.location='<?= base_url(); ?>index.php?/Login/logout'" style="float: right; margin-right: 10px">
        </form>  
</div>
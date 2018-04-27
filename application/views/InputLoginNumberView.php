<div class="hc" style="margin-top: 100px; margin-bottom: 200px;">
    <p style="color: red"><?= $result ?></p>
    <form style=" display: inline-block;" action="<?= base_url(); ?>index.php?/Reset/isThisUserInDB" method="post">

        <img style="vertical-align:middle" src="<?= assetUrl(); ?>img/person_32.png">
        <input type="text" id="LoginNumber" name="LoginNumber" value="" placeholder="Please Input Your Faculty/Student Number" size="40" required>
        <br><br>
        <input type="submit" value="Submit" style="float: left; margin-left: 10px">
        <input type="button" value="Back" onclick="window.location='<?= base_url(); ?>index.php?/Login'" style="float: right; margin-right: 10px">
    </form> 
</div>
<div class="hc" style="margin-top: 100px; margin-bottom: 200px;">
    <p style="color: red"><?= $result ?></p>
    <form style=" display: inline-block;" action="<?= base_url(); ?>index.php?/login/loginUser" method="post">

        <img style="vertical-align:middle" src="<?= assetUrl(); ?>img/person_32.png">
        <input type="text" id="loginNumber" name="loginNumber" value="" placeholder="Please Input Your Faculty/Student Number" size="40">
        <br>

        <img style="vertical-align:middle" src="<?= assetUrl(); ?>img/key_32.png">
        <input type="password" id="password" name="password"  placeholder="Please Input Your Password" size="40">
        <br><br>
        <input type="submit" value="Login" style="float: left; margin-left: 10px">
        <input type="button" value="Forgot Password" onclick="window.location='<?= base_url(); ?>index.php?/Reset/inputLoginNumber'" style="float: right; margin-right: 10px">



    </form> 



</div>

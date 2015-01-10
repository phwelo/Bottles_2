<?php
session_start();
if (isset($_SESSION['PassError'])) {
    $PassError = $_SESSION['PassError'];
    $Display = "display:block;";
}else{
	$PassError = "";
        $Display = "display:none;";
	}
    session_start();
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(),'',0,'/');
    session_regenerate_id(true);

?>
<?PHP include 'header.php' ?>

<div class="alert alert-error" style="<?php echo $Display?>">
  <a class="close" data-dismiss="alert" href="#">x</a>
  <h4 class="alert-heading">Error!</h4>
  <?php echo $PassError?>
</div>
<div class="ui inverted large form segment">
<form action="submitfood.php" method="post" id="form1" name="form1">
  <div class="two fields">
    <div class="field">
      <label>Name of Food</label>
      <input id="input01" name="VTitle" placeholder="Name" type="text">
    </div>
    <div class="field">
      <label>Amount of Phe</label>
      <input id="input02" name="VPhe" placeholder="Amount" type="tel">
    </div>
  </div>
  <div onclick="document.getElementById('form1').submit();" class="ui blue submit button fluid">Submit to Database</div>
  </form>
</div>
</form>
</html>



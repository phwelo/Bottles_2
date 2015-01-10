<?php
$con = mysql_connect("localhost","root","ALsk1029");
mysql_select_db("bottles", $con);

$query = "SELECT TargetPhe FROM settings WHERE ID=1";
$result = mysql_query($query) or die (mysql_error());
$row = mysql_fetch_array($result);
$phelimit = $row['TargetPhe'];

$phecss = "";
if (isset($_GET['p'])) {
  $phecss = "show";
}
?>

<?
//print_r($_POST);

if($_POST["action"] == "Upload Image")
{
unset($imagename);

if(!isset($_FILES) && isset($HTTP_POST_FILES))
$_FILES = $HTTP_POST_FILES;

if(!isset($_FILES['image_file']))
$error["image_file"] = "An image was not found.";

$imagename = basename($_FILES['image_file']['name']);
//echo $imagename;

if(empty($imagename))
$error["imagename"] = "The name of the image was not found.";

if(empty($error))
{
$newimage = "images/" . $imagename;
//echo $newimage;
$result = @move_uploaded_file($_FILES['image_file']['tmp_name'], $newimage);
if(empty($result))
$error["result"] = "There was an error moving the uploaded file.";
}
}
?>

<script>
$('.ui.modal')
  .modal()
;
</script>

<?PHP include 'header.php' ?>

<form action="phelim.php" method="post">
<div class="ui menu">
<div class="link item">
    Phe Limit:
  </div>
<div class="item">
    <div class="ui input">
      <input name="phe" type="tel" placeholder="<?php echo $phelimit ?>">
    </div>
  </div>
  <div class="right item">
    <div class="ui purple button" onclick="$(this).closest('form').submit();">Change</div>
  </div>
</div>
</form>

<form enctype="multipart/form-data" action="picupload.php" method="POST">
<input type="hidden" name="who" value="stella">
<div class="ui menu">
<div class="link item">
    Stella Picture:
    </div>
<div class="item">
  <input  class="ui input" name="userfile" type="file" accept="image/jpeg"/>  
    </div>
<div class="right item">
    <div class="ui purple button" onclick="$(this).closest('form').submit();">Set</div>
    </div>
</div>
</form>

<form enctype="multipart/form-data" action="picupload.php" method="POST">
<input type="hidden" name="who" value="zoe">
<div class="ui menu">
<div class="link item">
    Zoe Picture:
    </div>
<div class="item">
  <input  class="ui input" name="userfile" type="file" accept="image/jpeg"/>  
    </div>
<div class="right item">
    <div class="ui purple button" onclick="$(this).closest('form').submit();">Set</div>
    </div>
</div>
</form>

<div class="ui basic modal">
    <div class="header">
      Phe Limit Changed
    </div>
    <div class="content">
      <div class="left">
        <i class="food icon"></i>
      </div>
      <div class="right">
        <p>New Phe Limit is <b><?php echo $phelimit ?></b>!</p>
      </div>
    </div>
    <div class="actions">
      <div class="fluid ui buttons">
        <div class="ui positive labeled icon button">
          <i class="food icon"></i>
          Okay
        </div>
      </div>
    </div>
  </div>

<script>
$('.basic.modal')
  .modal('<?php echo $phecss ?>')
;
</script>


</body>
</html>

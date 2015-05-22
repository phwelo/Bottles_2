<?PHP
date_default_timezone_set('America/Detroit');

$user_name = "*******";
$password = "********"";
$database = "bottles";
$server = "localhost";

$db_handle = mysql_connect($server, $user_name, $password);
$db_found = mysql_select_db($database, $db_handle);
if(isset($_GET["id"]))
    $ervisible="";
 else
    $ervisible="none";
if(isset($_GET["s"]))
    $sucvisible="";
 else
    $sucvisible="none";

if ($db_found) 
{
  $SQL = 'SELECT * FROM stella WHERE DATE(time) = DATE(NOW()) ORDER BY time DESC';
  $result = mysql_query($SQL);
  $varoptions = "";
  $varentries = "";
  $unit = "Error";
	while ($db_field = mysql_fetch_assoc($result))
      {
	  $SQL2 = 'SELECT * FROM food WHERE Code="'. $db_field['type'].'"';
	  $result2 = mysql_query($SQL2) or die(mysql_error());
	  $Smysqldate = $db_field['time'];
	  $Sepoch = strtotime( $Smysqldate );
	  $Smysqldate = date( 'g:ia m/d', $Sepoch );
	  while($db_field2 = mysql_fetch_assoc($result2))
        {
	    $varentries = $varentries . "<tr><td><a href=today.php?id=S" . $db_field['id']  . "><i class='red icon close'></i></a>" . $Smysqldate . "</td><td>" . $db_field['amount'] . "mg ph</td><td>" . $db_field['grams'] . "</td><td>". $db_field2['Name'] . "</td></tr></a>";
		}
	  }
    $SQL = 'SELECT * FROM zoe WHERE DATE_SUB(CURDATE(),INTERVAL 0 DAY) <= time ORDER BY time DESC';
    $result = mysql_query($SQL);
    $varoptions2 = "";
    $varentries2 = "";
    $unit = "Error";
	while ($db_field = mysql_fetch_assoc($result))
      {
		$SQL2 = 'SELECT * FROM food WHERE Code="'. $db_field['type'].'"';
		$result2 = mysql_query($SQL2) or die(mysql_error());
		$Zmysqldate = $db_field['time'];
		$Zepoch = strtotime( $Zmysqldate );
		$Zmysqldate = date( 'g:ia m/d', $Zepoch );
		while($db_field2 = mysql_fetch_assoc($result2))
          {
			$varentries2 = $varentries2 . "<tr><td><a href=today.php?id=Z" . $db_field['id'] . "><i class='red icon close'></i></a>" . $Zmysqldate . "</td><td>" . $db_field['amount'] . "mg phe</td><td>" . $db_field['grams'] . "</td><td>" . $db_field2['Name'] . "</td></tr>";
		  }
	  }
    mysql_close($db_handle);
}
else
{
  print "Database NOT Found ";
  mysql_close($db_handle);
}

$date = new DateTime();
$date->add(DateInterval::createFromDateString('yesterday'));
$con = mysql_connect("localhost","root","ALsk1029");
mysql_select_db("bottles", $con);

//Get sum of mixed values for the day for stella
$query = "SELECT type, SUM(amount) FROM stella WHERE (DATE_SUB(CURDATE(),INTERVAL 0 DAY) <= time AND type='M')"; 
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)){
	$STotal = $row['SUM(amount)'];
	}

//Get sum of mixed values for the day for zoe
$query = "SELECT type, SUM(amount) FROM zoe WHERE (DATE_SUB(CURDATE(),INTERVAL 0 DAY) <= time AND type='M')"; 
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)){
	$ZTotal = $row['SUM(amount)'];
	}

include 'header.php';

?>
<style>
a{text-decoration:none;font-color:red;}
i{display:none;}
</style>
<div class="alert alert-error" style="display:<?php echo $ervisible ?>">
<a class="close" data-dismiss="alert" href="today.php">x</a>
  <p><strong>Confirm Deletion!</strong>  Are you sure you want to delete whatever ID you picked?</p>
 <form action="deleter.php" method="post">
 <input type="hidden" name="id" value="<?php echo $_GET["id"] ?>">
 <input type="submit" value="Confirm">&nbsp<a href="today.php">Cancel</a>
 </form>
</div>

<div class="alert alert-success" style="display:<?php echo $sucvisible ?>">
<a class="close" data-dismiss="alert" href="today.php">x</a>
<h4 class="ui inverted green block header"><strong>Success!</strong>  Record has been deleted.</h4>
</div>

	<h4>Stella Totals:</h4>
	<table class="ui compact table segment">
	<thead><tr><th>Time</th><th>Phe</th><th>Amount</th><th>Food</th></tr></thead>
	<?php echo $varentries ?>
	</table>

	<h4>Zoe Totals:</h4>	
	<table class="ui compact table segment">
	<thead><tr><th>Time</th><th>Phe</th><th>Amount</th><th>Food</th></tr></thead>
	<?php echo $varentries2 ?>
	</table>
</body>
</html>

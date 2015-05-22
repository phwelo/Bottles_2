<?php
$FPTarget = 0;

$STgot = $_GET["ST"];
$SYgot = $_GET["SY"];
$SDgot = $_GET["SD"];

$ZTgot = $_GET["ZT"];
$ZYgot = $_GET["ZY"];
$ZDgot = $_GET["ZD"];

$SCgot = "none";
$ZCgot = "none";

if ($STgot>0) {
    $SCgot = "";
}

if ($ZTgot>0) {
    $ZCgot = "";
}

$con = mysql_connect("localhost","****","********");
mysql_select_db("bottles", $con);

//get current phe limit from settings table
$query = "SELECT TargetPhe FROM settings WHERE ID=1";
$result = mysql_query($query) or die (mysql_error());
$row = mysql_fetch_array($result);
$SFPTarget = $row['TargetPhe'];
$ZFPTarget = $row['TargetPhe'];

//Get sum of everything that isn't mixed formula and has a value
$query = "SELECT type, SUM(amount) FROM stella WHERE (DATE_SUB(CURDATE(),INTERVAL 0 DAY) <= time AND type<>'M')";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result))
  {
    $SCTotal = $row['SUM(amount)'];
  }
if (empty($SCTotal))
  {
    $SCTotal = 0;
  }

//Get sum of everything besides formula
$query = "SELECT type, SUM(amount) FROM zoe WHERE (DATE_SUB(CURDATE(),INTERVAL 0 DAY) <= time AND type<>'M')"; 
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result))
  {
    $ZCTotal = $row['SUM(amount)'];
  }
if (empty($ZCTotal))
  {
    $ZCTotal = 0;
  }
	
//Put color value into $?Perc2 variable
$SRemain2 = $SFPTarget - $SCTotal;	
$SPerc2 = 100 - ($SRemain2 / $SFPTarget * 100);
$SPerc2 = round($SPerc2, 0, PHP_ROUND_HALF_UP);
if($SPerc2 < 50){
   $SPercCSS = "green";}
elseif($SPerc2 < 85){
   $SPercCSS = "orange";}
else{
   $SPercCSS = "red";}

$ZRemain2 = $ZFPTarget - $ZCTotal;
$ZPerc2 = 100 - ($ZRemain2 / $ZFPTarget * 100);
$ZPerc2 = round($ZPerc2, 0, PHP_ROUND_HALF_UP);
if($ZPerc2 < 50){
   $ZPercCSS = "green";}
elseif($ZPerc2 < 85){
   $ZPercCSS = "orange";}
else{
   $ZPercCSS = "red";}

$query = "SELECT * FROM food ORDER BY Name";
$result = mysql_query($query) or die(mysql_error());
$varOptions = "";
while($row = mysql_fetch_array($result)){
	$varBuild ="<option  value=" . $row['Code']. ">". $row['Name']. "</option>";
	$varOptions = $varOptions . $varBuild;
	}
?>

<head>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1, maximum-scale=1">

<title>Phe Calculator</title>

<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700|Open+Sans:300italic,400,300,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" class="ui" href="Semantic/build/packaged/css/semantic.css">
<link rel="apple-touch-icon" href="images/apple-touch-icon.png" />
<link rel="stylesheet" href="stylesheets/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="Semantic/build/packaged/javascript/semantic.js"></script>
<script type="text/javascript">
function s1change() {document.getElementById("Samount").value = "1";
                     document.getElementById("Stime").click();}
function s2change() {document.getElementById("Samount").value = "2";
					document.getElementById("Stime").click();}
function s3change() {document.getElementById("Samount").value = "3";
					document.getElementById("Stime").click();}
function z1change() {document.getElementById("Zamount").value = "1";
					document.getElementById("Ztime").click();}
function z2change() {document.getElementById("Zamount").value = "2";
					document.getElementById("Ztime").click();}
function z3change() {document.getElementById("Zamount").value = "3";
					document.getElementById("Ztime").click();}
function svaltoz() {
	var collection = document.getElementsByClassName("custom-combobox-input");
	collection[1].value = collection[0].value;
	}
</script>
<style>
img
    {
     width:150px;
     height:150px;
    }
html
  {
    background: url(images/bg.jpg) no-repeat center center fixed;
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
  }

.spacer
  {
  line-height:10px;
  visibility:hidden;
  }

.custom-combobox 
  {
    position: relative;
  }
  
.custom-combobox-toggle 
  {
    position: absolute;
    top: 0;
    bottom: 0;
    margin-left: -1px;
    padding: 0;
  }
.custom-combobox-input
  {
    margin: 0;
    padding: 0.3em;
  }
</style>

<?PHP include 'header.php' ?>
    
<script>
(function( $ ) 
  {
  $.widget( "custom.combobox",
    {
    _create: function()
      {
        this.wrapper = $( "<span>" )
        .addClass( "custom-combobox" )
        .insertAfter( this.element );
        this.element.hide();
        this._createAutocomplete();
        //this._createShowAllButton();
      },
    _createAutocomplete: function()
      {
        var selected = this.element.children( ":selected" ),
        value = selected.val() ? selected.text() : "";
        this.input = $( "<input>" )
        .appendTo( this.wrapper )
        .val( value )
        .attr( "title", "" )
        .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
        .autocomplete({
          delay: 0,
          minLength: 0,
          source: $.proxy( this, "_source" )
          })
        .tooltip({
          tooltipClass: "ui-state-highlight"
          });
        this._on( this.input,
        {
          autocompleteselect: function( event, ui ) 
          {
            ui.item.option.selected = true;
            this._trigger( "select", event,
              {
                item: ui.item.option
              });
          },
          autocompletechange: "_removeIfInvalid"
        });
      },

      _source: function( request, response ) 
        {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() 
          {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
          return {
            label: text,
            value: text,
            option: this
            };
          }));
        },
 
      _removeIfInvalid: function( event, ui )
        {
        // Selected an item, nothing to do
        if ( ui.item ) 
        {
          return;
        }
       // Search for a match (case-insensitive)
       var value = this.input.val(),
       valueLowerCase = value.toLowerCase(),
       valid = false;
       this.element.children( "option" ).each(function() 
         {
           if ( $( this ).text().toLowerCase() === valueLowerCase ) 
             {
             this.selected = valid = true;
             return false;
             }
         });
         // Found a match, nothing to do
         if ( valid )
         {
           return;
         }
         // Remove invalid value
         this.input
         .val( "" )
         .attr( "title", value + " didn't match any item" )
         .tooltip( "open" );
         this.element.val( "" );
         this._delay(function() 
         {this.input.tooltip( "close" ).attr( "title", "" );}, 2500 );
         this.input.data( "ui-autocomplete" ).term = "";
         },
 
      _destroy: function() 
        {
          this.wrapper.remove();
          this.element.show();
        }
    });
  })

( jQuery );
  $(function() 
  {
    $( "#combobox" ).combobox();
    $( "#combobox2" ).combobox();
    $( "#toggle" ).click(function() 
      {
        $( "#combobox" ).toggle();
      });
  });
</script>

<div class="alert alert-success" style="display:<?php echo $SCgot ?>;">
<strong>Success!</strong> Succesfully entered <?PHP echo $SYgot ?> totalling <?php echo $STgot ?>mg Phe for Stella at <?php echo $SDgot ?>
</div>

<div class="alert alert-success" style="display:<?php echo $ZCgot ?>;">
<strong>Success!</strong> Succesfully entered <?PHP echo $ZYgot ?> totalling <?php echo $ZTgot ?>mg Phe for Zoe at <?php echo $ZDgot ?>
</div>

<div class="alert alert-error" style="display:none;">
<strong>Error!</strong> Tell Daniel that something is messed up
</div>

<div class="ui one column grid" style="display:block">
<div class="row">
<div class="column">
<div class="ui raised green segment">
<div class="ui ribbon purple label">
Stella
</div>
<br><br>
<div class="ui ribbon label">
Target: <?php echo "$SFPTarget"; ?>
</div>
<br><br>
<div class="ui ribbon label">
Today: <?php echo "$SCTotal"; ?>
</div>
<br><br>
<div class="ui ribbon label">
Remaining: <?php echo "$SRemain2"; ?>
</div>
<div class="spacer"><font color="#ffffff">#</font></div>
<div class="ui top right attached label" style="background-color:transparent;">
<img class="circular ui image top right floated ui stella transition" src="images/spic/stellapic.jpg">
</div>

        <div style="display:none;">
        <?php echo "PercentageCSS:" . $SPercCSS . "\">"; ?>
        <?php echo "Percentage". $SPerc2 ."\";>"; ?>
        </div>
<div class="ui fluid form">
<form autocorrect="off" autocapitalize="off" action="insert.php" method="post" name="form1">

		  <div class="column">
		    <div class="ui fluid form segment">
		      <div class="two fields">
		        <div class="field">
		           <div class="ui left icon fluid input">
		             <select id="combobox" name="Stype">
		               <?php echo "$varOptions"; ?>
		             </select>
		             <i class="food icon"></i>
		           </div>
		        </div>
		          <div class="field">
		          <div class="3 fluid blue ui buttons">
  <div class="ui button" onclick="s1change()">1</div>
  <div class="ui button" onclick="s2change()">2</div>
  <div class="ui button" onclick="s3change()">3</div>
</div>
                  </div>
		        <div class="field">
		          <div class="ui left icon fluid input">
		          <input type="tel" name="Samount" id="Samount">
                    <i class="Add icon"></i>
                  </div>
		        </div>
		      <div class="field">
		        <div class="ui left icon fluid input">
		          <input type="time" name="Stime" id="Stime">
                  <i class="Time icon"></i>
                </div>
		      </div>
		    </div>
		  </div>
		</div>
		</div>
		</div>
</div>
</div>
</div>
<div class="spacer"><font color="#ffffff">#</font></div>
<a class="fluid ui blue button" onclick="svaltoz()">Same For Zoe</a>

<div style="opacity:0;"><font color="#ffffff">#</font></div>
<div class="ui one column grid" style="display:block">
<div class="row">
<div class="column">
<div class="ui raised green segment">
<div class="ui ribbon purple label">
Zoe
</div>
<br><br>
<div class="ui ribbon label">
Target: <?php echo "$ZFPTarget"; ?>
</div>
<br><br>
<div class="ui ribbon label">
Today: <?php echo "$ZCTotal"; ?>
</div>
<br><br>
<div class="ui ribbon label">
Remaining: <?php echo "$ZRemain2"; ?>
</div>
<div class="spacer"><font color="#ffffff">#</font></div>
<div class="ui top right attached label" style="background-color:transparent;">
<img class="circular ui image top right floated ui zoe transition" src="images/spic/zoepic.jpg">
</div>

        <div style="display:none;">
        <?php echo "PercentageCSS:" . $ZPercCSS . "\">"; ?>
        <?php echo "Percentage". $ZPerc2 ."\";>"; ?>
        </div>
<div class="ui fluid form">
<form autocorrect="off" autocapitalize="off" action="insert.php" method="post" name="form1">

		  <div class="column">
		    <div class="ui fluid form segment">
		      <div class="two fields">
		        <div class="field">
		           <div class="ui left icon fluid input">
		             <select id="combobox2" name="Ztype" id="Ztype">
		               <?php echo "$varOptions"; ?>
		             </select>
		             <i class="food icon"></i>
		           </div>
		        </div>
		          <div class="field">
		          <div class="3 fluid blue ui buttons">
  <div class="ui button" onclick="z1change()">1</div>
  <div class="ui button" onclick="z2change()">2</div>
  <div class="ui button" onclick="z3change()">3</div>
</div>
                  </div>
		        <div class="field">
		          <div class="ui left icon fluid input">
		          <input type="tel" name="Zamount" id="Zamount">
                    <i class="Add icon"></i>
                  </div> 
		        </div>
		      <div class="field">
		        <div class="ui left icon fluid input">
		          <input type="time" name="Ztime" id="Ztime">
                  <i class="Time icon"></i>
                </div>
		      </div>
		    </div>
		  </div>
		</div>
		</div>
		</div>
</div>
</div>
</div>
</form>
<div class="spacer"><font color="#ffffff">#</font></div>
<a class="fluid ui blue button" onclick="document.forms['form1'].submit(); return false;">Submit Food</a>
<div class="alert alert-info">
</div>
</body>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36060270-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<script type="text/javascript">
    $('.stella.image').transition
    ({
    animation : 'tada',
    duration  : '1.5s',
    complete  : function() {}
  })
    $('.zoe.image').transition
    ({
    animation : 'tada',
    duration  : '1.5s',
    complete  : function(){}
  })
;
    </script>
</html>

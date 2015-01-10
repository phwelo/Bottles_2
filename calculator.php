<?php
$con = mysql_connect("localhost","root","ALsk1029");
mysql_select_db("bottles", $con);

//This builds the list of food from database
$query = "SELECT * FROM food ORDER BY Name";
$result = mysql_query($query) or die(mysql_error());
$varOptions = "";
while($row = mysql_fetch_array($result)){
	$varBuild ="<option  value=" . $row['ID']. ">". $row['Name']. "</option>";
	$varOptions = $varOptions . $varBuild;
	}

//This gets stella's phe for today so far
$queryST = "SELECT type, SUM(amount) FROM stella WHERE (DATE_SUB(CURDATE(),INTERVAL 0 DAY) <= time AND type<>'M')"; 
$resultST = mysql_query($queryST) or die(mysql_error());
while($row = mysql_fetch_array($resultST)){
	$STotal = $row['SUM(amount)'];
	}
if (empty($STotal)){
$STotal = 0;
}

//This gets zoe's phe for the day so far
$queryZT = "SELECT type, SUM(amount) FROM zoe WHERE (DATE_SUB(CURDATE(),INTERVAL 0 DAY) <= time AND type<>'M')"; 
$resultZT = mysql_query($queryZT) or die(mysql_error());
while($row = mysql_fetch_array($resultZT)){
	$ZTotal = $row['SUM(amount)'];
	}
if (empty($ZTotal)){
$ZTotal = 0;
}

//Subtract from current limit for remaining
$ZRemain = 300 - $ZTotal;
$SRemain = 300 - $STotal;
?>
<?PHP include 'header.php' ?>

<script>
function getPhe(form)
{
var varFood = form.food.value;
var varFoodNum = form.foodnum.value;

if (varFood=="")
{
		document.getElementById("txtFood").innerHTML="Poop";
		return;
} 
if (window.XMLHttpRequest)
{
		xmlhttp=new XMLHttpRequest();
}
xmlhttp.onreadystatechange=function()
{
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		varResult=xmlhttp.responseText;
		var varSplitted=varResult.split(" ");
    var varFood1 = parseFloat(varSplitted[0]) * 1;
    var varFood2 = parseFloat(varSplitted[0]) * 2;
    var varFood3 = parseFloat(varSplitted[0]) * 3;
    var varFood4 = parseFloat(varSplitted[0]) * 4;
    var varFood5 = parseFloat(varSplitted[0]) * 5;
    var varFood6 = parseFloat(varSplitted[0]) * 6;
    var varFood7 = parseFloat(varSplitted[0]) * 7;
    var varFood8 = parseFloat(varSplitted[0]) * 8;
    var varFood9 = parseFloat(varSplitted[0]) * 9;
    var varFood10 = parseFloat(varSplitted[0]) * 10;
		//var varMathed = varFoodPhe;
		document.getElementById("span10").innerHTML =varFood1.toFixed(1);
    document.getElementById("span11").innerHTML =varFood2.toFixed(1);
    document.getElementById("span12").innerHTML =varFood3.toFixed(1);
    document.getElementById("span13").innerHTML =varFood4.toFixed(1);
    document.getElementById("span14").innerHTML =varFood5.toFixed(1);
    document.getElementById("span15").innerHTML =varFood6.toFixed(1);
    document.getElementById("span16").innerHTML =varFood7.toFixed(1);
    document.getElementById("span17").innerHTML =varFood8.toFixed(1);
    document.getElementById("span18").innerHTML =varFood9.toFixed(1);
    document.getElementById("span19").innerHTML =varFood10.toFixed(1);
		//document.getElementById("span5").innerHTML =varMathed.toFixed(1);
		//var ZRemain = document.getElementById("ZRemain").innerHTML;
		//var ZRemain = parseFloat(ZRemain);
		//var SRemain = document.getElementById("SRemain").innerHTML;
		//var SRemain = parseFloat(SRemain);
		//document.getElementById("span10").innerHTML =varMathed.toFixed(1);
		//document.getElementById("span6").innerHTML =ZRemain.toFixed(1)+"-"+varMathed+"=";
		//document.getElementById("span7").innerHTML =(parseFloat(ZRemain).toFixed(1) - varMathed).toFixed(1);
		//document.getElementById("span8").innerHTML =SRemain.toFixed(1)+"-"+varMathed+"=";
		//document.getElementById("span9").innerHTML =(parseFloat(SRemain).toFixed(1) - varMathed).toFixed(1);
	}
}
xmlhttp.open("GET","getphe.php?q="+varFood,true);
xmlhttp.send();
}
</script>

<style>

.custom-combobox 
  {
    position: relative;
    display: inline-block;
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
        .attr( "onchange", "getPhe(this.form)" )
        .attr( "onblur", "getPhe(this.form)" )
        .attr( "onfocus", "getPhe(this.form)" )
        .attr( "onforminput", "getPhe(this.form)" )
        .attr( "type", "text" )
        

        .addClass( "fluid custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
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

    _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Show All Items" )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .mousedown(function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .click(function() {
            input.focus();
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
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
</head>

<body>

<div class="ui two column top aligned relaxed grid basic segment">
  <div class="column">
    <div class="ui fluid inverted form segment">
      <form autocorrect="off" autocapitalize="off" class="well" name="myform" action="" method="get">
        <div class="field">
          <label>Food</label>
          <select id="combobox" class="fluid" name="food" onblur="getPhe(this.form)" onchange="getPhe(this.form)">
            <?php echo $varOptions;?>
          </select>
        </div>
        <input type="hidden" name="foodnum">
      </form>
    </div>
    </div>
  <div class="ui vertical divider">
    <i class="food icon"></i>
  </div>
  <div class="top aligned column">
    <table class="ui large fluid purple inverted table segment">
      <thead>
        <tr><th>Number</th>
        <th>mg Phe</th>
        </tr>
      </thead>
      <tbody>
      <tr>
        <td>1</td>
        <td><span id="span10">0</span></td>
      </tr>
      <tr>
          <td>2</td>
        <td><span id="span11">0</span></td>
      </tr>
      <tr>
        <td>3</td>
        <td><span id="span12">0</span></td>
      </tr>
      <tr>
        <td>4</td>
        <td><span id="span13">0</span></td>
      </tr>
      <tr>
        <td>5</td>
        <td><span id="span14">0</span></td>
      </tr>
      <tr>
        <td>6</td>
        <td><span id="span15">0</span></td>
      </tr>
      <tr>
        <td>7</td>
        <td><span id="span16">0</span></td>
      </tr>
      <tr>
        <td>8</td>
        <td><span id="span17">0</span></td>
      </tr>
      <tr>
        <td>9</td>
        <td><span id="span18">0</span></td>
      </tr>
      <tr>
        <td>10</td>
        <td><span id="span19">0</span></td>
      </tr>
    </tbody>
    </table>
  </div>
</div>
</div>
</body>
</html>

<?php
$girl = $_POST["who"];

switch ($girl) {
    case "stella":
      $uploadfile = '/var/www/wordpress/bottles/images/spic/stellapic.jpg';
    break;
    case "zoe":
      $uploadfile = '/var/www/wordpress/bottles/images/spic/zoepic.jpg';
    break;
}

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
  header( 'Location: http://comeplaywithus.org/bottles/settings.php?success' ) ;
  $im = new Imagick("$uploadfile");
  $im->scaleImage(200, 200, true);
  $im->writeImage($uploadfile);
} else {
  header( 'Location: http://comeplaywithus.org/bottles/settings.php?failure' ) ;
    
}
?> 
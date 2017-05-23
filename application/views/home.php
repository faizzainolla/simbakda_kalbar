<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/styles/style.css" />

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>
</head>

<body>

<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
  
   	
			if(isset($contents)){
            	echo $contents;
                
            }
	
?>

</body>
</html>
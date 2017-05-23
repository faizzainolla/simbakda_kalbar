<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html>
<head>
	<title>LOGIN SIMBAKDA</title>
		<meta charset="utf-8">
		<link href="<?php echo base_url();?>public/styles/style_login.css" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>

</head>
<body>
	 <!-----start-main---->
	 <div class="main">
			<div class="login-form">
			<h1>SIGN IN</h1>
					<div class="head">
						<img src="<?php echo base_url();?>public/images/kalbar.png" style="width:110;height:auto" alt=""/>
					</div>
					<?php
                	if(isset($login_info))
					{
			   			echo "<span style='color:red;padding:3px;text-align:center;font-size:10px;'>";
			   			echo $login_info;
			   			echo '</span>';
					}else{
					?> &nbsp; <?php
					}
				?>
				<form role="form" method="post" action="<?php echo base_url();?>index.php/welcome/login">
						<input type="text" class="text" value="USERNAME" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'USERNAME';}" >
						<input type="password" value="Password" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Password';}">
						<input hidden="true" type="text" value='<?php date('Y')?>' id="ta" name="ta">
						<div class="submit">
							<input type="submit" value="LOGIN" >
					</div>	
				</form>
			<?php echo form_close(); ?>
			</div>
			<!--//End-login-form-->
			 <!-----start-copyright---->
   					<div class="copy-right" >
						<p>[Versi.3.0] Copyright &copy; 2017<a href="http://msmgroups.com"> Murfa Surya Mahardika</a></p> 
					</div>
				<!-----//end-copyright---->
		</div>
			 <!-----//end-main---->
		 		
</body>
</html>
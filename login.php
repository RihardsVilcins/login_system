<?php 
	require_once 'core/init.php';

?>

		<div class="form-container sign-in-container">
		    <form action="" method="POST">
		        <h1 class="element">Login</h1>
		        <img id="imgLogo" src="img/logo.jpg">
		        <div class="underline element"> </div>
				        <input type="email" class="name" name="email" placeholder="Email"/>
				        <div style="color:#f44368;" id="star_login_email" class="star">*</div>
				        <input type="password" class="password_login" name="password" placeholder="Password"/>
				        <div style="color:#f44368;" id="star_login_pass" class="star">*</div>
				        <a style="color: #8495b7;" class="element" href="#">Forgot?</a>
			        <button id="login" class="element" type="submit" name="submit_login" value="logIn">LOGIN</button>
    		</form>
		</div>

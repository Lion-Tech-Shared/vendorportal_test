<form action="validate.php" method="POST">
								 
                                <div class="form-group <?PHP echo $error; ?>" align="center">
									<input id="captchaId" type="hidden" class="form-control" placeholder="Password" name="captchaId" value="<?PHP echo $captchaId; ?>" required>
								</div>

                                <div class="form-group <?PHP echo $error; ?>" align="center">
									<img id="captcha"  src="securimage/securimage_show.php" alt="captcha_image"/>
                                    <a href="#" onClick="document.getElementById('captcha').src='securimage/securimage_show.php?' + Math.random();return false">[Different Image]</a>
								</div>
                                <div class="form-group <?PHP echo $error; ?>">
                                	<label>Enter Code:</label>
									<input type="text"  name="captcha_code" value="" required />
								</div>                                
								<hr />                               
								<button name="lgin" type="submit" class="btn btn-primary btn-block">
									<i class="fa fa-unlock fa-fw"></i>
									<strong>Login</strong>
								</button>
</form>
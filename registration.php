<?php
	session_start();
	include_once("includes/languages/" . $_SESSION['lang'] . ".php");
	include_once("includes/language_helper.php");
	include_once("header.php");
	include_once("Database/connect.php");

	if(isset($_POST['submit']))
	{
		$count="";
	 	$name=$_POST['nm'];
	  	$surnm=$_POST['surnm'];
	    $unm=$_POST['unm'];
	 	$email=$_POST['email'];
	 	$pswd=$_POST['pswd'];
	 	$mo=$_POST['mo'];
	    $adrs=$_POST['adrs'];
	  	$q=mysqli_query($con,"select unm from registration where unm='$unm' ");
		if(mysqli_num_rows($q)>0)
		{
					echo "<script> alert('" . t('username_exists') . "');</script>";	
		}
		else
		{
			// Hash the password before storing
			$hashed_password = password_hash($pswd, PASSWORD_DEFAULT);
			
			// Insert with explicit column names that match the database schema
			$qry = mysqli_query($con, "INSERT INTO registration (nm, surnm, unm, email, pswd, mo, adrs) 
			                     VALUES ('$name', '$surnm', '$unm', '$email', '$hashed_password', '$mo', '$adrs')");
			if($qry)
			{
				
				$qry1=mysqli_query($con,"select id from registration where unm='$unm'");
				while($row=mysqli_fetch_row($qry1))
				{
						$qry2=mysqli_query($con,"insert into login values(NULL,'$unm','$pswd')");
						if($qry2)
						{
							echo "<script> alert('" . t('registration_success') . "');</script>";
							echo "<script> alert('" . t('login_redirect') . "');</script>";
							echo "<script> window.location.assign('login.php')</script>";	
						}		
					
				}
			}
		}
	}
	
?>
	
	<div class="banner about-bnr">
		<div class="container">
		</div>
	</div>
	<div class="codes">
		<div class="container"> 
		<h2 class="w3ls-hdg" align="center"><?php echo t('registration'); ?></h2>
				  
	<div class="grid_3 grid_4">
				<div class="tab-content">
					<div class="tab-pane active" id="horizontal-form">
						<form class="form-horizontal" action="" method="post" name="reg" onsubmit="return validate(this)">
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label"><?php echo t('first_name'); ?></label>
								<div class="col-sm-8">
									<input type="text" class="form-control1" pattern="[A-Za-z\s]{2,30}" title="Only Letter For Name"  name="nm" id="focusedinput" placeholder="<?php echo t('first_name'); ?>" required="">
								</div>
							</div>
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label"><?php echo t('last_name'); ?></label>
								<div class="col-sm-8">
									<input type="text" class="form-control1"  name="surnm" pattern="[A-Za-z\s]{2,30}" id="focusedinput" placeholder="<?php echo t('last_name'); ?>" required="">
								</div>
							</div>
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label"><?php echo t('username'); ?></label>
								<div class="col-sm-8">
									<input type="text" class="form-control1"  name="unm" id="focusedinput" placeholder="<?php echo t('username'); ?>" required="">
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-sm-2 control-label label-input-sm"><?php echo t('email'); ?></label>
								<div class="col-sm-8">
									<input type="email" class="form-control1 input-sm" 
									   pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" 
									   title="<?php echo t('enter_valid_email'); ?>" 
									   name="email" 
									   id="email" 
									   placeholder="<?php echo t('email_placeholder'); ?>"
									   required>
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassword" class="col-sm-2 control-label"><?php echo t('password'); ?></label>
								<div class="col-sm-8">
									<input type="password" class="form-control1" name="pswd" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8.}" title="Must Cointain At Least One Number & One Uppercase & One Lowercase Letter, & At Least 8 Or More Characters" id="inputPassword" placeholder="<?php echo t('password'); ?>" required="">
								</div>
							</div>
							<div class="form-group">
								<label for="smallinput" class="col-sm-2 control-label label-input-sm"><?php echo t('mobile'); ?></label>
								<div class="col-sm-8">
									<input type="tel" class="form-control1 input-sm" name="mo" 
									   pattern="[0-9]{10,15}" 
									   title="<?php echo t('enter_valid_phone'); ?>" 
									   maxlength="15" 
									   id="smallinput" 
									   placeholder="<?php echo t('mobile_placeholder'); ?>" 
									   required
								/>
							</div>
						</div>
						<script>
							// Format nomor telepon sambil mengetik
							document.getElementById('smallinput').addEventListener('input', function(e) {
								let value = e.target.value.replace(/\D/g, '');
								e.target.value = value;
							});
						</script>
							<div class="form-group">
								<label for="txtarea1" class="col-sm-2 control-label"><?php echo t('address'); ?></label>
								<div class="col-sm-8"><textarea name="adrs" id="txtarea1" cols="50" rows="4" class="form-control1"></textarea></div>
							</div>
					<div class="contact-w3form" align="center">
					<input type="submit" name="submit" value="<?php echo t('submit'); ?>">
					</div>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
			<?php
				include_once("footer.php");
			?>
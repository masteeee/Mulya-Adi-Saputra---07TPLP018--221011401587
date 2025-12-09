<?php
	session_start();
	include_once("Database/connect.php");
	include_once("includes/language_helper.php");

	$error_message = '';
	$success_message = '';

	// Process login form
	if(isset($_POST['submit'])) {
		$count = 0;
		$uname = trim($_POST['unm']);
		$pswd = $_POST['pswd'];
		
		// Validate username is not empty
		if(empty($uname)) {
			$error_message = t('username_required');
			$count++;
		}
		
		// Validate password format
		if(!preg_match('/^[a-zA-Z\w]{6,18}$/', $pswd)) {
			$error_message = t('password_requirements');
			$count++;
		}   
		
		if($count === 0) {
			// Use prepared statement to prevent SQL injection
			$stmt = $con->prepare("SELECT * FROM login WHERE unm = ? AND pswd = ?");
			$stmt->bind_param("ss", $uname, $pswd);
			$stmt->execute();
			$result = $stmt->get_result();
			
			if($result->num_rows > 0) {
				$_SESSION['uname'] = $uname;
				$_SESSION['loggedin'] = true;
				
				// Clear output buffer and redirect
				while (ob_get_level()) {
					ob_end_clean();
				}
				header('Location: index.php');
				exit();
			} else {
				$error_message = t('login_error');
			}
			$stmt->close();
		}
	}
	
	// Include header after all processing is done
	include_once("header.php");
?>
<div class="banner about-bnr">
		<div class="container">
			<h2><?php echo t('login'); ?></h2>
		</div>
	</div>
	<?php if (!empty($error_message)): ?>
    <div class="alert alert-danger text-center">
        <?php echo $error_message; ?>
    </div>
<?php endif; ?>

<?php if (!empty($success_message)): ?>
    <div class="alert alert-success text-center">
        <?php echo $success_message; ?>
    </div>
<?php endif; ?>

<div class="codes">
    <div class="container"> 
        <h2 class="w3ls-hdg text-center"><?php echo t('login'); ?></h2>
        
        <div class="grid_3 grid_4">
            <div class="tab-content">
                <div class="tab-pane active" id="horizontal-form">
                    <form class="form-horizontal" action="" method="post">
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label"><?php echo t('username'); ?></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control1" name="unm" id="focusedinput" 
                                       placeholder="<?php echo t('Username'); ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword" class="col-sm-2 control-label"><?php echo t('password'); ?></label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control1" name="pswd" id="inputPassword" 
                                       placeholder="<?php echo t('Password'); ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember_me"> <?php echo t('remember_me'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="contact-w3form text-center">
                            <input type="submit" name="submit" value="<?php echo t('login_now'); ?>" class="btn btn-primary">
                        </div>
                    </form>
                    <br/>
                    <div class="text-center">
                        <h5><?php echo t('dont_have_account'); ?> 
                            <a href="registration.php"><?php echo t('register_here'); ?></a>
                        </h5>
                        <p>
                            <a href="forgot_password.php"><?php echo t('forgot_password'); ?></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
				<?php
				include_once("footer.php");
			?>
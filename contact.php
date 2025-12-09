<?php
session_start();
include('Database/connect.php');
include("includes/languages/" . $_SESSION['lang'] . ".php");
include("includes/language_helper.php");

if(isset($_POST['submit']))
{
	$a = $_POST['Name'];
	$b = $_POST['Email'];
	$c = $_POST['Message'];
	$q = mysqli_query($con,"insert into feedback values(NULL,'$a','$b','$c')");
	if($q)
	{
		echo "<script>alert('" . t('message_sent') . "');</script>";
		echo "<script>window.location.assign('index.php');</script>";
	}
	else
	{
		echo "<script>alert('" . t('message_not_sent') . "');</script>";
	}
}


?>
<?php
	include_once("header.php");
?>
	<!-- //header -->
	<div class="banner about-bnr w3-agileits">
		<div class="container">
		</div>
	</div>
	<!-- contact -->
	<div class="contact">
		<div class="container">
			<h2 class="w3ls-title1"><?php echo t('contact_us'); ?></h2>
			<div class="contact-agileitsinfo">
				<div class="col-md-8 contact-grids">
					<p><?php echo t('contact_description'); ?></p><br />
					<h5><?php echo t('contact_subheading'); ?></h5>	
					<div class="contact-w3form">
						<h3 class="w3ls-title1"><?php echo t('drop_us_a_line'); ?></h3>
						<form action="#" method="post"> 
							<div class="form-group">
								<label class="form-control"><?php echo t('your_name'); ?></label>
								<input type="text" name="Name" placeholder="" required="" class="form-control">
							</div>
							<div class="form-group">
								<label class="form-control"><?php echo t('your_email'); ?></label>
								<input type="email" name="Email" placeholder="" required="" class="form-control">
							</div>
							<div class="form-group">
								<label class="form-control"><?php echo t('message'); ?></label>
								<textarea name="Message" placeholder="" required="" class="form-control"></textarea>
							</div>
							<input type="submit" name="submit" value="<?php echo t('send'); ?>">
						</form>
					</div>
				</div>
				<div class="col-md-4 contact-grids">
					<div class="cnt-address">
						<h3 class="w3ls-title1"><?php echo t('address'); ?></h3>
						<h4><?php echo t('classic_events'); ?></h4>
						<p><?php echo t('kalwad_road'); ?>,
							<span></span>
							<?php echo t('rajkot'); ?>.
						</p>
						<h4><?php echo t('get_in_touch'); ?></h4>
						<p><?php echo t('javgal_patel'); ?>: +91 90333 36811
							<span><?php echo t('mohit_patel'); ?>: +91 96870 00004 </span>
							E-mail: <a href="mailto:info@example.com"><?php echo t('info@classicevents.in'); ?></a>
							E-mail: <a href="mailto:info@example.com">info@classicevents.in</a>
						</p>
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
	<!-- //contact -->
	<!-- footer -->
	<?php
		include_once("footer.php");
	?>
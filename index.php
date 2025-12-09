<?php
 	include_once("header.php");
	include_once("slider.php");
	include_once("Database/connect.php");
?>
	<!-- modal -->
	<div class="modal about-modal w3-agileits fade" id="myModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>						
				</div> 
				<div class="modal-body">
					<img src="images/cs_birthday.JPG" alt=""> 
					<p><?php echo t('about_us_description'); ?></p>
		</div> 
			</div>
		</div>
	</div>
	<!-- //modal -->  
	<!-- banner-bottom -->
	<div class="w3-agile-text">
		<div class="container"> 
			<h2><?php echo t('making_moments_memorable'); ?></h2>
		<!--	<p>Vivamus vitae elementum velit. Morbi convallis nisi velit, maximus consequat lacus sagittis et. Sed at fringilla erat, id mollis eros.</p>-->
		</div>
	</div>
	<!-- //banner-bottom -->
	<!-- features -->
	<div class="features">
		<div class="container">
			<div class="col-md-4 feature-grids">
				<h3 class="w3ltitle"><?php echo t('what_we_are'); ?></h3>
				<p><?php echo t('company_description_1'); ?></p>
				<p><?php echo t('company_description_2'); ?></p>
				<div class="w3ls-more">
					<a href="#" class="effect6" data-toggle="modal" data-target="#myModal"><span><?php echo t('read_more'); ?></span></a>
				</div>
			</div>
			<div class="col-md-4 feature-grids">
				<img src="images/cs_event.jpg" alt=""/>
			</div>
			<div class="col-md-4 feature-grids">
				<h3 class="w3ltitle"><?php echo t('our_specifications'); ?></h3>
				<div class="w3ls-pince">
					<div class="pince-left">
						<h5>01</h5>
					</div>
					<div class="pince-right">
						<h4><?php echo t('designer_wedding'); ?></h4>
						<p><?php echo t('designer_wedding_desc'); ?></p>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="w3ls-pince">
					<div class="pince-left">
						<h5>02</h5>
					</div>
					<div class="pince-right">
						<h4><?php echo t('destination_wedding'); ?></h4>
						<p><?php echo t('destination_wedding_desc'); ?></p>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="w3ls-pince">
					<div class="pince-left">
						<h5>03</h5>
					</div>
					<div class="pince-right">
						<h4><?php echo t('theme_wedding'); ?></h4>
						<p><?php echo t('theme_wedding_desc'); ?></p>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
	<!-- //features -->
	<?php
		include("projects.php");
		?>
	
	<!-- services -->
	<div class="services">
		<div class="container">
			<h3 class="w3ltitle"><?php echo t('our_services'); ?></h3>
			<div class="services-agileinfo">
				<div class="servc-icon">
					<a href="wedding.php" class="agile-shape"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
					<p class="serw3-agiletext"><?php echo t('wedding'); ?></p>
					</a>
				</div>
				<div class="servc-icon">
					<a href="anniversary.php" class="agile-shape"><span class="glyphicon glyphicon-glass" aria-hidden="true"></span>
					<p class="serw3-agiletext"><?php echo t('anniversary'); ?></p>
					</a>
				</div>
				<div class="servc-icon">
					<a href="birthday.php" class="agile-shape"><span class="glyphicon fa fa-gift" aria-hidden="true"></span>
					<p class="serw3-agiletext"><?php echo t('birthday_party'); ?></p>
					</a>
				</div>
				<div class="servc-icon">
					<a href="other_events.php" class="agile-shape"><span class="glyphicon glyphicon-music" aria-hidden="true"></span>
					<p class="serw3-agiletext"><?php echo t('enjoyments'); ?></p>
					</a>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
	<!-- //services -->
	
	<?php
		include_once("footer.php");
	?>
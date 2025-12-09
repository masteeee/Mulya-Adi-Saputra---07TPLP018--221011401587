<?php
	include_once("header.php");
?>
	<!-- //header -->
	<div class="banner about-bnr">
		<div class="container">
		</div>
	</div>
	<!-- about -->
	<div class="about">
		<div class="container">
			<h3 class="w3ls-title1"><span><?php echo t('birth'); ?></span><?php echo t('day'); ?></h3>
			<div class="about-agileinfo w3layouts">
				<div class="col-md-8 about-wthree-grids grid-top">
					<h4><?php echo t('birthday_heading'); ?></h4>
					<p class="top"><?php echo t('birthday_desc1'); ?></p>
					<p><?php echo t('birthday_desc2'); ?></p>		
					<div class="about-w3agilerow">
						<div class="col-md-4 about-w3imgs">
							<img src="images/cs_birthday2.jpg" alt="" class="img-responsive zoom-img"/>
						</div>
						<div class="col-md-4 about-w3imgs">
							<img src="images/cs_birthday4.jpg" alt=""  class="img-responsive zoom-img"/>
						</div>
						<div class="col-md-4 about-w3imgs">
							<img src="images/cs_birthday5.jpg" alt=""  class="img-responsive zoom-img"/>
						</div>
						<div class="clearfix"> </div>
					</div>
				</div>
				<?php 
					include_once("sidebar.php");
				?>
				</div>	
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
	<!-- //about -->
	<!-- about-slid -->
	<div class="about-slid agileits-w3layouts"> 
		<div class="container">
			<div class="about-slid-info"> 
				<h2><?php echo t('slide_heading'); ?></h2>
				<p><?php echo t('slide_desc'); ?></p>
			</div>
		</div>
	</div>
	<!-- //about-slid -->
	<!-- about-services -->
	<br/><br />
	<div class="about-servcs"> 
		<div class="container">
			<h3 class="w3ls-title1"><?php echo t('specialize_heading'); ?></h3>
			<h5><?php echo t('services_heading'); ?></h5>
			<div class="servcs-info">
				<div class="col-md-6 sevcs-grids">
					<h4><span>01.</span> <?php echo t('birthday_parties'); ?></h4>
					<p><?php echo t('birthday_parties_desc'); ?></p>			
				</div>
				<div class="col-md-6 sevcs-grids"> 
					<h4><span>02.</span> <?php echo t('theme_party'); ?></h4>
					<p><?php echo t('theme_party_desc'); ?></p>			
				</div>
				<div class="clearfix"> </div>
			</div>
			
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
	<!-- //about-services -->
	<!-- footer -->
	<?php
		include_once("footer.php");
	?>
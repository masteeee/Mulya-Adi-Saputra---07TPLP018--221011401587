<?php
	include_once("header.php");
?>
	<!-- //header -->
	<div class="banner about-bnr">
		<div class="container">
			<h2><?php echo t('about_us'); ?></h2>
		</div>
	</div>
	<!-- about -->
	<div class="about">
		<div class="container">
			<h3 class="w3ls-title1"><?php echo t('about'); ?> <span><?php echo t('us'); ?></span></h3>
			<div class="about-agileinfo w3layouts">
				<div class="col-md-8 about-wthree-grids grid-top">
					<h4><?php echo t('about_tagline'); ?></h4>
					
					<p class="top"><?php echo t('about_description_1'); ?></p>
					<p><?php echo t('about_description_2'); ?></p>
			
					<div class="about-w3agilerow">
						<div class="col-md-4 about-w3imgs">
							<img src="images/cs_dj-sound - Copy.JPG" alt="<?php echo t('dj_sound'); ?>" class="img-responsive zoom-img"/>
						</div>
						<div class="col-md-4 about-w3imgs">
							<img src="images/cs_theme.jpg" alt="<?php echo t('theme_decoration'); ?>" class="img-responsive zoom-img"/>
						</div>
						<div class="col-md-4 about-w3imgs">
							<img src="images/cs_birthday3.jpg" alt="<?php echo t('birthday_party'); ?>" class="img-responsive zoom-img"/>
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
				<h2><?php echo t('about_section_title'); ?></h2>
				<p><?php echo t('about_section_content'); ?></p>
			</div>
		</div>
	</div>
	<!-- //about-slid -->
	
	<!-- footer -->
	<?php
		include_once("footer.php");
	?>
<!-- footer -->
	<div class="footer">
		<div class="container">
			<h3 class="w3ltitle"><span><?php echo t('get_in'); ?> </span><?php echo t('touch'); ?></h3>
			<div class="footer-agileinfo">
				<div class="col-md-6 footer-left">
					<h5 style="color:#FFFFFF"><?php echo t('celebrate_special_day'); ?></h5>
					<div class="w3ls-more">
						<a href="gallery.php" class="effect6"><?php echo t('book_your_event'); ?></a>
					</div>
				</div>
				<div class="col-md-6 footer-right">
					<div class="address">
						<div class="col-xs-2 contact-grdl">
							<span class="glyphicon glyphicon-phone" aria-hidden="true"></span>
						</div>
						<div class="col-xs-10 contact-grdr">
							<p>+62 98237 232</p>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="address">
						<div class="col-xs-2 contact-grdl">
							<span class="glyphicon glyphicon-send" aria-hidden="true"></span>
						</div>
						<div class="col-xs-10 contact-grdr">
							<p><?php echo t('address_line'); ?></p>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="address">
						<div class="col-xs-2 contact-grdl">
							<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
						</div>
						<div class="col-xs-10 contact-grdr">
							<p><a href="mailto:info@kevents.com">info@KEevents.in</a></p>
						</div>
						<div class="clearfix"> </div>
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
		<!-- copy-right -->
		<div class="copy-right" style="padding: 10px 0; margin-top: 20px;">
			<div class="container" style="text-align: center;">
				<p style="margin: 0; font-size: 14px; color: #fff;">
					&copy; <?php echo date('Y'); ?> <?php echo t('copyright_text'); ?>
				</p>
			</div>
		</div>
		<!-- //copy-right -->
	</div>
	<!-- //footer --> 
	<!-- start-smooth-scrolling-->
	<script type="text/javascript" src="js/move-top.js"></script>
	<script type="text/javascript" src="js/easing.js"></script>	
	<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(".scroll").click(function(event){		
					event.preventDefault();
			
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
				});
			});
	</script>
	<!-- //end-smooth-scrolling -->	
	<!-- smooth-scrolling-of-move-up -->
	<script type="text/javascript">
		$(document).ready(function() {
			/*
			var defaults = {
				containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 1200,
				easingType: 'linear' 
			};
			*/
			
			$().UItoTop({ easingType: 'easeOutQuart' });
			
		});
	</script>
	<!-- //smooth-scrolling-of-move-up -->
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.js"></script>
</body>
</html>
<?php
	session_start();
	include_once("includes/languages/" . $_SESSION['lang'] . ".php");
	include_once("includes/language_helper.php");
	include_once("header.php");
?>
<link rel="stylesheet" href="css/lightbox.css">
<link href="css/font-awesome.css" rel="stylesheet"> 
	<!-- //header -->
	<div class="banner about-bnr">
		<div class="container">
		</div>
	</div>
	
	<!-- gallery -->
	<div class="gallery-top">
		<!-- container -->
		<div class="container">
			<h2 class="w3ls-title1"><?php echo t('our_gallery'); ?> <span><?php echo t('gallery_title'); ?></span></h2>
			<div class="grid_3 grid_5"><br /><br/>
				<div class="but_list w3layouts">
					<h1>
						<a href="gallery.php"><span class="label label-default"><?php echo t('wedding'); ?></span></a>
						<a href="bday_gal.php"><span class="label label-primary"><?php echo t('birthday_party'); ?></span></a>
						<a href="anni_gal.php"><span class="label label-success"><?php echo t('anniversary'); ?></span></a>
						<a href="other_gal.php"><span class="label label-warning"><?php echo t('entertainment'); ?></span></a>
					</h1>
			</div></div>
			<div class="gallery-grids-top">
				<div class="gallery-grids">
				<?php
						include_once("Database/connect.php");
						$qry="select * from otherevent";
						$res=mysqli_query($con,$qry)or die("can't fetch data");
						while($row=mysqli_fetch_array($res)){
				?>
				<div class="col-md-3 gallery-grid">
						<a class="example-image-link" href="images/<?php echo $row['img']; ?>" data-lightbox="example-set" data-title=""><img class="example-image" src="images/<?php echo $row['img']; ?>" alt="" height="200"/></a>
						<a href="book_other.php?id=<?php echo $row['id']; ?>"><?php echo "<input type='button' value='" . t('book_now') . "' class='btn my'/>" ;?></a><br />
					</div>
					<?php } ?>
					<div class="clearfix"> </div>
				</div>
				<script src="js/lightbox-plus-jquery.min.js"></script>
			</div>
			<div class="clearfix"> </div>
		</div>
		<!-- //container -->
	</div>				
	<!-- //gallery -->
	<!-- footer -->
	<?php
		include_once("footer.php");
	?>
<!--
Author: CLASSIC EVENTS
-->
<!DOCTYPE html>
<html lang="en">
<head><link rel="shortcut icon" href="images/Logo.png">
        
<title>K Events</title>
<!-- meta tags -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Light Fixture Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
	SmartPhone Compatible web template, free WebDesigns for Nokia, Samsung, LG, Sony Ericsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- //meta tags -->
<?php
// Include language helper
require_once 'includes/language_helper.php';
?>
<!-- Custom Theme files -->
<link href="css/bootstrap.css" type="text/css" rel="stylesheet" media="all">
<link href="css/style.css" type="text/css" rel="stylesheet" media="all">
<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />
<link href="css/font-awesome.css" rel="stylesheet">
<!-- Flag Icons CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css" />
<!-- //Custom Theme files -->
<style>
/* Language Selector Styles */
.language-selector {
    display: inline-block;
    margin: 0 10px;
}

.language-selector .dropdown-menu {
    min-width: 120px;
    border-radius: 0;
    margin-top: 5px;
    border: 1px solid #e6e6e6;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.language-selector .dropdown-menu li a {
    padding: 8px 15px;
    color: #333;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.language-selector .flag-icon {
    margin-right: 8px;
    width: 1.2em;
    height: 1em;
    border-radius: 2px;
}

.language-selector .dropdown-menu > li.active > a {
    background-color: #f5f5f5;
    color: #000;
    font-weight: bold;
}

.language-selector .dropdown-menu > li > a:hover {
    background-color: #f0f0f0;
}

.language-selector .dropdown-menu li a:hover {
    background-color: #f5f5f5;
    color: #000;
}

.language-selector .btn {
    background: transparent;
    border: 1px solid #ddd;
    color: #fff;
    padding: 5px 10px;
    border-radius: 3px;
    font-size: 13px;
}

.language-selector .btn:hover {
    background: rgba(255,255,255,0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .language-selector {
        display: block;
        margin: 10px 0;
        text-align: center;
    }
    
    .language-selector .dropdown-menu {
        position: static;
        float: none;
        width: 100%;
        margin-top: 0;
        border: none;
        box-shadow: none;
    }
}
</style>
<!-- js -->
<script src="js/jquery-1.11.1.min.js"></script> 
<!-- //js --> 
<!-- web fonts -->
<link href="//fonts.googleapis.com/css?family=Abel" rel="stylesheet">
<link href="//fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
<!-- //web fonts -->
</head>
<body>
	<!-- header -->
	<div class="header">
		<nav class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header navbar-left">
					<h1>
					<img src="images/Logo.png"></h1>
				</div>
				
				<!-- navigation --> 
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Eksentrik Organizer</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<div class="header-right">
					<div class="agileits-topnav">
						<ul>
							<li><span class="glyphicon glyphicon-earphone"></span> ++62 1234 5678</li>
							<li><a class="email-link" href="mailto:example@mail.com"> <span class="glyphicon glyphicon-envelope"></span> info@KEevents.in </a></li>
							<li class="language-selector">
								<div class="dropdown">
									<button class="btn btn-default dropdown-toggle" type="button" id="languageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
															<?php 
															$currentLang = $_SESSION['lang'] ?? 'en';
															echo $currentLang === 'en' 
															? '<i class="flag-icon flag-icon-gb"></i> ' 
															: '<i class="flag-icon flag-icon-id"></i> ';
															echo strtoupper($currentLang);
															?>
															<span class="caret"></span>
														</button>
														<ul class="dropdown-menu" aria-labelledby="languageDropdown">
															<li class="<?php echo $currentLang === 'en' ? 'active' : ''; ?>"><a href="?lang=en"><i class="flag-icon flag-icon-gb"></i> English (EN)</a></li>
															<li class="<?php echo $currentLang === 'id' ? 'active' : ''; ?>"><a href="?lang=id"><i class="flag-icon flag-icon-id"></i> Bahasa Indonesia (ID)</a></li>
														</ul>
													</div>
												</li>
							<li class="social-icons"> 
							<?php
							@session_start();
							if(isset($_SESSION['uname']))
							{
								echo "<a href='gallery.php'><button class='btn default'>" . t('book_event') . "</button></a> ";
								echo "<a href='logout.php'><button class='btn warning'>" . t('logout') . "</button></a>";
							}
							else
							{
								echo "<a href='registration.php'><button class='btn default'>" . t('signin') . "</button></a> ";
								echo "<a href='login.php'><button class='btn warning'>" . t('login') . "</button></a>";
							}
							?>
								<div class="clearfix"> </div> 
							</li>
						</ul>
					</div>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">					
						<ul class="nav navbar-nav navbar-left">
							<li><a href="index.php" class="link link--yaku"><?php echo t('home'); ?></a></li>
							<li><a href="about.php" class="link link--yaku"><?php echo t('about'); ?></a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle link link--yaku" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
									<?php echo t('services'); ?> <span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li><a href="wedding.php" class="link link--yaku"><?php echo t('wedding'); ?></a></li>
									<li><a href="birthday.php" class="link link--yaku"><?php echo t('birthday'); ?></a></li>
									<li><a href="anniversary.php" class="link link--yaku"><?php echo t('anniversary'); ?></a></li>
									<li><a href="other_events.php" class="link link--yaku"><?php echo t('other_events'); ?></a></li>
								</ul>
							</li>
							<li><a href="gallery.php" class="link link--yaku"><?php echo t('gallery'); ?></a></li>
							<li><a href="contact.php" class="link link--yaku"><?php echo t('contact_us'); ?></a></li>
						</ul>		
						<div class="clearfix"> </div>
					</div><!-- //navigation -->
				</div>
				<div class="clearfix"> </div>
			</div>	
		</nav>		
	</div>	
	<!-- //header -->

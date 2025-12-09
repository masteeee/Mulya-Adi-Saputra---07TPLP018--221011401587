<?php
// Pastikan file language_helper.php sudah di-include di header.php
if (!function_exists('t')) {
    require_once 'includes/language_helper.php';
}
?>

<div class="col-md-4 about-wthree-grids">
    <div class="offic-time">
        <div class="time-top">
            <h4><?php echo t('our_services'); ?></h4>
        </div>
        <div class="time-bottom">
            <a href="wedding.php"><h5><?php echo t('wedding'); ?></h5></a>
            <a href="birthday.php"><h5><?php echo t('birthday'); ?></h5></a>
            <a href="anniversary.php"><h5><?php echo t('anniversary'); ?></h5></a>
            <a href="other_events.php"><h5><?php echo t('other_events'); ?></h5></a>
        </div>
    </div>
    <br />
    <div class="testi">
        <h3 class="w3ls-title1"><?php echo t('testimonial'); ?></h3>
        <!--//End-slider-script -->
        <script src="js/responsiveslides.min.js"></script>
        <script>
            // You can also use "$(window).load(function() {"
            $(function () {
                // Slideshow 5
                $("#slider5").responsiveSlides({
                    auto: true,
                    pager: false,
                    nav: true,
                    speed: 500,
                    namespace: "callbacks",
                    before: function () {
                        $('.events').append("<li>" + '<?php echo t('before_event'); ?>' + "</li>");
                    },
                    after: function () {
                        $('.events').append("<li>" + '<?php echo t('after_event'); ?>' + "</li>");
                    }
                });
            });
        </script>
        <div id="top" class="callbacks_container">
            <ul class="rslides" id="slider5">
                <li>
                    <div class="testi-slider">
                        <h4>" <?php echo t('satisfied'); ?></h4>
                        <p><?php echo t('satisfied_text'); ?></p>
                        <div class="testi-subscript">
                            <p><a href="#"><?php echo t('testimonial_author_1'); ?></a></p>
                            <span class="w3-agilesub"></span>
                        </div>	
                    </div>
                </li>
                <li>
                    <div class="testi-slider">
                        <h4>" <?php echo t('thankful'); ?></h4>
                        <p><?php echo t('thankful_text'); ?></p>
                        <div class="testi-subscript">
                            <p><a href="#"><?php echo t('testimonial_author_2'); ?></a></p>
                            <span class="w3-agilesub"></span>
                        </div>	
                    </div>
                </li>
            </ul>	
        </div>
    </div>
</div>
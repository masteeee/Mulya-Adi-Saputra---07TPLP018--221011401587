<?php
// Include language configuration
require_once 'lang.php';
?>
<div class="language-selector">
    <div class="dropdown">
        <button class="btn btn-default dropdown-toggle" type="button" id="languageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <i class="fa fa-language"></i> <?php echo t('language'); ?>: <?php echo strtoupper($_SESSION['lang'] ?? 'en'); ?>
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="languageDropdown">
            <li><a href="?lang=en"><?php echo t('english'); ?> (EN)</a></li>
            <li><a href="?lang=id"><?php echo t('indonesian'); ?> (IND)</a></li>
        </ul>
    </div>
</div>

<style>
.language-selector {
    display: inline-block;
    margin-right: 15px;
}
.language-selector .dropdown-menu {
    min-width: 120px;
}
.language-selector .dropdown-menu a {
    padding: 8px 15px;
}
</style>

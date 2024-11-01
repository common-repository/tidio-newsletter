<?php

require "classes/TidioNewsletterOptions.php";

$tidioNewsletterOptions = new TidioNewsletterOptions();

if(!class_exists('TidioPluginsScheme')){

	require "classes/TidioPluginsScheme.php";

}

TidioPluginsScheme::registerPlugin('newsletter');

//

$tidioPublicKey = $tidioNewsletterOptions->getPublicKey();

$tidioPrivateKey = $tidioNewsletterOptions->getPrivateKey();

$newsletterSettings = $tidioNewsletterOptions->getNewsletterSettings();

$extensionUrl = plugins_url(basename(__DIR__).'/');

//

wp_register_style('tidio-newsletter-css', plugins_url('media/css/app-options.css', __FILE__) );

wp_enqueue_style('tidio-newsletter-css' );


?>

<div class="wrap">
	<h2>Newsletter</h2>

    <div id="newsletter-loading">
    	<p>Loading...</p>
    </div>
    
    <div id="newsletter-content"></div>
    
</div>

<!-- Dialog -->

<div class="frame-dialog-wrap" id="dialog-settings">
	
    <div class="frame-dialog content"></div>
    
</div>

<!-- Dialog Overlay -->

<div id="dialog-overlay"></div>

<!-- Le' Script -->

<script src="<?php echo $extensionUrl ?>/media/js/plugin-minicolors.js"></script>
<script src="<?php echo $extensionUrl ?>/media/js/newsletter-options.js"></script>

<script>

var $ = jQuery;

newsletterOptions.create({
	extension_url: '<?php echo $extensionUrl ?>',
	public_key: '<?php echo $tidioPublicKey ?>',
	private_key: '<?php echo $tidioPrivateKey ?>',
	settings: <?php echo json_encode($newsletterSettings); ?>,
	ajax_url: '<?php echo admin_url() ?>'
});

</script>




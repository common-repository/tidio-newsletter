<?php

/**
 * Plugin Name: Newsletter
 * Plugin URI: http://www.tidioelements.com
 * Description: Newsletter
 * Version: 1.1
 * Author: Tidio Ltd.
 * Author URI: http://www.tidiomobile.com
 * License: GPL2
 */
  
if(!class_exists('TidioPluginsScheme')){
	 
	 require "classes/TidioPluginsScheme.php";
	 	 
} 

if(!class_exists('TidioNewsletterWidget')){
	 
	 require "classes/TidioNewsletterWidget.php";
	 
} 
  
 
class TidioNewsletter {
	
	private $scriptUrl = '//tidioelements.com/uploads/redirect-plugin/';
	
	private $pageId = '';
		
	public function __construct() {
						
		add_action('admin_menu', array($this, 'addAdminMenuLink'));
		
		add_action('init', array($this, 'addCode'));

		//
		
		add_action('deactivate_'.plugin_basename(__FILE__), array($this, 'uninstall'));

		//	
			 
		add_action("wp_ajax_tidio_newsletter_settings_update", array($this, "ajaxPageSettingsUpdate"));	 
			
		//	
			
		add_action( 'widgets_init', 'TidioNewsletter::registerWidgets' );
			 
		// 
			 
		$this->initShortCode();			 
			 	
	}
	
	// Unintall
	
	public function uninstall(){
						
		delete_option('tidio-newsletter-settings');
		delete_option('tidio-newsletter-public-key');
		delete_option('tidio-newsletter-private-key');
		
		TidioPluginsScheme::removePlugin('newsletter');
		
	}
	
	// Widgets
	
	public static function registerWidgets(){
		
		register_widget( 'TidioNewsletterWidget' );
		
	}
	
	// Shortcodes
	
	public function initShortCode(){
		
		add_shortcode('newsletter-sign-up', 'TidioNewsletter::shortCodeNewsletterSignUp');
		
	}
	
	public static function shortCodeNewsletterSignUp($attr, $content = null){
		
		$html = file_get_contents(__DIR__.'/views/newsletter-sign-up.html');
		
		//
		
		return $html;
		
		
	}
	
	// Menu Positions
	
	public function addAdminMenuLink(){
		
        $optionPage = add_menu_page(
			'Newsletter', 'Newsletter', 'manage_options', 'tidio-newsletter', array($this, 'addAdminPage'), plugins_url(basename(__DIR__) . '/media/img/icon.png')
        );
        $this->pageId = $optionPage;
		
	}
	
    public function addAdminPage() {
        // Set class property
        $dir = plugin_dir_path(__FILE__);
        include $dir . 'options.php';
    }

	
	// Enqueue Script
	
	public function addCode(){

		if(is_admin()){
			return false;
		}
				
		$tidioPublicKey = get_option('tidio-newsletter-public-key');

		$settings = get_option('tidio-newsletter-settings');
			
		@$settings = json_decode($settings, true);
		
		//
		
		$htmlCode = '';
								
        if(empty($tidioPublicKey) || empty($settings)){
			
			TidioPluginsScheme::insertCode('newsletter', '');
			
			return false;
			
		}
				
		// Addon
						
		$addonData = array(
			'project_public_key' => $tidioPublicKey,
			
			'header' => $settings['header'],
			'description' => $settings['description'],
			'footer' => $settings['footer'],
			'success' => $settings['success'],
			'error' => $settings['error'],
			'placeholder' => $settings['placeholder'],
			'button' => $settings['button'],
		);
						
		//	
		
		$htmlCode = '';
		
		if($settings['popup_show']=='1'){	
			
			$htmlCode .=  
			'<script type="text/javascript">'.
			"if(typeof tidioElementsAddons!='object'){ var tidioElementsAddons = []; }".
			"tidioElementsAddons.push(".json_encode(array(
				'type' => 'newsletter',
				'addon_data' => $addonData,
				'button_label' => $settings['button_label']
			)).");".
			'</script>'.
			'<script type="text/javascript" src="http://www.tidioelements.com/uploads/addons/addon-newsletter-en.js"></script>';
									
		}
				
		//
		
		TidioPluginsScheme::insertCode('newsletter', $htmlCode);

	}
	
	// Ajax Pages
	
	public function ajaxPageSettingsUpdate(){

		if(empty($_POST['settingsData'])){
			
			$this->ajaxResponse(false, 'ERR_PASSED_DATA');
			
		}
		
				
		$newsletterSettings = urldecode($_POST['settingsData']);
					
		@$newsletterSettings = json_decode($newsletterSettings, true);	
		
		if(!$newsletterSettings){
			$this->ajaxResponse(false, 'ERR_PARSE_DATA');
		}
		
		//
		
		$newsletterSettingsCurrent = get_option('tidio-newsletter-settings');
				
		if($newsletterSettingsCurrent && $newsletterSettingsCurrent!='null'){
		
			$newsletterSettingsCurrent = json_decode($newsletterSettingsCurrent, true);
		
			$newsletterSettings = array_merge($newsletterSettingsCurrent, $newsletterSettings);
		
		}
				
		//
				
		update_option('tidio-newsletter-settings', json_encode($newsletterSettings));
				
		$this->ajaxResponse(true, true);

	}

	public function ajaxResponse($status = true, $value = null){
		
		echo json_encode(array(
			'status' => $status,
			'value' => $value
		));	
		
		exit;
			
	}
}

$TidioNewsletter = new TidioNewsletter();


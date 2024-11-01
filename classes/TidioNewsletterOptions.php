<?php

class TidioNewsletterOptions {
	
	private $apiHost = 'http://www.tidioelements.com/';
	
	private $siteUrl;
	
	public function __construct(){
				
		$this->siteUrl = get_option('siteurl');
		
	}
	
	public function getNewsletterSettings(){

		$newsletterSettings = get_option('tidio-newsletter-settings');
		
		if($newsletterSettings)
			
			return json_decode($newsletterSettings, true);
			
		//	
					 
		$newsletterSettings = array(
			'base_color' => '#32475C',
			'button_label' => 'Newsletter',
			'header' => 'Sign up for our Newsletter',
			'description' => 'Receiving regular news will help you to develop your business.',
			'footer' => 'No-spam guarantee',
			'button' => 'Sign Up',
			'placeholder' => 'Your email address...',
			'success' => 'Email address has been added!',
			'error' => 'Email address already exists in our database',
			'popup_show' => '1',
			'popup_show_auto' => '',
			'popup_show_button' => '1',
		);
		
		//
		
		update_option('tidio-newsletter-settings', json_encode($newsletterSettings));
		
		return $newsletterSettings;
		

	}
	
	public function getPrivateKey(){
				
		$tidioPrivateKey = get_option('tidio-newsletter-private-key');

		if(empty($tidioPrivateKey)){
		
			$tidioPrivateKey = md5(SECURE_AUTH_KEY.md5(microtime().mt_rand(1,100000000000)).'.tidioNewsletter');
			
			update_option('tidio-newsletter-private-key', $tidioPrivateKey);
		
		}
		
		return $tidioPrivateKey;

	}
	
	public function getPublicKey(){

		$tidioPublicKey = get_option('tidio-newsletter-public-key');
				
		if(!empty($tidioPublicKey))
			
			return $tidioPublicKey;
			
		//
				
		$apiData = $this->getContentData($this->apiHost.'apiExternalPlugin/accessPlugin?'.http_build_query(array(
			'pluginId' => 'newsletter',
			'privateKey' => $this->getPrivateKey(),
			'url' => $this->siteUrl,
			'_ip' => $_SERVER['REMOTE_ADDR']
		)));

		$apiData = json_decode($apiData, true);
		
		if(!empty($apiData) || $apiData['status']){
			
			$tidioPublicKey = $apiData['value']['public_key'];
			
			update_option('tidio-newsletter-public-key', $tidioPublicKey);
			
		}
		
		return $tidioPublicKey;

	}
	
	private function getContentData($url){
		
		$ch = curl_init();
	
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
	
		$data = curl_exec($ch);
		curl_close($ch);
		
		return $data;
		
	}
	
}
var newsletterOptions = {
		
	public_key: null,
	
	private_key: null,
	
	//
	
	plugin_url: 'http://visual-editor.tidioelements.com/',
	
	api_url: '//www.tidioelements.com/',
	
	extension_url: null,
	
	ajax_url: null,
	
	settings: null,
	
	//
	
	$iframe: null,
	
	create: function(data){
		
		// this.plugin_url = 'http://localhost/tidio_elements_app/public/';
		
		//
						
		var default_data = {
			extension_url: null,
			public_key: null,
			private_key: null,
			settings: null,
			ajax_url: null
		};
		
		data = $.extend(default_data, data);
		
		//
		
		this.extension_url = data.extension_url;

		this.public_key = data.public_key;

		this.private_key = data.private_key;

		this.settings = data.settings;

		this.ajax_url = data.ajax_url;
		
		//
		
		this.iFrameLoad();
		
		this.iFrameMessages();
						
	},
			
	iFrameLoad: function(){
		
		var iframe_height = $('body').height() - ($("#wpadminbar").height() + $(".wrap h2").height() + 10);
		
		$("#newsletter-content").html(
			'<iframe src="' + this.plugin_url + 'en/panel/plugins/newsletter/external?externalAccessKey=' + this.private_key + '&visualEditorSimulate=1&_cache=' + Math.random() + '" id="newsletter-iframe" style="height: ' + iframe_height + 'px;"></iframe>'
		);		
		
		$("#newsletter-iframe").load(function(){
			
			$("#newsletter-loading").fadeOut('fast', function(){
				
				$("#newsletter-content").fadeIn('fast');
				
				newsletterOptions.iFrameIsReady();
				
			});
			
		});
						
	},
	
	iFrameIsReady: function(){
		
	},
	
	iFrameMessages: function(){
		
		if (window.addEventListener) {
			window.addEventListener("message", this.iFrameMessageTrigger, false);
		} else {
			window.attachEvent("onmessage", this.iFrameMessageTrigger);
		}

	},
	
	iFrameMessageTrigger: function(e){
		
		if(e.type!='message' || !e.data){
			
			return false;
			
		}
				
		newsletterOptions.settings = e.data;
		
		newsletterOptions.updateSettings();
				
	},
	
	updateSettings: function(){
		
		$.ajax({
			url: this.ajax_url + 'admin-ajax.php?action=tidio_newsletter_settings_update',
			data: {
				settingsData: encodeURI(JSON.stringify(this.settings))
			},
			type: 'POST'
		});
		
	}
		
};
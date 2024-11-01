var newsletterWidget = {
	
	project_public_key: null,
	
	create: function(){
		
		jQuery('.tidio-newsletter-widget').on('submit', function(){
			
			var $this = jQuery(this),
				input_email = $this.find('.input-email').val(),
				message_success = $this.attr('data-text-success'),
				message_error = $this.attr('data-text-error');	
						
			if(!input_email || input_email=='' || input_email.indexOf('@')==-1){
				alert(message_error);
				return false;
			}
			
			alert(message_success);
			
			this.reset();
			
			//
			
			newsletterWidget.addEmail(input_email, function(){
				
			});
			
			
			return false;
			
		});
		
	},
	
	addEmail: function(email, _func){
		
		if(typeof _func!='function')
			_func = function(){};
		
		//
		
		var xhr_url = '//www.tidioelements.com/plugin-inside/newsletter/add-email?projectPublicKey=' + this.project_public_key + '&email=' + encodeURIComponent(email);
		
		jQuery.getJSON(xhr_url, function(data){
			
		});
		
	}
	
};

jQuery(document).on('ready', function(){

	newsletterWidget.create();

});
// Refresh page viewers
/**
* @file
* Global jQuery, Drupal, drupalSettings, window
* jslint white:true, multivar, this, browser:true.
*/
(function ($, Drupal, drupalSettings, window) {
 "use strict";

 Drupal.behaviors.subseven = {
   attach: function (context, settings) {
     /* SCRIPT START */

    console.log('NAVIGUS Script Working');
	var refresh_page_viewers = function() {
		   console.log('refresh_page_viewers');
            var response = true;
			if(response == true){
                // This makes it unable to send a new request 
                // unless you get response from last request
                response = false;
				$.ajax({
				   url: 'http://localhost/navigus/viewers/refresh?_format=json',
				   datatype: 'jsonp',
				   //context: element,
				   complete: function (xhr, status) {
					   // This makes it able to send new request on the next interval
                      response = true;
				   },
				   success: function (response) {
						console.log("Response get successful!");
						console.log(response);
						$('#block-currentviewersblock .content').html(response.data);
				   },
				 });
            }
        }

        refresh_page_viewers();
		setInterval(refresh_page_viewers, 10000);

    
	  
   }
 };
}(jQuery, Drupal, drupalSettings, window));
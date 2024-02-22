(function( $ ) {
	'use strict';
	
	 $( window ).load(function() {

	   $("#akismet").on("click", function() {
         if($(this).prop('checked') == true) { 
           $('.trakismet').removeClass('unseen'); 
           $('#thakismet, #tdakismet').addClass('first');
           $('#thakismet, #tdakismet').removeClass('wide');
           if( $('#flagged-message').prop('checked') == true){ 
	           $('.trspammark').removeClass('unseen'); 
               $('#thakismetaction, #tdakismetaction').removeClass('last');
	       } 
           else { 
	           $('.trspammark').addClass('unseen'); 
               $('#thakismetaction, #tdakismetaction').addClass('last');
	       }            
         } 
         else { 
           $('.trakismet').addClass('unseen'); 
           $('#thakismet, #tdakismet').addClass('wide');
           $('#thakismet, #tdakismet').removeClass('first');          
         }
       });
       
       $("#blocked-message").on("click", function() {
         if($(this).prop('checked') == true) { 
	         $('.trspammark').addClass('unseen');
	         $('#thakismetaction, #tdakismetaction').addClass('last'); 
	     } 
         else {
	         $('.trspammark').removeClass('unseen');
	         $('#thakismetaction, #tdakismetaction').removeClass('last'); 
         }
       });
       
       $("#flagged-message").on("click", function() {
         if($(this).prop('checked') == true) {
	         $('.trspammark').removeClass('unseen');
	         $('#thakismetaction, #tdakismetaction').removeClass('last'); 
	     } 
         else { 
	         $('.trspammark').addClass('unseen');
	         $('#thakismetaction, #tdakismetaction').addClass('last'); 
	     }
       });

       $("#blocked-message").on("click", function() { $('#akismet-action-notes').addClass('invisible'); }); 
       $("#flagged-message").on("click", function() { $('#akismet-action-notes').removeClass('invisible'); }); 

   	 });

})( jQuery );
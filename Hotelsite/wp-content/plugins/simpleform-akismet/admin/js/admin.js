(function( $ ) {
	'use strict';
	
	 $( window ).load(function() {

	   $("#akismet").on("click", function() {
         if($(this).prop('checked') == true) { 
           $('.trakismet').removeClass('unseen'); 
           $('#tdakismet').removeClass('last');
           if( $('#blocked-message').prop('checked') == true){ 
	           $('.trspammark').addClass('unseen'); 
               $('#tdakismetaction').addClass('last');
	       } 
           else { 
	           $('.trspammark').removeClass('unseen'); 
               $('#tdakismetaction').removeClass('last');
	       }            
         } 
         else { 
           $('.trakismet').addClass('unseen'); 
           $('#tdakismet').addClass('last');
         }
       });
       
       $("#blocked-message").on("click", function() {
         if($(this).prop('checked') == true) { 
	         $('.trspammark').addClass('unseen');
	         $('#tdakismetaction').addClass('last'); 
	     } 
         else {
	         $('.trspammark').removeClass('unseen');
	         $('#tdakismetaction').removeClass('last'); 
         }
       });
       
       $("#flagged-message").on("click", function() {
         if($(this).prop('checked') == true) {
	         $('.trspammark').removeClass('unseen');
	         $('#tdakismetaction').removeClass('last'); 
	     } 
         else { 
	         $('.trspammark').addClass('unseen');
	         $('#tdakismetaction').addClass('last'); 
	     }
       });

       $("#blocked-message").on("click", function() { $('#akismet-action-notes').addClass('invisible'); }); 
       $("#flagged-message").on("click", function() { $('#akismet-action-notes').removeClass('invisible'); }); 

   	 });

})( jQuery );
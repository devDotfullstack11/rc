      

   // Validation Oder Details
		   jQuery(".delivery").validate({
		  rules: {
			name: "required",
			address1:"required",
			email: {
			  required: true,
			  email: true
			}
		  }  
		});
		
	// Validation Sign up
		  jQuery(".account_create").validate({
		  rules: {
			farmname: "required",
			email:"required",
			password:"required",
			email: {
			  required: true,
			  email: true
			}
		  }  
		});
		
	   //Validation on Login Form
      jQuery("#loginform").submit(function() {
	 
          var email= $(".useremail").val();
          var pass= $(".userpass").val();
            var content={							
			               email: pass,
							pass:pass,
						};
		   jQuery.ajax({
				 type: "post",
				 data:content,
				 url: "https://www.dawo.co.in/auth/login", 
				 dataType: "json",  
				 cache:false,
				  success: 
							  function(response){
								  //console.log(response);
								 
								 
							  }
				  });
			return false;

        });
		
		//Validation End
		
		
		//Ammination
        jQuery(".signup").click(function(){
				 
				
				 $(".signup_from").show(1000);
		   			$('html, body').animate({
           scrollTop: $(".signup_from").offset().top
           }, 1000);
		 
	   
        });
				jQuery(".login").click(function(){
				 
					$(".signup_from").hide(500);
					$(".login_form").show(1000);
							$('html, body').animate({
						scrollTop: $(".login_form").offset().top
						}, 1000);
			
			
				 });
	    jQuery(".addtocart").click(function(){
		   
				$('html, body').animate({
					scrollTop: $("#error").offset().top
					}, 500);
				
				
				});
   
        //Ammination End
 
   
	  jQuery('#signupform').validate({ // initialize the plugin
			rules: {
				farmname: {
					required: true,
					
				},
				email: {
					required: true,
					email: true
				}
			}
		});
		
		
		
		
       
	     
 
 
		
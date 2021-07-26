$(document).ready(function () {
	var commissioned = [];
	var salaried = [];
	var base = $('.base').val();
	var token = $('._token').val();
	var notes_page = 1;
	var totalpages = 0;
	var limit = 6;
	var thedate = new Date();
	var currentTimezone = thedate.getTimezoneOffset();
	//console.log(currentTimezone);
	//console.log(base);
	$('table').on('click', '.__del', function(event){
			event.preventDefault();
			//console.log($(this).attr('href'));
			var url = $(this).attr('href');
			var token = $('._token').val();
			var datatopost = {origin : $(this).attr('data-origin') , _token : token};
		var r = confirm("Are you Sure to Delete?");
		if (r == true) {
			commonajax(datatopost,url,'delel',$(this))
			//window.location.href = url;

		} else {
		    //ajax to change status
		} 
	});

	$('.togglestatus').on('click',function(event){
			event.preventDefault();
			//console.log($(this).attr('href'));
			var url = $(this).attr('href');
			var token = $('._token').val();
			var alertmsg = $(this).attr('data-confirmation');
			var type = $(this).attr('data-type'); 
			var current_status = $(this).attr('data-current');
			var datatopost = {origin : $(this).attr('data-origin') , _token : token, current_status : current_status};
		var r = confirm(alertmsg);
		if (r == true) {
			commonajax(datatopost,url,type,$(this))
			//window.location.href = url;

		} else {
		    //ajax to change status
		} 
	});

	


	$(".image-file").on('change', function () {
		//console.log("asdfasd");
		setTimeout( function previewFile() {
			var preview = document.querySelector('img#imgprview');
			var file    = document.querySelector('input[type=file]').files[0];
			//console.log(file);
			var reader  = new FileReader();
			$('.image_loader').hide();
			setTimeout( function(){ 
				$('#image').show();
			}  , 500 );
			reader.onloadend = function () {
				preview.src = reader.result;
			}
			if (file) {
				reader.readAsDataURL(file);
			} else {
				preview.src = "";
			}
		} , 500 );
});
	if($(document).find('div').hasClass('emp_type')){
		setTimeout(function(){
			$('.role_dd').trigger('change'); 			
		},10)
		
		$('.form-group').on('change','.role_dd',function(){
			//console.log("thisval");
			 var thisval = $(this).val();
			 if(thisval == 2){
			 	// If Selected Role is Director No Area things needed
			 	$('.states_wrapper').hide();
			 	$('.region_wrapper').hide();
			 	$('.zone_wrapper').hide();
			 	$('.area_wrapper').hide();
			 } else if (thisval ==3){
			 	$('.states_wrapper').show();
			 	$('.region_wrapper').hide();
			 	$('.zone_wrapper').hide();
			 	$('.area_wrapper').hide();
			 } else if (thisval ==4){
			 	$('.states_wrapper').show();
			 	$('.region_wrapper').show();
			 	$('.zone_wrapper').hide();
			 	$('.area_wrapper').hide();
			 } else if (thisval ==5){
			 	$('.states_wrapper').show();
			 	$('.region_wrapper').show();
			 	$('.zone_wrapper').show();
			 	$('.area_wrapper').hide();
			 } else if (thisval == 6 || thisval == 7 || thisval == 8 ){
			 	$('.states_wrapper').show();
			 	$('.region_wrapper').show();
			 	$('.zone_wrapper').show();
			 	$('.area_wrapper').show();
			 }	

			 if(thisval < 8){
			 	$(document).find('.emp_type').show();
			 } else {
			 	$(document).find('.emp_type').hide();
			 	$(document).find('.emp_type').find('select').val('NA');
			 }
			 var state_id = $('.state_dd').val();
			 var region_id = $('.region_select').val();
			 var zone_id = $('.zone_select').val();
			 var area_id = $('.area_select').val();
			 var datatopost = {role_id : thisval ,type : 'reporting_to', _token : token , state_id : state_id , region_id : region_id , zone_id : zone_id, area_id : area_id };
			 commonajax(datatopost,base+'/get-data','reporting_to',$(this));	
		});

	}
		if($(document).find('div').hasClass('commission_wrapper')){
			var datatopost = {origin : $(this).attr('data-origin') , _token : token};
			commonajax(datatopost,base+'/admin/getdata','commissioned',$(this));
		$('.form-group').on('change','.user_dd',function(){
			 var thisval = $(this).val();
			 //console.log(thisval);
			 //console.log(commissioned);
			 //console.log($.inArray( parseInt(thisval), commissioned ));
			 if($.inArray( parseInt(thisval), commissioned ) == -1 && $.inArray( parseInt(thisval), salaried ) == -1){
			 	$('.commission_wrapper').hide();
			 	$('.commission_wrapper').find('input').val(0);
			 } else {
			 	$('.commission_wrapper').show();
			 	if($.inArray( parseInt(thisval), commissioned ) >= 0){
			 		$('.clabel').text("Commission(%)");
			 	} else {
			 		$('.clabel').text("Salary(Monthly 	)");
			 	}
			 }
		});

	}

	if($(document).find('div').hasClass('pm_wrapper')){
		setTimeout(function(){
			$('.bussiness_type').trigger('change'); 
			$('.plans_select').trigger('change'); 
		},10);
		
		$('.form-group').on('change','.bussiness_type',function(){
			//console.log("thisval");
			 var thisval = $(this).val();
			 var resetinputs = $(this).attr('data-reset');
			 if(thisval == 'Free'){
			 	$(document).find('.pm_wrapper').hide();
			 	$(document).find('.payment_wrapper').hide();
			 	$(document).find('.plans_wrapper').hide();	
			 	$('.amount-inp').prop("disabled", false);
			 	$('.gallery_wrapper').hide();
			 	if(resetinputs =='Yes'){
			 		$(document).find('.pm_wrapper').find('select').val('None');
			 		$(document).find('.payment_wrapper').find('input').val(0);	
			 		$('.plans_select').val(0);
			 		$('.keywords_number').val(3);
			 		$('.images_allowed').val(0);		 		
			 		var input = $('.keywords').val();  
			 		console.log(input.split(','));
					input.split(',').splice(0,3);
					console.log(input.split(',').splice(0,3));
					setTimeout(function(){
						$('.keywords').val(input.split(',').splice(0,3).join(','));
						$('.keyword-tags').tagsinput('removeAll');
					},100);
			 	}
			 	
			 } else {
			 	$(document).find('.pm_wrapper').show();
			 	$(document).find('.payment_wrapper').show();
			 	$(document).find('.plans_wrapper').show();	
			 	$('.gallery_wrapper').show();
			 }
		});
		$('.form-group').on('change','.plans_select',function(){
			 var thisval = $(this).val();
			 var resetinputs = $(this).attr('data-reset');
			 if(thisval == 0){
			 	
			 } else {
			 	var datatopost = {id : thisval ,type : 'get_amount', _token : token};
				commonajax(datatopost,base+'/get-data','get_amount',$(this));	
			 }
		});

	}

	if($('.form-control').hasClass('front_plan_id')){
		//alert("asdf");
				var thisval = $('.front_plan_id').val();
			 var resetinputs = 'No';
			 if(thisval == 0){
			 	
			 } else {
			 	var datatopost = {id : thisval ,type : 'get_amount', _token : token};
				commonajax(datatopost,base+'/get-data','get_amount',$(this));	
			 }
	}

	if($(document).find('ul').hasClass('todo-list')){
		getnotes();
	}

	//alert("asdf");
		if($(document).find('div').hasClass('states_wrapper')){

			//console.log("Got it");
			setTimeout(function(){
				$('.state_dd').change();
			},10);
			$('.form-group').on('change','.state_dd',function(){
				 var thisval = $(this).val();
				 if(thisval == 0){
				 	
				 } else {
				 	var datatopost = {id : thisval ,type : 'get_region', _token : token};
					commonajax(datatopost,base+'/admin/get-geo-data','get_region',$(this));	
				 }
			});	
		}


		/*if($(document).find('div').hasClass('states_wrapper')){

			console.log("Got it");
			setTimeout(function(){
				$('.state_dd').change();
			},10);
			$('.form-group').on('change','.state_dd',function(){
				 var thisval = $(this).val();
				 if(thisval == 0){
				 	
				 } else {
				 	var datatopost = {id : thisval ,type : 'get_region', _token : token};
					commonajax(datatopost,base+'/admin/get-geo-data','get_region',$(this));	
				 }
			});	
		}*/

		$('.form-group').on('change','.region_select',function(){
				 var thisval = $(this).val();
				 if(thisval == 0){
				 	
				 } else {
				 	var datatopost = {id : thisval ,type : 'get_zone', _token : token};
					commonajax(datatopost,base+'/admin/get-geo-data','get_zone',$(this));	
				 }
			});	

		$('.form-group').on('change','.zone_select',function(){
				 var thisval = $(this).val();
				 if(thisval == 0){
				 	
				 } else {
				 	var datatopost = {id : thisval ,type : 'get_area', _token : token};
					commonajax(datatopost,base+'/admin/get-geo-data','get_area',$(this));	
				 }
			});	

	

	$('.note_wrapper').on('click','.addnotebtn',function(event){
		event.preventDefault();
		var note = $('.note').val();
		var datatopost = {note : note , _token : token};
		commonajax(datatopost,base+'/add-note','add-note',$(this));
	});
	
	$('.note_wrapper').on('keydown','.note',function(event){
		var code = event.keyCode;
		if(code == 13){
			$('.addnotebtn').click();
			return false;
		}
	});
	$('.notespagination').on('click','.paginate_notes',function(event){
		event.preventDefault();
		//if(notes_page < totalpages  ){
			var page = $(this).attr('data-page');
			if(page == 'pre'){
				if(notes_page > 1){
					notes_page = parseInt(notes_page)
					notes_page =  notes_page-=1;
					getnotes();	
				}
			} else if(page == 'next'){
				if(notes_page < totalpages){
					notes_page = parseInt(notes_page)
					notes_page = notes_page +=1;
					getnotes();	
				}
			}else {
				notes_page = parseInt(page);
				getnotes();
			}

			
		//}
	});

	$('.todo-list').on('click','.taskstatus',function(event){
		event.stopImmediatePropagation();
		if($(this).is(':checked')){
			var id = $(this).attr('data-id');
			var datatopost = {id : id , _token : token, is_done : 'Yes' };
		} else {
			var datatopost = {id : id , _token : token, is_done : 'No' };
		}
		commonajax(datatopost,base+'/changestatus','changestatus',$(this));	
	});
	$('.todo-list').on('click','.delnote',function(event){
			event.preventDefault();
			var url = $(this).attr('href');
			var token = $('._token').val();
			//console.log(url);
			var datatopost = {origin : $(this).attr('data-origin') , _token : token};
		var r = confirm("Are you Sure to Delete?");
		if (r == true) {
			commonajax(datatopost,url,'del-note',$(this))
			//window.location.href = url;

		} else {
		    //ajax to change status
		} 	

	 });

	
	
	

	




	function commonajax(datatopost,url,type,$this){
				$.ajax({
							    url: url,
							    type: "post",
							    data: datatopost,
							    beforeSend: function() {
							    	//console.log("Beforsend")
							    },
							    complete: function(){
							    	//console.log("complete")
							    },
							    success: function(data){
									data = JSON.parse(data);
							    	console.log(type,data																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																									);
							    	if(data.status == 'success'){
							    		if(type == 'delel'){
							    			alert("Record Deleted!");
							    			$this.closest('tr').remove();
							    		} else if(type =='commissioned'){
							    			commissioned = data.commissioned;
							    			salaried = data.salaried;
							    			setTimeout(function(){
												$('.user_dd').trigger('change'); 			
											},10)
							    		}else if(type =='add-note'){
							    			$('.note').val('');
							    			getnotes();
							    		} else if(type == 'get-notes'){
							    			var out = '';
							    			$.each(data.notes, function( index, value ) {
							    				////console.log(value);
							    				var checked = (value.is_done == 'Yes') ? 'checked' : '' ;
							    				out+='<li> <span class="handle"> <i class="fa fa-ellipsis-v"></i> <i class="fa fa-ellipsis-v"></i> </span> <input type="checkbox" value="" '+ checked +' data-id="'+ value.id +'" class="taskstatus"> <span class="text">'+ value.note +'</span> <small class="label label-danger"><i class="fa fa-clock-o"></i> '+ value.created +'</small> <div class="tools"> <i class="fa fa-trash-o delnote" href="' + base +'/del-note" data-origin="'+value.id+'"></i> </div> </li>';
											});
											$('.todo-list').html(out);
											totalpages = data.totalpages;
											var pagination_htm = '<li><a href="#" data-page="pre" class="paginate_notes">&laquo;</a></li>';
											//var endloop = (data.totalpages > 5) ? 5 
											var last_page = ((notes_page+ 5) > data.totalpages) ? data.totalpages : (notes_page+ 5);
											var start_page = ((data.totalpages <=5)) ? 1 : notes_page;
											for (var i = start_page ; i <= last_page; i++) {
												pagination_htm += '<li><a href="#"data-page="'+i+'" class="paginate_notes">'+i+'</a></li>';
											}
											pagination_htm += '<li><a href="#" data-page="next" class="paginate_notes">&raquo;</a></li>';
											$('.notespagination').html(pagination_htm);
							    		} else if(type == 'changestatus'){
							    			//console.log(data);
							    		} else if(type == 'del-note'){
							    			getnotes();
							    		} else if(type == 'approve-leave'){
							    			if(datatopost.current_status == 'Yes'){
							    				$this.text('Approve');
							    				$this.attr('data-current','No');
							    				$this.closest('tr').find('.is_approvedtxt').text('No');
							    				
							    			} else {
							    				$this.text('Dis-Approve');
							    				$this.attr('data-current','Yes');
							    				$this.closest('tr').find('.is_approvedtxt').text('Yes');
							    			}		
							    		} else if(type == 'approve-bussiness'){
							    			if(datatopost.current_status == 'Yes'){
							    				$this.text('Dis-Approved');
							    				$this.attr('data-current','No');
							    				$this.closest('tr').css('color','red');
							    				$this.closest('tr').find('a').css('color','red');
							    				$this.closest('tr').find('.is_approvedtxt').text('No');
							    				
							    			} else {
							    				$this.text('Approved');
							    				$this.attr('data-current','Yes');
							    				$this.closest('tr').css('color','#3c8dbc');
							    				$this.closest('tr').find('a').css('color','#3c8dbc');
							    				$this.closest('tr').find('.is_approvedtxt').text('Yes');
							    			}		
							    		} else if(type == 'del-service'){
								    			alert("Record Deleted!");
								    			$this.closest('tr').remove();
							    		} else if (type == 'toggle-visibility'){
							    			if(datatopost.current_status == 'Yes'){
							    				$this.text('Not Visible');
							    				$this.attr('data-current','No');
							    				$this.css('color','red');
							    				//$this.closest('tr').find('.is_approvedtxt').text('No');
							    				
							    			} else {
							    				$this.text('Visible');
							    				$this.attr('data-current','Yes');
							    				$this.css('color','#72afd2');
							    				//$this.closest('tr').find('.is_approvedtxt').text('Yes');
							    			}	
							    		} else if (type =='get_amount'){
							    			if(data.plan.price !== undefined){

							    				//console.log(data.plan.price);
							    				$('.amount-inp').val(data.plan.price);	
							    				$('.images_allowed').val(data.plan.images);	
							    				$('.keywords_number').val(data.plan.keywords);	
							    				$('.amount-inp').prop("disabled", true);
							    			}		
							    		} else if(type == 'get_region'){
							    			var out = '';
							    			$.each(data.regions, function( index, value ) {
							    				if($(document).find('input').hasClass('selected_region_id')){
							    					var selected_val = $('.selected_region_id').val();
							    					if(selected_val == index){
							    						var selected = 'selected';	
							    					} else {
							    						var selected = '';	
							    					}
							    					
							    				} else {
							    					var selected = '';
							    				}
							    				out+='<option value="'+index+'" '+ selected +'>'+value+'</option>';
							    			});
							    			$('.region_select').html(out);
							    			
							    			if($(document).find('div').hasClass('zone_wrapper')){
							    				setTimeout(function(){
							    					$('.region_select').change();
							    				},10);	
							    			}
							    		} else if(type == 'get_zone'){
							    			out ='';
							    			$.each(data.zones, function( index, value ) {
							    				var selected_val = $('.selected_zone_id').val();
							    					if(selected_val == index){
							    						var selected = 'selected';	
							    					} else {
							    						var selected = '';	
							    					}
							    				out+='<option value="'+index+'"	'+ selected +' >'+value+'</option>';
							    			});
							    			$('.zone_select').html(out);
							    			if($(document).find('div').hasClass('area_wrapper')){
							    				setTimeout(function(){
							    					$('.zone_select').change();
							    				},10);	
							    			}	
							    		} else if (type == 'get_area'){
							    			out ='';
							    			$.each(data.areas, function( index, value ) {
							    				var selected_val = $('.selected_area_id').val();
							    					if(selected_val == index){
							    						var selected = 'selected';	
							    					} else {
							    						var selected = '';	
							    					}
							    				out+='<option value="'+index+'"	'+ selected +' >'+value+'</option>';
							    			});
							    			$('.area_select').html(out);
							    		} else if (type == 'reporting_to'){
							    				var out ='';
							    			$.each(data.users, function( index, value ) {
							    				out+='<option value="'+index+'">'+value+'</option>';
							    			});
							    			$('.reporting_to').html(out);
							    		} else if (type == 'toggle-working-status'){
							    			//console.log(datatopost.current_status);
							    			if(datatopost.current_status == 'Working'){
							    				$this.text('Left');
							    				$this.attr('data-current','Left');
							    				$this.closest('tr').css('color','red');
							    				$this.closest('tr').find('a').css('color','red');
							    				$this.closest('tr').find('.is_approvedtxt').text('Left');
							    				
							    			} else {
							    				$this.text('Working');
							    				$this.attr('data-current','Working');
							    				$this.closest('tr').css('color','#3c8dbc');
							    				$this.closest('tr').find('a').css('color','#3c8dbc');
							    				$this.closest('tr').find('.is_approvedtxt').text('Working');
							    			}	
							    		} else if(type == 'user-activation'){
							    			console.log(datatopost.current_status);
							    			if(datatopost.current_status == 'No'){
							    				$this.text('Subscribed');
							    				$this.closest('td').html('Subscribed')
							    			} else {

							    			}	
							    		}

							    			
							    		
							    	} else if(data.status == 'failed'){
							    		if(type == 'delel'){
							    			alert(data.reason);
							    		} else if (type == 'add-note'){
							    			if(data.reason == 'validation'){
							    				$.each(data.errors, function( index, value ) {
							    					//console.log(value);
							    					$(document).find('.'+index).closest('.form-group').find('.errors').html(value[0]);
												  //alert( index + ": " + value );
												});
							    			}
							    		}
							    	}
							    }
			});

	}

	function getnotes(){

		var datatopost = { type : 'init' , _token : token, page : notes_page ,limit : limit , currentTimezone : currentTimezone};
		commonajax(datatopost,base+'/get-notes','get-notes',$(this));
	}
});
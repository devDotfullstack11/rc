$(document).ready(function(){
    // WE WILL SET A SPECIAL CLASS FOR ASSIGNING DATEPICKER
if($('input').hasClass("assingDatePicker")){
    $('.assingDatePicker').datetimepicker({        
        timepicker:false,
        format:'Y-m-d',
        maxDate: Date(),
        onChangeDateTime:function(dp,$input){
            // console.log($input.val());
            
            // setTimeout(function(){
            //     var formattedDate = moment($input.val()).format("MM/DD/YYYY");    
            //     console.log($input.next("input").attr("class"),formattedDate);
            //     $input.next("input").val(formattedDate)
            // },200);
            
           // alert($input.val())
        }
       });
}

if(document.getElementById("date_timepicker_start") !== null && document.getElementById("date_timepicker_end") ) {
    jQuery(function(){
        jQuery('#date_timepicker_start').datetimepicker({
         format:'Y-m-d',
         onShow:function( ct ){
          this.setOptions({
           maxDate:jQuery('#date_timepicker_end').val()?jQuery('#date_timepicker_end').val():false
          })
         },
         timepicker:false
        });
        jQuery('#date_timepicker_end').datetimepicker({
         format:'Y-m-d',
         onShow:function( ct ){
          this.setOptions({
           minDate:jQuery('#date_timepicker_start').val()?jQuery('#date_timepicker_start').val():false
          })
         },
         timepicker:false
        });
       });

}


}); // Ready ENDED
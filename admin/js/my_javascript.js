// ***** YOUR EXTRA FUNCTIONS FOR EXTRA COLUMNS OR SOMETHING ELSE *****
 $(document).ready(function(){
 $('#ctLeads td img.del').click(function(){
		
		id = $(this).attr('id'); // 
		
		if (confirm("Are you sure you want to delete this?"))
        {
           
         var record = '?id=' + id;
            $.ajax(
            {
                   type: "POST",
                   url: "../wp-content/plugins/dudapro/admin/includes/delete.php",
                   data: record,
                   cache: false,

                   success: function()
                   {
           			 $(this).parent().parent().parent().remove();
				
                  //  parent.fadeOut('slow', function() {$(this).remove();});

					
                   }
             });
        }
		
		
	//	alert(id);
				

             
		
// Mass delete

$("#ct_actions").change(function(e) { 
    $('#ct_actions option:eq(0)').attr("selected","selected") ;


	 $("#ct input[type='checkbox']:checked").each(function() {
		 
  		 $(this).parent().parent().remove();
	     
	});


});
			
			
			
});
			
			
			
});


function ctActions(table_id){

   // alert('Action: '+$('#'+table_id+'_actions').val()+' - '+$('#'+table_id+'_actions :selected').text());
         var record = 'id=';
		 var tblID = '#' + table_id;

    $(tblID +'_actions option:eq(0)').attr("selected","selected") ;

    $(tblID +" input[type='checkbox']:checked").each(function() {
       // alert('Checkbox selected : '+this.value);
	   record = 'id=' + this.value;

		if (table_id == "ctLeads")
		{
			actionURL = "../wp-content/plugins/dudapro/admin/includes/delete.php?" + record;
		}
		
		if (table_id == "ctMobile")
		{
			//alert(record);
			actionURL = "../wp-content/plugins/dudapro/admin/includes/mobileDelete.php?" + record;
		}
		if (table_id == "ctMulti")
		{
		//	alert('mobile' + record);
			actionURL = "../wp-content/plugins/dudapro/admin/includes/multiDelete.php?" + record;
		}	
		
		

	   $.ajax(
            {
                   type: "POST",
                   url: actionURL,
                   data: record,
                   cache: false,

                   success: function(response)
                   {
           			 $(this).parent().parent().parent().remove();
				
                      
					
                   }
             });
	 //  $(tblID).load(window.location.href + tblID);

	   location.reload();
	   
    })

}

function checkAllLeads(){
    if($('#ct_check_all').is(':checked'))
        $("#ctLeads input[type='checkbox']").attr('checked', true);
    else
        $("#ctLeads input[type='checkbox']").attr('checked', false);
}

function checkAllMobile(){
    if($('#ct_check_all').is(':checked'))
        $("#ctMobile input[type='checkbox']").attr('checked', true);
    else
        $("#ctMobile input[type='checkbox']").attr('checked', false);
}

function checkAllMulti(){
    if($('#ct_check_all').is(':checked'))
        $("#ctMulti input[type='checkbox']").attr('checked', true);
    else
        $("#ctMulti input[type='checkbox']").attr('checked', false);
}


function check(){
    return true;
}

function funcEdit(value){
    alert(value);
}

function funcDelete(value){
    alert(value);
 var record = 'id=' + value;

    
        if (confirm("Are you sure you want to delete this?"))
        {
           

            $.ajax(
            {
                   type: "POST",
                   url: "../wp-content/plugins/dudapro/admin/includes/delete.php",
                   data: record,
                   cache: false,

                   success: function()
                   {
           				
                    parent.fadeOut('slow', function() {$(this).remove();});
					
					
                   }
             });
        }
    

    // style the table with alternate colors
    // sets specified color for every odd row
    $('table#delTable tr:odd').css('background',' #FFFFFF');

	
	
}

var last_search='';
function onComplete(){

    if($('#ct1_search').val()!=last_search){
        last_search=$('#ct1_search').val();
        $('#ct2_search_value').animate({opacity: 0}, 10);
        $('#ct2_search').val($('#ct1_search').val());
        ctSearch('ct2');
    }

    if($('#ct2_search').val()!=last_search){
        last_search=$('#ct2_search').val();
        $('#ct1_search_value').animate({opacity: 0}, 10);
        $('#ct1_search').val($('#ct2_search').val());
        ctSearch('ct1');
    }

}


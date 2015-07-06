// Global vars
var enter_key=0;
var multiple_sort=0;

function ctSearchFocus(table_id){
    if($('#'+table_id+'_search').val()=='')
        $('#'+table_id+'_search_value').animate({opacity: 0.25}, 200);
}

function ctSearchBlur(table_id){
    if($('#'+table_id+'_search').val()=='')
        $('#'+table_id+'_search_value').animate({opacity: 1}, 200);
}

function ctSearchKeypress(table_id){
    if($('#'+table_id+'_search').val()=='')
        $('#'+table_id+'_search_value').animate({opacity: 0},10);
}

function ctSearch(table_id){
    if($('#'+table_id+'_search').val()=='')
        $('#'+table_id+'_search_value').animate({opacity: 0.25}, 200);

    $('#'+table_id+'_total_items').val(0);

    // if ENTER key is pressed then submit form
    if(enter_key)
        ctSubmitForm(table_id,'',1,false);

    return true;
}

function ctMultiSearch(table_id){
     $('#'+table_id+'_total_items').val(0);

    // if ENTER key is pressed then submit form
    if(enter_key)
        ctSubmitForm(table_id,'',1,false);

    return true;
}

function ctShowAdvancedSearch(table_id){
    $('#'+table_id+'_multiple_search').toggle();
}


function ctItemsPerPage(table_id){
    $('#'+table_id+'_items_per_page').val($('#'+table_id+'_items_per_page_change').val());
    ctSubmitForm(table_id,'',1,true);
}

function ctSort(table_id,sort_column){
    var sort_num;
    var sort_order;
    var max_num=1;
    var str_sort='';
    var sort_aux='';

    sort_aux=$('#'+table_id+'_sort').val();
    var arr_sort_aux=sort_aux.split('_');

    if(multiple_sort==1){

        for(i=0; i<arr_sort_aux.length; i++){
            sort_num=arr_sort_aux[i].substring(0,arr_sort_aux[i].length-1);

            if(sort_num>max_num)
                max_num=sort_num;
        }

        for(i=0; i<arr_sort_aux.length; i++){
            sort_num=arr_sort_aux[i].substring(0,arr_sort_aux[i].length-1);
            sort_order=arr_sort_aux[i].substring(arr_sort_aux[i].length-1);

            if(sort_column==i+1){
                str_sort+=(str_sort!='' ? '_' : '')+(arr_sort_order[sort_order]=='t' ? '' : (sort_num!='' ? sort_num : parseInt(max_num)+1))+(arr_sort_order[sort_order]=='' ? arr_sort_order["first"] : arr_sort_order[sort_order]);
            }else{
                str_sort+=(str_sort!='' ? '_' : '')+(sort_order=='f' ? 'f' : sort_num+sort_order);
            }
        }

        ctSubmitForm(table_id,str_sort,1,true);

    }else{

        for(i=0; i<arr_sort_aux.length; i++){
            sort_num=arr_sort_aux[i].substring(0,arr_sort_aux[i].length-1);
            sort_order=arr_sort_aux[i].substring(arr_sort_aux[i].length-1);

            if(sort_column==i+1){
                str_sort+=(str_sort!='' ? '_' : '')+(arr_sort_order[sort_order]=='t' ? '' : 1)+(arr_sort_order[sort_order]=='' ? arr_sort_order["first"] : arr_sort_order[sort_order]);
            }else{
                str_sort+=(str_sort!='' ? '_' : '')+(sort_order=='f' ? 'f' : 't');
            }
        }

        ctSubmitForm(table_id,str_sort,1,true);

    }
}

function ctActions(table_id){
    return true;
}

function ctPager(table_id,page){
    ctSubmitForm(table_id,'',page);
}

function onInit(){
    return true;
}

function onComplete(){
    return true;
}

function ctSubmitForm(table_id,sort,page,pass_total_items){

    onInit();

    if(sort!='')
        $('#'+table_id+'_sort').val(sort);
    if(page!='')
        $('#'+table_id+'_page').val(page);

    document.forms[table_id+'_form'].submit();

    onComplete();
}

$(document).ready(function(){

    $(document).keydown(function(e) {
        if (e.keyCode==13){
            enter_key=1;
        }else{
            enter_key=0;
        }

        if (e.shiftKey || e.ctrlKey || e.altKey)
            multiple_sort=1;
    }).keyup(function(e) {
        multiple_sort=0;
    });

});
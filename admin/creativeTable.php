<?php

/*
creativeTable version 1.4
created by Creative Dreams @ 14-05-2010
last update @ 26-09-2014

<form id="ct_form">
  <input type="hidden" id="ct_items_per_page">
  <input type="hidden" id="ct_sort">
  <input type="hidden" id="ct_page">
  <input type="hidden" id="ct_total_items">

  <div id="search_container">
    <input id="ct_search" />
  </div>

  <div id="ct_items_per_page_container">
    <select id="ct_items_per_page_change"></select>
  </div>

  <table id="ct" class="">
    <thead>
      <tr id="ct_sort">
        <th></th>
      </tr>
      <tr id="ct_multiple_search">
        <th><input id="ct_multiple_search1" /></th>
      </tr>
    </thead>
    <tbody>
      <tr class="odd">
        <td></td>
      </tr>
      <tr class="even">
        <td></td>
      </tr>
    </tbody>
  </table>

  <div id="ct_actions_container">
    <select id="ct_actions"></select>
  </div>

  <div id="ct_pager_container">
    ...
  </div>

</form>
*/

class CreativeTable{

    var $sql_query;             // SQL query for all the data
    var $table_key;             // primary key of the table (must be an integer)
    var $data;                  // data to build the table (data gathering)
    var $search;                // search selected (data gathering)
    var $multiple_search;       // multi search selected (data gathering)
    var $items_per_page;        // items per page selected (data gathering)
    var $sort;                  // selected column 1a_2d_t_t (data gathering)
    var $page;                  // selected page (for the sql query)
    var $total_items;           // total items (got from sql query or the 2D array)

    var $id;                    // id of the table
    var $class;                 // class of the table
    var $form_init;             // true, false show or not to show the form
    var $form_url;              // form url
    var $form_method;           // form method GET or POST
    var $header;                // text for the header i.e. 'ID,Movie Title,Any Text,...' or true or false
    var $table_width;           // total width of the table (100%, 700px, '')
    var $width;                 // ''; '15,100,200,50'
    var $search_init;           // false, true, ttftt
    var $search_type;           // search type (like or = or beginning_line or end_like)
    var $search_html;           // html with search configuration
    var $search_placeholder;    // search text placeholder
    var $multiple_search_init;  // hide, true, false, ttftt, ttftt hide
    var $multiple_search_type;  // multiple search type (like or  = or beginning_line or end_like)
    var $items_per_page_init;   // false; 10,20,50,100; ($i+1)*10
    var $items_per_page_all;    // text for the show all option: All; false; #TOTAL_ITEMS#
    var $items_per_page_url;    // index.php or javascript: myFunc();
    var $items_per_page_ids;    // items per page ids (if more than one items per page for the same table)
    var $sort_init;             // true, false ttftt
    var $sort_order;            // 'adt';'ad';'da';'dat'; (ascending, descending, true)
    var $sort_url;              // index.php or javascript: myFunc();
    var $extra_cols;            // array containing the the information about extra columns array(array(col,header,width,html),array(...),...)
    var $odd_even;              // true, false
    var $no_results;            // false; html for the no results
    var $actions;               // array containing the value and the text of the select box array(array($value,$text),...)
    var $actions_url;           // text function when the select box of actions is changed
    var $pager;                 // external html pager
    var $pager_ids;             // pager ids (if more than one pager for the same table)
    var $ajax_url;              // url to call then ajax event occurs
    var $format_cols;           // array(html or text, ...)
    var $display_cols;          // ttftt (display columns 1,2,4,5
    var $export_filename;       // text with the filename
    var $export_header;         // to include the header in the xls or in the csv
    var $csv_delimiter;         // csv delimiter
    var $csv_enclosure;         // csv enclosure
    var $charset;               // charset of the country (UTF-8, ISO-8859-1, ISO-8859-2, ...)
    var $extra_vars;            // html ids of input boxes to pass to PHP when submiting the form '#my_var, .my_class'
    var $where_having;          // wether the search is using the WHERE statement of the HAVING
    var $debug;                 // 0 - don't show any debug; 1 - show debug on the table; 2 - show a list of debug information

    var $row_attributes;        // attributes of a row
    var $cell_attributes;       // attributes of a cell
    var $sql_error;             // sql error msg
    var $sql_fields;            // sql fields (got from sql query)
    var $out;                   // output of the table

    function table($params){

        // Default Values
        $this->sql_query            = isset($params['sql_query']) ? trim($params['sql_query']) : '';
        $this->table_key            = isset($params['table_key']) ? $params['table_key'] : '';
        $this->data                 = isset($params['data']) ? $params['data'] : array();
        $this->search               = isset($params['search']) ? $params['search'] : '';
        $this->multiple_search      = isset($params['multiple_search']) ? $params['multiple_search'] : '';
        $this->items_per_page       = isset($params['items_per_page']) ? $params['items_per_page'] : '';
        $this->sort                 = isset($params['sort']) ? $params['sort'] : false;
        $this->page                 = isset($params['page']) ? $params['page'] : 1;
        $this->total_items          = isset($params['total_items']) ? ($params['total_items']>=0 ? $params['total_items'] : '') : '';

        $this->id                   = isset($params['id']) ? $params['id'] : 'ct';
        $this->class                = isset($params['class']) ? $params['class'] : 'ct';
        $this->form_init            = isset($params['form_init']) ? $params['form_init'] : true;
        $this->form_url             = isset($params['form_url']) ? $params['form_url'] : $_SERVER['PHP_SELF'];
        $this->form_method          = isset($params['form_method']) ? $params['form_method'] : 'GET';
        $this->header               = isset($params['header']) ? $params['header'] : true;
        $this->table_width          = isset($params['table_width']) ? $params['table_width'] : '';
        $this->width                = isset($params['width']) ? $params['width'] : '';
        $this->search_init          = isset($params['search_init']) ? $params['search_init'] : true;
        $this->search_type          = isset($params['search_type']) ? $params['search_type'] : 'like';
        $this->search_html          = isset($params['search_html']) ? $params['search_html'] : '<a id="#ID#_advanced_search" class="#CLASS#_advanced_search" href="javascript: ctShowAdvancedSearch(\'#ID#\');" title="Advanced Search"></a><div id="#ID#_loader" class="#CLASS#_loader"></div>';
        $this->search_placeholder   = isset($params['search_placeholder']) ? $params['search_placeholder'] : 'Search';
        $this->multiple_search_init = isset($params['multiple_search_init']) ? $params['multiple_search_init'] : 'hide';
        $this->multiple_search_type = isset($params['multiple_search_type']) ? $params['multiple_search_type'] : 'like';
        $this->items_per_page_init  = isset($params['items_per_page_init']) ? $params['items_per_page_init'] : '10*$i';
        $this->items_per_page_all   = isset($params['items_per_page_all']) ? (($params['items_per_page_all']!='' or $params['items_per_page_all']===false) ? $params['items_per_page_all'] : '#TOTAL_ITEMS#') : '#TOTAL_ITEMS#';
        $this->items_per_page_url   = isset($params['items_per_page_url']) ? $params['items_per_page_url'] : 'ctItemsPerPage(\'#ID#\')';
        $this->items_per_page_ids   = isset($params['items_per_page_ids']) ? $params['items_per_page_ids'] : '';
        $this->sort_init            = isset($params['sort_init']) ? $params['sort_init'] : true;
        $this->sort_order           = isset($params['sort_order']) ? $params['sort_order'] : 'adt';
        $this->sort_url             = isset($params['sort_url']) ? $params['sort_url'] : 'ctSort(\'#ID#\',\'#COLUMN_ID#\')';
        $this->extra_cols           = isset($params['extra_cols']) ? $params['extra_cols'] : array();
        $this->odd_even             = isset($params['odd_even']) ? $params['odd_even'] : true;
        $this->no_results           = isset($params['no_results']) ? $params['no_results'] : 'No results found.';
        $this->actions              = isset($params['actions']) ? $params['actions'] : array();
        $this->actions_url          = isset($params['actions_url']) ? $params['actions_url'] : 'ctActions(\'#ID#\')';
        $this->pager                = isset($params['pager']) ? $params['pager'] : '';
        $this->pager_ids            = isset($params['pager_ids']) ? $params['pager_ids'] : '';
        $this->ajax_url             = isset($params['ajax_url']) ? $params['ajax_url'] : $_SERVER['PHP_SELF'];
        $this->format_cols          = isset($params['format_cols']) ? $params['format_cols'] : array();
        $this->display_cols         = isset($params['display_cols']) ? $params['display_cols'] : '';
        $this->export_filename      = isset($params['export_filename']) ? $params['export_filename'] : 'creative_table.csv';
        $this->export_header        = isset($params['export_header']) ? $params['export_header'] : true;
        $this->csv_delimiter        = isset($params['csv_delimiter']) ? $params['csv_delimiter'] : ',';
        $this->csv_enclosure        = isset($params['csv_enclosure']) ? $params['csv_enclosure'] : '';
        $this->charset              = isset($params['charset']) ? $params['charset'] : 'UTF-8';
        $this->extra_vars           = isset($params['extra_vars']) ? $params['extra_vars'] : '';
        $this->where_having         = isset($params['where_having']) ? $params['where_having'] : false;
        $this->debug                = isset($params['debug']) ? $params['debug'] : 1;

        $this->row_attributes       = array();
        $this->cell_attributes      = array();
        $this->sql_error            = '';
        $this->sql_fields           = '';
        $this->out                  = '';

        $this->init_data();

        if($this->sql_query!='')
            $this->init_data_sql();
        else
            $this->init_data_array();

    }

    // Auxiliar function - trims an array
    function array_trim($array_to_trim){
        if(count($array_to_trim)>0){
            for($i=0; $i<count($array_to_trim); $i++){
                $array_to_trim[$i]=trim($array_to_trim[$i]);
            }
        }
        return $array_to_trim;
    }

    // Makes somes necessary initializations
    function init_data(){

        // default value of items per page
        if($this->items_per_page==''){
            // formula $i*10; pow(10,$i)
            if(strpos($this->items_per_page_init,'$')!==false){
                $i=1;
                eval('$items_per_page='.$this->items_per_page_init.';');
            }else{
                $items_per_page=explode(',',$this->items_per_page_init);
                $items_per_page=$items_per_page[0];
            }

            $this->items_per_page=$items_per_page;
        }

    }

    // Gets the data from the database and makes somes necessary initializations
    function init_data_sql(){

        // removes carriages return
        $this->sql_query = str_replace("\r", "", str_replace("\r\n", "", $this->sql_query));

        $flag_no_items=0;

        // adds the new extra columns to the data
        for($i=0; $i<count($this->extra_cols); $i++)
            $this->add_col($this->extra_cols[$i][0],$this->extra_cols[$i][1],$this->extra_cols[$i][2],$this->extra_cols[$i][3],'init');

        // Gets the fields names
        $result = mysql_query($this->sql_query);

        if($result){

            $arr_header = explode(',',$this->header);
            $i = 0;
            while ($i < mysql_num_fields($result)) {
                $meta = mysql_fetch_field($result, $i);

                /*
                stdClass Object
                (
                    [name] => id
                    [table] => imdbtop250
                    [def] =>
                    [max_length] => 2
                    [not_null] => 1
                    [primary_key] => 1
                    [multiple_key] => 0
                    [unique_key] => 0
                    [numeric] => 1
                    [blob] => 0
                    [type] => int
                    [unsigned] => 1
                    [zerofill] => 0
                )
                */
                $this->sql_fields[$i] = $meta->table=='' ? $meta->name : $meta->table.'.`'.$meta->name.'`';

                //if($this->header!==false and ($this->header===true or !isset($arr_header[$i]) or $arr_header[$i]=='')){
                    //$arr_header[$i] = $meta->name;
                //}

                $i++;
            }

            //if($this->header!==false)
                //$this->header = implode(',',$arr_header);

        }

        // Converts non UTF-8 searches to the selected charset
        if($this->charset!='UTF-8')
            $this->search=iconv('UTF-8',$this->charset,$this->search);

        $multiple_search_empty=1;

        for($i=0; $i<count($this->multiple_search); $i++){
            if(!empty($this->multiple_search[$i]))
                $multiple_search_empty=0;

                if($this->charset!='UTF-8')
                    $this->multiple_search[$i]=iconv('UTF-8',$this->charset,$this->multiple_search[$i]);
        }

        //if($this->display_cols[$i]!=''){
            for($i=0; $i<strlen($this->display_cols); $i++){
                if($this->display_cols[$i]=='f')
                    array_splice($this->multiple_search, $i, 0, '');
            }
        //}

        $sort_empty=1;
        if(strpos($this->sort,'a'))
            $sort_empty=0;
        if(strpos($this->sort,'d'))
            $sort_empty=0;

        if(!$flag_no_items){

            // gets the data from the DB
            if($this->table_key!='' and $this->search=='' and $multiple_search_empty==1 and $sort_empty==1){
                $result = mysql_query($this->get_sql(true));
                if(!$result)
                    $this->sql_error=mysql_error();
            }else{
                $result = mysql_query($this->get_sql());
                if(!$result)
                    $this->sql_error=mysql_error();
            }

            if($result) {

                // Gets the data
                while ($row = mysql_fetch_row($result))
                    $this->data[]=$row;

                mysql_free_result($result);

            }

            // total of items
            if($this->total_items<=0){

                $result = mysql_query( 'SELECT FOUND_ROWS()' );
                $this->total_items=mysql_fetch_row($result);
                $this->total_items=$this->total_items[0];

                mysql_free_result($result);

            }

            if($this->sql_error!='')
                $this->total_items=0;

        }else{
            $this->total_items=0;
        }

        // adds the new extra columns to the data
        for($i=0; $i<count($this->extra_cols); $i++)
            $this->add_col($this->extra_cols[$i][0],$this->extra_cols[$i][1],$this->extra_cols[$i][2],$this->extra_cols[$i][3],'data');

        //mysql_close($result);

    }

    // Gets the data from an array and makes somes necessary initializations
    function init_data_array(){

        // adds the new extra columns to the data
        for($i=0; $i<count($this->extra_cols); $i++)
            $this->add_col($this->extra_cols[$i][0],$this->extra_cols[$i][1],$this->extra_cols[$i][2],$this->extra_cols[$i][3],'init');

        // where
        $removed_row=0;
        $total_multiple_search=0;

        foreach ($this->data as $key => $row) {

            $flag_found=0;
            $flag_found_part=0;

            for($i=0; $i<count($row); $i++){

                if($this->search_type=='='){
                    if($this->search!='' and $this->search_init[$i]!='f' and $row[$i]==$this->search)
                        $flag_found=1;
                }elseif($this->search_type=='beginning_like'){
                    if($this->search!='' and $this->search_init[$i]!='f' and substr($row[$i],0,strlen($this->search))==$this->search)
                        $flag_found=1;
                }elseif($this->search_type=='end_like'){
                    if($this->search!='' and $this->search_init[$i]!='f' and substr($row[$i],-strlen($this->search))==$this->search)
                        $flag_found=1;
                }else{
                    if($this->search!='' and $this->search_init[$i]!='f' and stripos($row[$i],$this->search)!==false)
                        $flag_found=1;
                }


                if(empty($this->multiple_search[$i]))
                    $this->multiple_search[$i]='';
                else
                    if($key==0)
                        $total_multiple_search++;

                if($this->multiple_search_type=='='){
                    if(count($this->multiple_search)>0 and $this->multiple_search[$i]!='' and $this->multiple_search_init[$i]!='f' and strtolower($this->multiple_search[$i])==strtolower($row[$i]))
                        $flag_found_part++;
                }elseif($this->multiple_search_type=='beginning_like'){
                    if(count($this->multiple_search)>0 and $this->multiple_search[$i]!='' and $this->multiple_search_init[$i]!='f' and strtolower(substr($row[$i],0,strlen($this->multiple_search[$i])))==strtolower($this->multiple_search[$i]))
                        $flag_found_part++;
                }elseif($this->multiple_search_type=='end_like'){
                    if(count($this->multiple_search)>0 and $this->multiple_search[$i]!='' and $this->multiple_search_init[$i]!='f' and strtolower(substr($row[$i],-strlen($this->multiple_search[$i])))==strtolower($this->multiple_search[$i]))
                        $flag_found_part++;
                }else{
                    if(count($this->multiple_search)>0 and $this->multiple_search[$i]!='' and $this->multiple_search_init[$i]!='f' and stripos($row[$i],$this->multiple_search[$i])!==false)
                        $flag_found_part++;
                }

                if($i==(count($row)-1)){
                    if($flag_found_part==$total_multiple_search and $total_multiple_search>0){
                        if(($this->search!='' and $this->search_init[$i]!='f' and $flag_found==1) or $this->search=='')
                            $flag_found=1;
                    }else{
                        if($flag_found==1 and $total_multiple_search>0)
                            $flag_found=0;
                    }
                }

            }

            if(($this->search!='' or $total_multiple_search>0) and !$flag_found){
                array_splice($this->data, $key-$removed_row, 1);
                $removed_row++;
            }

        }

        $this->total_items=count($this->data);

        // sort
        $arr_sort=explode('_',$this->sort);
        asort($arr_sort);

        $order_str = '';
        foreach($arr_sort as $key => $value){
            if(substr($arr_sort[$key],-1)=='a')
                $order_str.='$arr_field'.$key.', SORT_ASC, ';

            if(substr($arr_sort[$key],-1)=='d')
                $order_str.='$arr_field'.$key.', SORT_DESC, ';
        }

        foreach ($this->data as $key => $row) {
            for($i=0; $i<count($row); $i++)
                ${'arr_field'.$i}[]=$row[$i];
        }

        if(count($this->data)>0)
            eval('array_multisort('.$order_str.' $this->data);');

        // items per page
        if($this->items_per_page!='all' and $this->items_per_page!='')
            $this->data=array_splice($this->data, ($this->page-1)*$this->items_per_page, $this->items_per_page);


        // adds the new extra columns to the data
        for($i=0; $i<count($this->extra_cols); $i++)
            $this->add_col($this->extra_cols[$i][0],$this->extra_cols[$i][1],$this->extra_cols[$i][2],$this->extra_cols[$i][3],'data');

    }

    // Adds a new row i.e. $ct->add_row(array(69,69,'ola',69),3);
    function add_row($arr_html,$row){
        array_splice($this->data, $row-1, 0, array($arr_html));
    }

    // Adds a new column i.e. $ct->add_col(1,'Check','<input type="checkbox" name="check" />','50');
    function add_col($col,$header,$width,$html,$op='init_data'){

        if($op=='init_data')
            $this->extra_cols[]=array($col,$header,$width,$html);

        if(strpos($op,'init')!==false){

            // adds the new header
            $arr_header=explode(',',$this->header);

            if($col>count($arr_header)+1)
                $col=count($arr_header)+1;

            array_splice($arr_header, $col-1, 0, $header);
            $this->header=implode(',',$arr_header);

            // adds the new column width
            $arr_width=explode(',',$this->width);
            array_splice($arr_width, $col-1, 0, $width);
            $this->width=implode(',',$arr_width);

            // rearrange the sort string
            if($this->sort_init===true){
                $this->sort_init=str_repeat('t',count($arr_header));
                $this->sort_init[$col-1]='f';
            }else if($this->sort_init!==true and $this->sort_init!==false){
                $this->sort_init=substr_replace($this->sort_init,'f',$col-1,0);
            }

            // rearrange the search_init string
            if($this->search_init===true){
                $this->search_init=str_repeat('t',count($arr_header));
                $this->search_init[$col-1]='f';
            }elseif($this->search_init!==true and $this->search_init!==false){
                $this->search_init=substr_replace($this->search_init,'f',$col-1,0);
            }

            // rearrange the multiple_search_init string
            if($this->multiple_search_init===true){
                $this->multiple_search_init=str_repeat('t',count($arr_header));
                $this->multiple_search_init[$col-1]='f';
            }else if($this->multiple_search_init=='hide'){
                $this->multiple_search_init=str_repeat('t',count($arr_header));
                $this->multiple_search_init[$col-1]='f';
                $this->multiple_search_init.='hide';
            }else if($this->multiple_search_init!==true and $this->multiple_search_init!==false){
                $this->multiple_search_init=substr_replace($this->multiple_search_init,'f',$col-1,0);
            }

/*
            // rearrange the display_cols string
            if($this->display_cols==''){
                $this->display_cols=str_repeat('t',count($arr_header));
                $this->display_cols[$col-1]='t';
            }else{
                // rearrange the display_cols string
                $this->display_cols = substr_replace($this->display_cols,'t',$col-1,0);
            }
*/

        }

        if(strpos($op,'data')!==false){
            // add the new column in all rows
            if($this->total_items>0){
                for($i=0; $i<count($this->data); $i++)
                    array_splice($this->data[$i], $col-1, 0, array($html));
            }
        }

    }

    // Rearrange the sort string
    function init_sort(){
        $out='';
        if(($this->sort===true or $this->sort=='') and count($this->data)>0){
            for($i=0; $i<count($this->data[0]); $i++)
                $out.=($out ? '_' : '').'t';
            $this->sort=$out;
        }
    }

    // Return the safe character for %, _, ' and "
    function get_sql_safe_special_characters($search){
        $search=str_replace('%','\%',$search);
        $search=str_replace('_','\_',$search);
        $search=str_replace("'","\'",$search);
        $search=str_replace('"','\"',$search);

        return $search;
    }

    // Gets the sql query corresponding to tables parameters
    // $type - select, from, where, group by, having, order by, limit
    function get_sql_expressions($type='select',$pass_clause=false){

        $sql_expression='';

        $arr_clauses_cut=array('select'=>'SELECT ','from'=>' FROM ','where'=>' WHERE ','group by'=>' GROUP BY ','having'=>' HAVING ','order by'=>' ORDER BY ','limit'=>' LIMIT ');
        $arr_clauses_cut_keys=array_keys($arr_clauses_cut);

        if(in_array($type,$arr_clauses_cut_keys)){

            // Check where to cut the sql
            $initial_key='';
            $key_to_cut='';
            foreach($arr_clauses_cut as $key => $value){

                if($initial_key!='' and $key_to_cut=='' and strripos($this->sql_query,$value)!==false)
                    $key_to_cut=$key;

                if($initial_key=='' and $key==$type)
                    $initial_key=$key;

            }

            if(stripos($this->sql_query,$arr_clauses_cut[$initial_key])===false)
                $sql_expression='';
            elseif($key_to_cut=='')
                $sql_expression=($pass_clause ? $arr_clauses_cut[$initial_key] : '').substr($this->sql_query,strripos($this->sql_query,$arr_clauses_cut[$initial_key])+strlen($arr_clauses_cut[$initial_key]));
            else
                $sql_expression=($pass_clause ? $arr_clauses_cut[$initial_key] : '').substr($this->sql_query,strripos($this->sql_query,$arr_clauses_cut[$initial_key])+strlen($arr_clauses_cut[$initial_key]),strripos($this->sql_query,$arr_clauses_cut[$key_to_cut])-(strripos($this->sql_query,$arr_clauses_cut[$initial_key])+strlen($arr_clauses_cut[$initial_key])));

        }

        return $sql_expression;
    }

    // Gets final composed sql query
    function get_sql($check_table_key=false){
        $sql='';
        if($check_table_key)
            $sql=$this->get_sql_select().$this->get_sql_from().$this->get_sql_where(true).$this->get_sql_group().$this->get_sql_having().$this->get_sql_order();
        elseif($this->where_having)
            $sql=$this->get_sql_select().$this->get_sql_from().$this->get_sql_group().$this->get_sql_having().(($this->get_sql_where(false)!='' and strpos($this->get_sql_where(false),' WHERE ')===false and $this->where_having) ? ' HAVING ' : '').str_replace(' WHERE ',' HAVING ',$this->get_sql_where(false)).$this->get_sql_order().$this->get_sql_limit();
        else
            $sql=$this->get_sql_select().$this->get_sql_from().$this->get_sql_where(false).$this->get_sql_group().$this->get_sql_having().$this->get_sql_order().$this->get_sql_limit();

        return $sql;
    }

    // Gets the sql query corresponding to selecting fields and tables parameters
    function get_sql_select(){
        return 'SELECT SQL_CALC_FOUND_ROWS '.substr($this->sql_query,6,strripos($this->sql_query,' FROM ')-6);
    }

    // Gets the sql query corresponding to selecting fields and tables parameters
    function get_sql_from(){
        return $this->get_sql_expressions('from',true);
    }

    // Gets the sql query corresponding to conditions parameters
    function get_sql_where($check_table_key=true){

        $where_str_ini=$this->get_sql_expressions('where');
        $where_str_ini=$where_str_ini=='' ? $where_str_ini : '('.$where_str_ini.')';

        $where_str='';
        $multiple_search_str='';

        // adds the extra columns in consideration
        $arr_sql_fields=$this->sql_fields;
        for($i=0; $i<count($this->extra_cols); $i++)
            array_splice($arr_sql_fields, $this->extra_cols[$i][0]-1, 0, '');

        for($i=0; $i<count($arr_sql_fields); $i++){
            if(empty($this->multiple_search[$i]))
                $this->multiple_search[$i]='';

            if($this->search!='' and $this->search_init[$i]!='f'){
                if($this->search_type=='=')
                    $where_str.=($where_str ? ' OR ' : '(').$arr_sql_fields[$i]." = '".$this->get_sql_safe_special_characters($this->search)."'";
                elseif($this->search_type=='beginning_like')
                    $where_str.=($where_str ? ' OR ' : '(').$arr_sql_fields[$i]." LIKE '".$this->get_sql_safe_special_characters($this->search)."%'";
                elseif($this->search_type=='end_like')
                    $where_str.=($where_str ? ' OR ' : '(').$arr_sql_fields[$i]." LIKE '%".$this->get_sql_safe_special_characters($this->search)."'";
                else
                    $where_str.=($where_str ? ' OR ' : '(').$arr_sql_fields[$i]." LIKE '%".$this->get_sql_safe_special_characters($this->search)."%'";
            }

            if(count($this->multiple_search)>0 and $this->multiple_search[$i]!='' and $this->multiple_search_init[$i]!='f'){
                if($this->multiple_search_type=='=')
                    $multiple_search_str.=(($where_str_ini or $where_str or $multiple_search_str) ? ' AND ' : '').$arr_sql_fields[$i]." = '".$this->get_sql_safe_special_characters($this->multiple_search[$i])."'";
                if($this->multiple_search_type=='beginning_like')
                    $multiple_search_str.=(($where_str_ini or $where_str or $multiple_search_str) ? ' AND ' : '').$arr_sql_fields[$i]." LIKE '".$this->get_sql_safe_special_characters($this->multiple_search[$i])."%'";
                if($this->multiple_search_type=='end_like')
                    $multiple_search_str.=(($where_str_ini or $where_str or $multiple_search_str) ? ' AND ' : '').$arr_sql_fields[$i]." LIKE '%".$this->get_sql_safe_special_characters($this->multiple_search[$i])."'";
                else
                    $multiple_search_str.=(($where_str_ini or $where_str or $multiple_search_str) ? ' AND ' : '').$arr_sql_fields[$i]." LIKE '%".$this->get_sql_safe_special_characters($this->multiple_search[$i])."%'";
            }
        }

        if($where_str!='')
            $where_str.=')';

        if($this->table_key!='' and $check_table_key)
            $where_str.=($where_str ? ' AND ' : '').$this->table_key.">'".(($this->page-1)*$this->items_per_page)."' AND ".$this->table_key."<='".(($this->page-1)*$this->items_per_page+$this->items_per_page)."'";

        return  (($where_str_ini or $where_str or $multiple_search_str) ? ' WHERE ' : '').$where_str_ini.(($where_str_ini and $where_str) ? ' AND ' : '').$where_str.$multiple_search_str;
    }

    // Gets the sql query corresponding to group parameters
    function get_sql_group(){
        return $this->get_sql_expressions('group by',true);
    }

    // Gets the sql query corresponding to having parameters
    function get_sql_having(){
        return $this->get_sql_expressions('having',true);
    }

    // Gets the sql query corresponding to order parameters
    function get_sql_order(){

        $order_str_ini=$this->get_sql_expressions('order by',true);

        $order_str='';
        $arr_new_cols=array();

        // adds the extra columns in consideration
        $arr_sql_fields=$this->sql_fields;
        for($i=0; $i<count($this->extra_cols); $i++){
            array_splice($arr_sql_fields, $this->extra_cols[$i][0]-1, 0, '');
            $arr_new_cols[]=$this->extra_cols[$i][0];
        }

        $arr_sort=explode('_',$this->sort);
        asort($arr_sort);

        foreach($arr_sort as $key => $value){
            if(!in_array($key+1,$arr_new_cols)){
                if(substr($arr_sort[$key],-1)=='a')
                    $order_str.=(($order_str_ini or $order_str) ? ', ' : ' ORDER BY ').$arr_sql_fields[$key].' ASC';

                if(substr($arr_sort[$key],-1)=='d')
                    $order_str.=(($order_str_ini or $order_str) ? ', ' : ' ORDER BY ').$arr_sql_fields[$key].' DESC';
            }
        }

        return $order_str_ini.$order_str;
    }

    // Gets the sql query corresponding to limit parameters
    function get_sql_limit(){
        $limit_str='';

        if($this->items_per_page!='all' and $this->items_per_page!='')
            $limit_str=' LIMIT '.($this->page-1)*$this->items_per_page.','.$this->items_per_page;

        return $limit_str;
    }

    // Analises the url passed, if it has the tag #COLUMN_ID# it substitues for the true value of the page,
    // otherwise puts ?pag=1 or &pag=1 in the end of url
    function get_url($column){
        if(strpos($this->sort_url,'#COLUMN_ID#')!==false)
            return str_replace('#COLUMN_ID#',$column,$this->sort_url);
        else
            return $this->sort_url.(strpos($this->sort_url,'?')!==false ? '&' : '?').'sort='.$this->sort;
    }

    // Change some specific tags to their corresponding value
    function change_tags($str){
        $str=str_replace('#ID#',$this->id,$str);
        $str=str_replace('#CLASS#',$this->class,$str);
        $str=str_replace('#PAGE#',$this->page,$str);
        $str=str_replace('#ITEMS_PER_PAGE#',$this->items_per_page,$str);
        $str=str_replace('#TOTAL_ITEMS#',$this->total_items,$str);

        return $str;
    }

    // Change the column tags for their value #COL1#, #COL2#, ...
    function change_tag_col($str,$arr_cols){
        preg_match_all('/#COL(\d+)#/i', $str, $matches, PREG_SET_ORDER);

        // remove the columns that was added with the add_col function
        for($i=0; $i<count($this->extra_cols); $i++){
            array_splice($arr_cols, $this->extra_cols[$i][0]-1-$i, 1);
        }

        for($i=0; $i<count($matches); $i++){
            if(isset($arr_cols[$matches[$i][1]-1]))
                $str=str_replace($matches[$i][0], addslashes($arr_cols[$matches[$i][1]-1]), $str);
        }

        return $str;
    }

    // Draw the form
    function draw_form(){
        $out='';

        if($this->form_init)
            $out='<form id="'.$this->id.'_form"'.($this->class!='' ? ' class="'.$this->class.'_form"' : '').' name="'.$this->id.'_form" method="'.$this->form_method.'" action="'.$this->form_url.'">
            <input type="hidden" id="'.$this->id.'_items_per_page" name="'.$this->id.'_items_per_page" value="'.$this->items_per_page.'" />
            <input type="hidden" id="'.$this->id.'_sort" name="'.$this->id.'_sort" value="'.$this->sort.'" />
            <input type="hidden" id="'.$this->id.'_page" name="'.$this->id.'_page" value="'.$this->page.'" />
            <input type="hidden" id="'.$this->id.'_total_items" name="'.$this->id.'_total_items" value="'.$this->total_items.'" />';

        return $out;
    }

    // Draw the search component
    function draw_search(){
        $out='';
        if($this->search_init)
            $out.='<input type="text" id="'.$this->id.'_search"'.($this->class!='' ? ' class="'.$this->class.'_search"' : '').' name="'.$this->id.'_search" value="'.$this->search.'" placeholder="'.$this->search_placeholder.'" onfocus="ctSearchFocus(\''.$this->id.'\');" onblur="ctSearchBlur(\''.$this->id.'\');" onkeypress="ctSearchKeypress(\''.$this->id.'\');" onkeyup="ctSearch(\''.$this->id.'\');" />'.$this->change_tags($this->search_html);

        return $out;
    }

    // Draw the items_per_page component
    function draw_items_per_page($id_items_per_page=''){
        $out='';

        if($id_items_per_page=='')
            $id_items_per_page=$this->id;

        if($this->items_per_page_init!==false and $this->total_items>0){

            $out='<select id="'.$id_items_per_page.'_items_per_page_change"'.($this->class!='' ? ' class="'.$this->class.'_items_per_page_change"' : '').' name="'.$id_items_per_page.'_items_per_page_change" onchange="'.$this->change_tags($this->items_per_page_url).'">';

            // formula $i*10; pow(10,$i)
            if(strpos($this->items_per_page_init,'$')!==false){

                $i=1;

                eval('$value='.$this->items_per_page_init.';');

                while ($value<$this->total_items) {

                    $out.='<option value="'.$value.'"'.($value==$this->items_per_page ? ' selected="selected"' : '').'>'.$value.'</option>';

                    $i++;

                    eval('$value='.$this->items_per_page_init.';');

                }

            }else{

                $i=0;

                $arr_items_per_page=explode(',',$this->items_per_page_init);

                while ($i<count($arr_items_per_page) and $arr_items_per_page[$i]<$this->total_items) {

                    $out.='<option value="'.$arr_items_per_page[$i].'"'.($arr_items_per_page[$i]==$this->items_per_page ? ' selected="selected"' : '').'>'.$arr_items_per_page[$i].'</option>';

                    $i++;

                }

            }

            if($this->items_per_page_all!='')
                $out.='<option value="all"'.('all'==$this->items_per_page ? ' selected="selected"' : '').'>'.$this->change_tags($this->items_per_page_all).'</option>';

            $out.='</select>';
        }

        return $out;
    }

    // Draw the header of the table
    function draw_header(){

        $out_multiple_search='';

        $arr_width=explode(',',$this->width);
        $out='<tr id="'.$this->id.'_sort">';

        $arr_sort=explode('_',$this->sort);
        $arr_header=explode(',',$this->header);

        $column=1;
        for($i=0; $i<count($arr_header);$i++){

            if($this->display_cols!='' and isset($this->display_cols[$i]) and $this->display_cols[$i]!='f' or $this->display_cols==''){
                if($this->sort_init!==false and $this->sort_init[$i]!='f')
                    $out.='<th'.(($this->width!='' and isset($arr_width[$i]) and $arr_width[$i]>0) ? ' width="'.$arr_width[$i].'"' : '').' onclick="'.$this->change_tags($this->get_url($i+1)).'"'.(isset($arr_sort[$i]) ? ($arr_sort[$i]=='f' ? ' class="no_sort' : ' class="sort').(substr($arr_sort[$i],-1)=='a' ? '_asc' : (substr($arr_sort[$i],-1)=='d' ? '_desc' : '')) : '').'">'.$arr_header[$i].'</th>';
                else
                    $out.='<th'.(($this->width!='' and isset($arr_width[$i]) and $arr_width[$i]>0) ? ' width="'.$arr_width[$i].'"' : '').'>'.$arr_header[$i].'</th>';

                if($this->multiple_search_init===true or $this->multiple_search_init=='hide' or (strpos($this->multiple_search_init,'hide')!==false and $this->multiple_search_init[$i]=='t') or $this->multiple_search_init[$i]=='t')
                    $out_multiple_search.='<th><input type="text" id="'.$this->id.'_multiple_search'.($i+1).'" name="'.$this->id.'_multiple_search[]'.'" value="'.(isset($this->multiple_search[$i]) ? $this->multiple_search[$i] : '').'" onkeyup="ctMultiSearch(\''.$this->id.'\');" /></th>';
                else
                    $out_multiple_search.='<th><input type="text" id="'.$this->id.'_multiple_search'.($i+1).'" name="'.$this->id.'_multiple_search[]'.'" value="'.(isset($this->multiple_search[$i]) ? $this->multiple_search[$i] : '').'" onkeyup="ctMultiSearch(\''.$this->id.'\');" style="display: none;" /></th>';
            }

        }

        $out.='</tr>';

        if($this->multiple_search_init===true or strpos($this->multiple_search_init,'hide')!==false or strpos($this->multiple_search_init,'t')!==false)
            $out.='<tr id="'.$this->id.'_multiple_search"'.($this->class!='' ? ' class="'.$this->class.'_multiple_search"' : '').(($this->multiple_search_init!==true and strpos($this->multiple_search_init,'hide')!==false) ? ' style="display: none;"' : '').'>'.$out_multiple_search.'</tr>';

        return $out;
    }

    // Draw the body of the table
    function draw_body(){
        $out='';

        if($this->total_items>0){
            $arr_width=explode(',',$this->width);
            for($i=0; $i<count($this->data);$i++){
                $row_attribute='';
                foreach($this->row_attributes as $key2 => $value2){
                    $arr_row_attributes = explode('|',$this->row_attributes[$key2]);
                    if($arr_row_attributes[0]-1==$i)
                        $row_attribute.=' '.$arr_row_attributes[1];
                }
                $out.='<tr'.($this->odd_even ? ($i%2==0 ? ' class="odd"' : ' class="even"') : '').$row_attribute.'>';
                $j=0;
                foreach($this->data[$i] as $key => $value){
                    $cell_attribute='';
                    foreach($this->cell_attributes as $key2 => $value2){
                        $arr_cell_attributes = explode('|',$this->cell_attributes[$key2]);
                        if($arr_cell_attributes[0]-1==$i and $arr_cell_attributes[1]-1==$j or $arr_cell_attributes[0]-1==$i and $arr_cell_attributes[1]=='' or $arr_cell_attributes[0]=='' and $arr_cell_attributes[1]-1==$j)
                            $cell_attribute.=' '.$arr_cell_attributes[2];
                    }

                    if($this->display_cols=='' or (isset($this->display_cols[$j]) and $this->display_cols[$j]=='t')){
                        $out.='<td'.(($i==0 and isset($arr_width[$key]) and $this->width!='' and $arr_width[$key]>0) ? ' width="'.$arr_width[$key].'"' : '').$cell_attribute.'>'.str_replace('#ROW#',$i+1,$this->change_tag_col($this->change_tags(((count($this->format_cols) > 0 and isset($this->format_cols[$j])) ? ($this->format_cols[$j]!='' ? $this->format_cols[$j] : $value) : $value)),$this->data[$i])).'</td>';
                    }
                    $j++;
                }
                $out.='</tr>';
            }
        }elseif($this->sql_error!='' and $this->debug){

            $arr_header=explode(',',$this->header);

            $out.='<tr id="'.$this->id.'_error"><td colspan="'.count($arr_header).'"><b>SQL ERROR:</b> '.$this->sql_error.'<br/><b>SQL QUERY:</b> '.$this->get_sql(true).'</td></tr>';

        }else{
            $arr_header=explode(',',$this->header);

            if($this->no_results!==false)
                $out.='<tr id="'.$this->id.'_no_results"'.($this->class!='' ? ' class="'.$this->class.'_no_results"' : '').'><td colspan="'.count($arr_header).'">'.$this->no_results.'</td></tr>';
        }

        return $out;
    }

    // Draw the actions component
    function draw_actions(){
        $out='';

        if(count($this->actions)>0){

            $out='<select id="'.$this->id.'_actions"'.($this->class!='' ? ' class="'.$this->class.'_actions"' : '').' name="'.$this->id.'_actions" onchange="'.$this->change_tags($this->actions_url).'">';

            for($i=0; $i<count($this->actions); $i++)
                $out.='<option value="'.$this->actions[$i][0].'">'.$this->actions[$i][1].'</option>';

            $out.='</select>';

        }

        return $out;
    }

    // Draw the pager component
    function draw_pager(){
        return $this->pager;
    }

    // Draw the necessary javascript block
    function draw_javascript_block(){
        // sort order
        $out_sort_order='var arr_sort_order= new Array();';

        for($i=0; $i<strlen($this->sort_order); $i++){
            if($i==strlen($this->sort_order)-1)
                $out_sort_order.='arr_sort_order["'.$this->sort_order[$i].'"]="'.$this->sort_order[0].'";';
            else
                $out_sort_order.='arr_sort_order["'.$this->sort_order[$i].'"]="'.$this->sort_order[$i+1].'";';
        }

        if(strpos($this->sort_order,'t')===false)
            $out_sort_order.='arr_sort_order["t"]="";';

        $out_sort_order.='arr_sort_order["first"]="'.$this->sort_order[0].'";';

        $out='<script type="text/javascript">'.$out_sort_order.'var extra_cols ='.json_encode($this->extra_cols).';';

        if($this->ajax_url!='')
            $out.='var ajax_url="'.$this->ajax_url.'";';

        if($this->items_per_page_ids!='')
            $out.='var items_per_page_ids=new Array("'.str_replace(',','","',$this->items_per_page_ids).'");';
        else
            $out.="var items_per_page_ids='';";

        if($this->pager_ids!='')
            $out.='var pager_ids=new Array("'.str_replace(',','","',$this->pager_ids).'");';
        else
            $out.="var pager_ids='';";

        if($this->extra_vars!='')
            $out.='var extra_vars=new Array("'.str_replace(',','","',$this->extra_vars).'");';
        else
            $out.="var extra_vars='';";

        if($this->search=='')
            $out.='$(document).ready(function(){ $("#'.$this->id.'_search").val(""); $("#'.$this->id.'_items_per_page_change option:eq(0)").attr("selected","selected"); });';

        $out.='</script>';

        return $out;
    }

    function set_row_attribute($value='',$row=''){
        $this->row_attributes[]=$row.'|'.$value;
    }

    function set_cell_attribute($value='',$row='',$column=''){
        $this->cell_attributes[]=$row.'|'.$column.'|'.$value;
    }

    // Display xls donwload
    function display_xls(){

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=".$this->export_filename);
		header("Content-Transfer-Encoding: binary ");

        echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0); // xlsBOF

        $row = 0;

        if($this->export_header){
            $arr_header=explode(',',$this->header);

            $col=0;
            for($i=0; $i<count($arr_header);$i++){

                if($this->display_cols=='' or $this->display_cols[$i]=='t'){

                    if(is_numeric($arr_header[$i])){
                        echo pack("sssss", 0x203, 14, $row, $col, 0x0).pack("d", $arr_header[$i]);
    				}else{
                        //$arr_header[$i] = utf8_decode($arr_header[$i]);
                        echo pack("ssssss", 0x204, 8 + strlen($arr_header[$i]), $row, $col, 0x0, strlen($arr_header[$i])).$arr_header[$i];
                    }

                    $col++;
                }

            }
            
            $row++;
        }

        for($i=0; $i<count($this->data);$i++){

            $col = 0;
            for($j=0; $j<count($this->data[$i]);$j++){

                if($this->display_cols=='' or $this->display_cols[$j]=='t'){

                    //$this->data[$i][$j] = utf8_decode($this->data[$i][$j]);

    				if(is_numeric($this->data[$i][$j]))
                        echo pack("sssss", 0x203, 14, $row, $col, 0x0).pack("d", $this->data[$i][$j]);
    				else
                        echo pack("ssssss", 0x204, 8 + strlen($this->data[$i][$j]), $row, $col, 0x0, strlen($this->data[$i][$j])).utf8_decode($this->data[$i][$j]);

    				$col++;

                }
			}

            $row++;

        }

        echo pack("ss", 0x0A, 0x00); // xlsEOF

        exit;
    }

    // Display csv donwload
    function display_csv(){

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=".$this->export_filename);
		header("Content-Transfer-Encoding: binary ");

        if($this->export_header){
            $arr_header=explode(',',$this->header);

            $col=0;
            for($i=0; $i<count($arr_header);$i++){

                if($this->display_cols=='' or $this->display_cols[$i]=='t'){

                    if($col>0)
                        echo $this->csv_delimiter;

                    echo $this->csv_enclosure.$arr_header[$i].$this->csv_enclosure;
                    $col++;
                }

            }
            echo "\n";
        }

        for($i=0; $i<count($this->data);$i++){

            if($i>0)
                echo "\n";

            $col=0;
            for($j=0; $j<count($this->data[$i]);$j++){

                if($this->display_cols=='' or $this->display_cols[$j]=='t'){

                    if($col>0)
                        echo $this->csv_delimiter;

                    $this->data[$i][$j] = utf8_decode($this->data[$i][$j]);

                    echo $this->csv_enclosure.str_replace('#ROW#',$i+1,$this->change_tag_col($this->change_tags((count($this->format_cols) > 0 ? ($this->format_cols[$j]!='' ? $this->format_cols[$j] : $this->data[$i][$j]) : $this->data[$i][$j])),$this->data[$i][$j])).$this->csv_enclosure;
                    $col++;
                }

            }
        }

        exit;
    }

    // Display a debug box
    function display_debug(){

        $out='<div id="'.$this->id.'_debug_container"'.($this->class!='' ? ' class="'.$this->class.'_debug_container"' : '').'>';
        $out.='<h1>Debug</h1>';
        $out.='<table id="'.$this->id.'_debug"'.($this->class!='' ? ' class="'.$this->class.'_debug"' : '').'>';
        $out.='<tr><th>Field</td><th>Value</td></tr>';
        $out.='<tr class="even"><td class="field">Generated SQL</td><td class="value">'.$this->get_sql().'</td></tr>';
        $out.='<tr class="odd"><td class="field">SQL error</td><td class="value">'.$this->sql_error.'</td></tr>';
        $out.='<tr class="even"><td class="field">Total items</td><td class="value">'.$this->total_items.'</td></tr>';
        $out.='<tr class="odd"><td class="field">Page</td><td class="value">'.$this->page.'</td></tr>';
        $out.='<tr class="even"><td class="field">Items Per Page</td><td class="value">'.$this->items_per_page.'</td></tr>';
        $out.='<tr class="odd"><td class="field">Sort</td><td class="value">'.$this->sort.'</td></tr>';
        $out.='<tr class="even"><td class="field">Search</td><td class="value">'.$this->search.'</td></tr>';

        $out_multiple_search = '';
        foreach($this->multiple_search as $value)
            $out_multiple_search .= ($out_multiple_search!='' ? '<br/>' : '').($value!='' ? $value : '-');
        $out.='<tr class="odd"><td class="field">Multiple Search</td><td class="value">'.$out_multiple_search.'</td></tr>';
        $out.='<tr class="even"><td class="field">Extra Vars</td><td class="value">'.$this->extra_vars.'</td></tr>';
        $out.='</table></div>';

        return $out;
    }

    // Displays the output
    function display($op='',$ajax=false){
        $arr_out=array('form_init'=>'', 'search'=>'', 'items_per_page'=>'', 'table'=>'', 'thead'=>'', 'tbody'=>'', 'actions'=>'', 'pager'=>'', 'form_final'=>'', 'javascript'=>'', 'total_items'=>'', 'debug'=>'');
        $out='';

        // Builds all the structure of the table
        $this->init_sort();

        if(($op=='' or strpos($op,'form_init')!==false) and $this->form_init)
            $arr_out['form_init']=$this->draw_form();

        $arr_out['total_items']=$this->total_items;

        if($op=='' or strpos($op,'search')!==false){
            if($ajax)
                $arr_out['search']=$this->draw_search();
            else
                $arr_out['search']='<div id="'.$this->id.'_search_container"'.($this->class!='' ? ' class="'.$this->class.'_search_container"' : '').'>'.$this->draw_search().'</div>';
        }

        if($op=='' or strpos($op,'items_per_page')!==false){
            if($ajax)
                $arr_out['items_per_page']=$this->draw_items_per_page();
            else
                $arr_out['items_per_page']='<div id="'.$this->id.'_items_per_page_container"'.($this->class!='' ? ' class="'.$this->class.'_items_per_page_container"' : '').'>'.$this->draw_items_per_page().'</div>';
        }

        if($op=='' or strpos($op,'table')!==false){
            $arr_out['table']='<table id="'.$this->id.'"'.($this->class!='' ? ' class="'.$this->class.'"' : '').($this->table_width!='' ? ' style="width:'.$this->table_width.'"' : '').'>';
            if($this->header!==false)
                $arr_out['table'].='<thead>'.$this->draw_header().'</thead>';
            $arr_out['table'].='<tbody>'.$this->draw_body().'</tbody>';
            $arr_out['table'].='</table>';
        }

        if(strpos($op,'thead')!==false){
            if($ajax)
                $arr_out['thead']=$this->draw_header();
            else
                $arr_out['thead']='<thead>'.$this->draw_header().'</thead>';
        }

        if(strpos($op,'tbody')!==false){
            if($ajax)
                $arr_out['tbody']=$this->draw_body();
            else
                $arr_out['tbody']='<tbody>'.$this->draw_body().'</tbody>';
        }

        if(($op=='' or strpos($op,'actions')!==false) and count($this->actions)>0){
            if($ajax)
                $arr_out['actions']=$this->draw_actions();
            else
                $arr_out['actions']='<div id="'.$this->id.'_actions_container"'.($this->class!='' ? ' class="'.$this->class.'_actions_container"' : '').'>'.$this->draw_actions().'</div>';
        }

        if(($op=='' or strpos($op,'pager')!==false) and $this->pager!=''){
            if($ajax)
                $arr_out['pager']=$this->draw_pager();
            else
                $arr_out['pager']='<div id="'.$this->id.'_pager_container"'.($this->class!='' ? ' class="'.$this->class.'_pager_container"' : '').'>'.$this->draw_pager().'</div>';
        }

        if(($op=='' or strpos($op,'form_final')!==false) and $this->form_init)
            $arr_out['form_final']='</form>';

        if($op=='' or strpos($op,'javascript')!==false)
            $arr_out['javascript']=$this->draw_javascript_block();

        if($this->debug>1){
            $arr_out['debug']=$this->display_debug();
        }

        if($ajax and $this->charset!='UTF-8'){
             foreach($arr_out as $key => $value)
                $arr_out[$key]=iconv($this->charset,'UTF-8',$value);
        }

        if($op=='')
            $out=$arr_out['form_init'].$arr_out['search'].$arr_out['items_per_page'].$arr_out['table'].$arr_out['actions'].$arr_out['pager'].$arr_out['form_final'].$arr_out['javascript'].$arr_out['debug'];
        else
            $out=$arr_out;

        $this->out=$out;

        return $out;
    }

}

?>
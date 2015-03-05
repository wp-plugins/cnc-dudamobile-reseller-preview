<?php

// *** You only need to configure this ***
//$db_server      = 'localhost';
//$db_username    = 'root';
//$db_password    = '';
//$db_name        = 'creativedreams';
// ***************************************

//mysql_connect($db_server, $db_username, $db_password) or die("Could not connect: " . mysql_error());
//mysql_select_db($db_name);


// you may use any pagination system that you like, including this one, because its independent from the table,
// but i strongly recomend the full version of creativePagerLite because it has lots of options, other types of paginations, much more flexible and it's extremely easy to use.
function getCreativePagerLite($id,$page,$total_items,$items_per_page,$info1=true){

    include_once('./creativePagerLite.php');

    $cp=new CreativePager();

    // Data Gathering
    $params['selected_page']    = $page;
    $params['total_items']      = $total_items;
    $params['items_per_page']   = $items_per_page=='all' ? $total_items : $items_per_page;
    $params['url']              = 'javascript: ctPager(\''.$id.'\',\'#NUM_PAGE#\');';

    // Layout Configurations
    $params['id']               = $id.'_pager';
    $params['type']             = 'centered';
    $params['nav_pages']        = 5;
    $params['info1']            = $info1;

    $cp->pager($params);
    $out_pager=$cp->display();

    return $out_pager;
}


// function to check if an array as any value - for the real example and complex example
function filled_array($arr){

    for($i=0; $i<count($arr); $i++){
        if($arr[$i]!='')
            return true;
    }

    return false;

}

// function to build the menu
function buildLayoutMenu($op){

    $out='
    <ul id="nav">
        <li><a href="index.html">Intro</a></li>
        <li><a href="documentation/01_documentation_01_instructions.html">Documentation</a></li>
        <li><a href="02_examples_01_real.php"><b>Examples</b></a></li>
        <li><a href="03_styles.php">Styles</a></li>
    </ul>
    <ul class="sub_nav">
        <li><a href="02_examples_01_real.php">'.($op==1 ? '<b>' : '').'Real'.($op==1 ? '</b>' : '').'</a></li>
        <li><a href="02_examples_02_complex.php">'.($op==2 ? '<b>' : '').'Complex'.($op==2 ? '</b>' : '').'</a></li>
        <li><a href="02_examples_03_ajax.php">'.($op==3 ? '<b>' : '').'Ajax'.($op==3 ? '</b>' : '').'</a></li>
        <li><a href="02_examples_04_search.php">'.($op==4 ? '<b>' : '').'Search'.($op==4 ? '</b>' : '').'</a></li>
        <li><a href="02_examples_05_items_per_page.php">'.($op==5 ? '<b>' : '').'ItemsPerPage'.($op==5 ? '</b>' : '').'</a></li>
        <li><a href="02_examples_06_sort.php">'.($op==6 ? '<b>' : '').'Sort'.($op==6 ? '</b>' : '').'</a></li>
        <li><a href="02_examples_07_extra_cols.php">'.($op==7 ? '<b>' : '').'ExtraCols'.($op==7 ? '</b>' : '').'</a></li>
        <li><a href="02_examples_08_data.php">'.($op==8 ? '<b>' : '').'Data'.($op==8 ? '</b>' : '').'</a></li>
        <li><a href="02_examples_09_2D_array.php">'.($op==9 ? '<b>' : '').'2DArray'.($op==9 ? '</b>' : '').'</a></li>
    </ul>
    <ul class="sub_nav">
        <li><a href="02_examples_10_separate.php">'.($op==10 ? '<b>' : '').'Separate'.($op==10 ? '</b>' : '').'</a></li>
        <li><a href="02_examples_11_custom.php">'.($op==11 ? '<b>' : '').'Custom'.($op==11 ? '</b>' : '').'</a></li>
        <li><a href="02_examples_12_display.php">'.($op==12 ? '<b>' : '').'Display'.($op==12 ? '</b>' : '').'</a></li>
        <li><a href="02_examples_13_2_pagers.php">'.($op==13 ? '<b>' : '').'2Pagers'.($op==13 ? '</b>' : '').'</a></li>
        <li><a href="02_examples_14_2_tables.php">'.($op==14 ? '<b>' : '').'2Tables'.($op==14 ? '</b>' : '').'</a></li>
        <li><a href="02_examples_15_large_db.php">'.($op==15 ? '<b>' : '').'LargeDB'.($op==15 ? '</b>' : '').'</a></li>
    </ul>
    <ul class="sub_nav">
        <li><a href="02_examples_16_2_items_per_page.php">'.($op==16 ? '<b>' : '').'2ItemsPerPage'.($op==16 ? '</b>' : '').'</a></li>
        <li><a href="02_examples_17_format_cols.php">'.($op==17 ? '<b>' : '').'FormatCols'.($op==17 ? '</b>' : '').'</a></li>
        <li><a href="02_examples_18_cell_attributes.php">'.($op==18 ? '<b>' : '').'CellAttributes'.($op==18 ? '</b>' : '').'</a></li>
        <li><a href="02_examples_19_json.php">'.($op==19 ? '<b>' : '').'Json'.($op==19 ? '</b>' : '').'</a></li>
    </ul>';

    return $out;

}

?>
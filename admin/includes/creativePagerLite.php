<?php

/*
creativePager Lite version 1.1
created by Creative Dreams 26-05-2010
you may use any pagination system that you like, including this one, because its independent from the table,
but i strongly recomend the full version of creativePager because it has lots of options, other types of paginations, much more flexible, easy to use and has lots of styled examples.

<span id="pager_info1"></span>
<ul id="pager" class="">
    <li id="pager_first"><a href="">« First</a></li>
    <li id="pager_pos_first">...</li>
    <li id="pager_prev"><a href="">«</a></li>

    <!-- BEGIN DYNAMIC BLOCK: pages -->
    <li><a href="">{NUM_PAGE}</a></li>
    <!-- END DYNAMIC BLOCK: pages -->

    <li id="pager_next"><a href="">»</a></li>
    <li id="pager_pre_last">...</li>
    <li id="pager_last"><a href="">Last »</a></li>
</ul>
<span id="pager_info2"></span>
*/

class CreativePager{

    var $id;
    var $class;
    var $selected_page;
    var $total_items;
    var $items_per_page;
    var $total_pages;
    var $nav_pages;
    var $url;
    var $first;
    var $last;
    var $out;

    function pager($params){
        global $tpl;

        // Default Values
        $this->id               = isset($params['id']) ? $params['id'] : 'pager';
        $this->class            = isset($params['class']) ? $params['class'] : '';
        $this->selected_page    = isset($params['selected_page']) ? ($params['selected_page']>0 ? $params['selected_page'] : 1) : 1;
        $this->total_items      = isset($params['total_items']) ? $params['total_items'] : '';
        $this->items_per_page   = isset($params['items_per_page']) ? ($params['items_per_page']>0 ? $params['items_per_page'] : 10) : 10;
        $this->total_pages      = isset($params['total_pages']) ? $params['total_pages'] : ceil($this->total_items/$this->items_per_page);
        $this->nav_pages        = isset($params['nav_pages']) ? (($params['nav_pages']!==true and $params['nav_pages']>0 or $params['nav_pages']===false) ? $params['nav_pages'] : 9) : 9;
        $this->url              = isset($params['url']) ? $params['url'] : '';
        $this->first            = true;
        $this->last             = true;
        $this->info1            = isset($params['info1']) ? ($params['info1']===false ? false : true) : true;
        $this->info2            = isset($params['info2']) ? ($params['info2']===false ? false : true) : true;

        // Builds the all structure of the pager (info1 + pager + info2)

        $out='';
        if($this->info1)
            $out.='<span id="'.$this->id.'_info1">'.'Total: '.$this->total_items.'</span>';
        if($this->total_pages>1){
            $out.='<ul id="'.$this->id.'"'.($this->class!='' ? ' class="'.$this->class.'"' : '').'>';
            $out.=$this->draw_type();
            $out.='</ul>';
        }
        if($this->info2)
            $out.='<span id="'.$this->id.'_info2">'.'Page: '.$this->selected_page.' of '.$this->total_pages.'</span>';

        $this->out=$out;

    }

    // Analises the url passed, if it has the tag #NUM_PAGE# it substitues for the true value of the page,
    // otherwise puts ?pag=1 or &pag=1 in the end of url
    function get_url($page){
        if(strpos($this->url,'#NUM_PAGE#')!==false){
            return str_replace('#NUM_PAGE#',$page,$this->url);
        }else{
            return $this->url.(strpos($this->url,'?')!==false ? '&' : '?').'page='.$page;
        }
    }

    // Button first
    function draw_first(){
        $out='';

        if($this->first){
            $out='<li id="'.$this->id.'_first"><a href="'.$this->get_url(1).'">&laquo; First</a></li>';
            $out.='<li id="'.$this->id.'_pos_first">...</li>';
        }

        return $out;
    }

    // Button last
    function draw_last(){
        $out='';

        if($this->last){
            $out='<li id="'.$this->id.'_pre_last">...</li>';
            $out.='<li id="'.$this->id.'_last"><a href="'.$this->get_url($this->total_pages).'">Last &raquo;</a></li>';
        }

        return $out;
    }

    // Builds the pager
    function draw_type(){
        $out='';

        if($this->nav_pages){
            if($this->selected_page>1) $out='<li id="'.$this->id.'_prev"><a href="'.$this->get_url($this->selected_page-1).'">&laquo;</a></li>';
            $out.=$this->type_centered();
            if($this->selected_page<$this->total_pages) $out.='<li id="'.$this->id.'_next"><a href="'.$this->get_url($this->selected_page+1).'">&raquo;</a></li>';
        }

        return $this->draw_first().$out.$this->draw_last();
    }


    // Centered type - the selected page allways stays in the center
    function type_centered(){
        $out='';

        if($this->selected_page<=ceil(($this->nav_pages+1)/2)){
            $min=1;
            $max=$this->nav_pages;
            $this->first=false;
        }elseif($this->selected_page>$this->total_pages-floor(($this->nav_pages+1)/2)){
            $min=$this->total_pages-$this->nav_pages+1;
            $max=$this->total_pages;
            $this->last=false;
        }else{
            $min=$this->selected_page-ceil(($this->nav_pages-1)/2);
            $max=$min+$this->nav_pages-1;
        }

        if($this->total_pages<=$this->nav_pages)
            $this->last=false;

        if($min<1) $min=1;
        if($max>$this->total_pages) $max=$this->total_pages;

        for($i=$min; $i<=$max; $i++)
            $out.='<li><a href="'.$this->get_url($i).'" '.($i==$this->selected_page ? 'class="selected"' : '').'>'.$i.'</a></li>';

        return $out;
    }

    // Display the output
    function display(){
        return $this->out;
    }

}

?>
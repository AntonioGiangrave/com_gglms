<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<div id="levels_nav">
    <ul>
        <?php
        foreach ($this->levels as $level) {
            $s = '<li><a class="level-items" id="' . $level . '" href="#">level ' . $this->levels_map[$level] . '<br />';
            for ($i = 0; $i < $level; $i++)
                $s .= '<img src="components/com_gglms/images/star.png" alt="*" style="width:24px;" />';
            $s .='</a></li>';
            echo $s;
        }
        ?>
    </ul>
</div> 

<div id="levels_contents">
    <h2>Livello <span id="level_num"></span></h2>
    <div class="loading"></div>
    <div id="contents">
    </div>

    <div id="levels_contents_pagination">
        <span class="pag_button" id="first">First</span>
        <span class="pag_button" id="prev5">&lt;&lt;</span>
        <span class="pag_button" id="prev">&lt;</span>
        <div id="pages"></div>
        <span class="pag_button" id="next">&gt;</span>
        <span class="pag_button" id="next5">&gt;&gt;</span>
        <span class="pag_button" id="last">Last</span>
    </div>
</div>
<script type="text/javascript">
    var total = 0;
    var total_pages = 0;
    var current_page = 0;
    var num_per_page = <?php echo $this->num_per_page; ?>;
    var level=0;
    var levels_map = <?php echo $this->levels_map_json; ?>;
     
    jQuery(document).ready(function() {
        current_page = 1;
        level = <?php echo $this->start_level; ?>;
        ajax_load_data(level, current_page, num_per_page);
    
        jQuery('.level-items').click(function(e) {
            e.preventDefault();
            current_page = 1;
            level = jQuery(this).attr('id');
            ajax_load_data(level, current_page, num_per_page);
        });
        
        jQuery('#levels_contents_pagination span').bind('click', navigation_click);
        
    });
</script>
<div style="clear:both"></div>

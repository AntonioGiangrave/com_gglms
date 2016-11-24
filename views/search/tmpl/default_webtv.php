<?php
defined('_JEXEC') or die('Restricted access');
?>

<div class="titolo_contenuto"><?php echo $this->contenuto['titolo']; ?></div>
<div>
    <form id="search_form" action="<?php echo JURI::root(true) . "/search"; ?>" method="post">
        <input type="hidden" name="page" id="page" value="<?php echo $this->current_page; ?>" /> 
        <label>Cerca</label>
        <input type="text" name="search" value="<?php echo join(' ', $this->search_words); ?>" />
        <br />

        <fieldset>
            <!--            <legend>Filtri</legend>-->
            <div class="content">
                <!--              <label>Level</label>    
                            <select name="search_level">
                                <option></option>
                <?php
                foreach ($this->levels as $level) {
                    if ($level == $this->filtered_level)
                        echo '<option value="' . $level . '" selected="selected">' . $this->levels_map[$level] . '</option>';
                    else
                        echo '<option value="' . $level . '">' . $this->levels_map[$level] . '</option>';
                }
                ?>            
                            </select>-->
                <br />
                <label>Area Tematica</label>    
                <select name="search_category">
                    <option></option>
                    <?php
                    foreach ($this->categories as $category) {
                        if ($category['id'] == $this->filtered_category)
                            echo '<option value="' . $category['id'] . '" selected="selected">' . $category['categoria'] . '</option>';
                        else
                            echo '<option value="' . $category['id'] . '">' . $category['categoria'] . '</option>';
                    }
                    ?>            
                </select>
            </div>
        </fieldset>

        <input type="submit" value="Cerca" />
    </form>
</div> 


<div id="search_results" style="margin-top: 2em;">
    <?php
    if (!empty($this->results)) {
        echo " <div class='titolo_contenuto'>" . $this->contenuto['titolo'] . "</div>";
    }
    ?>
    <div class="loading"></div>
    <div id="results">
        <?php
        if (!empty($this->results)) {
//            foreach ($this->results as $result) {
//                echo '<div id ="' . $result['id'] . '" class="tv_contenuto"><div class="greybar"></div>';
//                echo '<div class="tv_contenuto_background" style="background-size:200px; background-image: url(mediatv/_contenuti/' . $result['id'] . '/' . $result['id'] . '.jpg);"></div>';
//                echo '<div class="tv_contenuto_titolo"><a href="index.php?option=com_gglms&view=contenuto&Itemid=206&id=' . $result['id'] . '">' . $result['titolo'] . '</a></div>';
//                echo '<div class="tv_contenuto_livello"><a href="index.php?option=com_gglms&view=listlevels&level=' . $result['livello'] . '">level ' . $this->levels_map[$result['livello']] . '</a></div>';
//                echo '<div class="tv_contenuto_categorie">';
//                foreach ($result['categories'] as $cat) {
//                    echo '<a href="index.php?option=com_gglms&task=categorie&id=' . $cat['id'] . '">' . $cat['categoria'] . '</a>';
//                }
//                echo '</div>';
//                echo '</div>';
//            }


            echo '<div id="tv_contenuti_categoria">';
            foreach ($this->results as $v) {
                //var_dump($v);

                $_img_contenuto = "mediatv/_contenuti/" . $v['id'] . "/" . $v['id'] . ".jpg";

                if (!file_exists($_img_contenuto)) {
                    $_img_contenuto = "mediatv/_contenuti/" . "voltoignoto.png";
                }
                ?>
                <div class="tv_contenuto" id="<?php echo $v['id']; ?>">
                    <div class="greybar"></div>

                    <div class="tv_contenuto_background"  >

                        <a href="<?php
        echo JRoute::_('index.php?option=com_gglms&view=contenuto&alias=' . $v['alias'] . '');
                ?>">
                            <img width="118px" src="<?php echo $_img_contenuto; ?>">    
                        </a>
                    </div>

                    <div class="tv_contenuto_titolo">

                        <a href="<?php
                   echo JRoute::_('index.php?option=com_gglms&view=contenuto&alias=' . $v[alias] . '');
                ?>">
                               <?php echo $v['titolo']; ?>
                        </a> 



                    </div>

                    <div class="tv_contenuto_descrizione">
                        <?php echo $v['descrizione']; ?>
                    </div>





                    <div class="datapubblicazione">
                        <?php echo $v['datapubblicazione']; ?>        

                    </div>

                </div>
                <?php
            }
        }
        ?>
    </div>
</div>
<!--
    <div id="search_contents_pagination">
        <span class="pag_button" id="first">First</span>
        <span class="pag_button" id="prev5">&lt;&lt;</span>
        <span class="pag_button" id="prev">&lt;</span>
        <div id="pages"></div>
        <span class="pag_button" id="next">&gt;</span>
        <span class="pag_button" id="next5">&gt;&gt;</span>
        <span class="pag_button" id="last">Last</span>
    </div>
-->
</div>
<script type="text/javascript">
    var search_words = <?php echo $this->search_words_json; ?>;
    var current_page = <?php echo $this->current_page; ?>;
                
    //    function highlight_searched_words(searched_words, item) {
    //        var src_str = item.html();
    //        for (i=0; i< searched_words.length; i++) {
    //            var pattern = new RegExp('('+searched_words[i]+')', 'ig');   
    //            src_str = src_str.replace(pattern, "<strong>$1</strong>");
    //        }
    //        item.html(src_str);
    //    }
    <!--    
    jQuery('#search_contents_pagination span').click(function (e) {
        e.preventDefault();
        var id = jQuery(this).attr('id');
        switch (id) {
            case 'first':
                new_page = 1;
                break;
            case 'prev5':
                new_page = (current_page - 5 > 0) ?  current_page - 5 : 1;
                break;
            case 'prev':
                new_page = (current_page > 1) ?  current_page - 1 : 1;
                break;
            case 'next':
                new_page = (current_page < total_pages) ?  current_page + 1 : total_pages;
                break;
            case 'next5':
                new_page = (current_page + 5  <= total_pages) ?  current_page + 5 : total_pages;
                break;
            case 'last':
                new_page = total_pages;
                break;
            default:
                new_page = parseInt(id);                
        }
        jQuery('#page').val(new_page);
        jQuery('#search_form').submit();
    });
    -->
    // highlight_searched_words(search_words, jQuery("#results"));
                
    jQuery('pag_button').click(function(e) {
        e.preventDefault(); 
    });
                
    jQuery('legend').click(function(){
        jQuery(this).parent().find('.content').slideToggle('slow');
    });
</script>

<?php
// ~@:-]
?>
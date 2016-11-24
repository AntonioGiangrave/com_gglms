<?php
/**
 * @version		1
 * @package		webtv
 * @author 		antonio
 * @author mail	tony@bslt.it
 * @link		
 * @copyright	Copyright (C) 2011 antonio - All rights reserved.
 * @license		GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

$_backgroundVetrina = "http://www.e-taliano.tv/home/mediatv/_contenuti/" . $this->vetrina['id'] . "/" . $this->vetrina['id'] . "_evidenza.jpg";
?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<script type="text/javascript">
    
    
    jQuery(function() {
        
        jQuery( "#listaUnita" ).accordion();
        
       
        
        jQuery(".tv_categorie_lista").click(function(){
            var id = jQuery(this).attr('id');
            window.location="webtv/categorie/"+id;
        });
        
        jQuery(".tv_palinsesto_contenuto_titolo").click(function(){
            var id = jQuery(this).attr('id');
            window.location="webtv/contenuto/"+id;
        });
        
        jQuery("#tv_palinsesto_vetrina").click(function(){
            var id = jQuery(this).attr('id_contenuto');
            window.location="webtv/contenuto/"+id;
        });

        //        jQuery('#tv_categorie').jScrollPane({
        //            horizontalGutter: 30,
        //            verticalGutter: 30
        //        });
    });
    
    
</script>



<div id="main_content">
    <div id="tv_contenitore">
        <div id ="tv_colonna_palinsesto">

            <div id="tv_palinsesto_vetrina"  id_contenuto="<?php echo $this->vetrina['id']; ?>" 
                 style="background-image:url('<?php echo $_backgroundVetrina; ?>'); background-repeat: no-repeat; background-size: cover" >
                <div id="play"><a href="<?php echo JRoute::_('index.php?option=com_gglms&view=contenuto&alias=' . $this->vetrina["alias"] . ''); ?>">
                        <img src="components/com_gglms/images/play_button.png" /></a>
                </div>

            </div>
            <div id="abstract">
                <?php echo $this->vetrina['abstract']; ?>
            </div>
            <div>
                <img width="100%" height="50px" src="components/com_gglms/images/box_ombra_sotto.png">
            </div>

            <div id="tv_palisesto_contenuti">
                <?php
                foreach ($this->ContentsOfTheDay as $val) {

                    $m = floor(($val['durata'] % 3600) / 60);
                    $s = ($val['durata'] % 3600) % 60;
                    $_durata = $m . ":" . $s;


                    $_img_contenuto = "http://www.e-taliano.tv/home/mediatv/_contenuti/" . $val['id'] . "/" . $val['id'] . ".jpg";
                    $_testo_contenuto = "<b>" . $val['titolo'] . "</b><br> [" . $_durata . "]";

                    //var_dump($val);
                    // creo le stelline del livello
                    $levels_map = array(1 => 'A1', 2 => 'A2', 3 => 'B1', 4 => 'B2', 5 => 'C1', 6 => 'C2');
                    $_livello_CEF = 'Level ' . $levels_map[$val['livello']];

                    $_livello = '';
                    for ($i = 0; $i < $val['livello']; $i++)
                        $_livello.="<img class='tv_livello' src='components/com_gglms/images/stella-livello.png'>";
                    for (; $i < 6; $i++)
                        $_livello.="<img class='tv_livello' src='components/com_gglms/images/stella-livello_bn.png'>";
                    //
                    ?>
                    <div class="tv_palinsesto_contenuto">
                        <div class="tv_palinsesto_contenuti_img" style="background-image:url('<?php echo $_img_contenuto; ?>'); background-repeat: no-repeat; background-size: contain; " ></div>
                        <div class='tv_palinsesto_contenuto_titolo' id='<?php echo $val['alias']; ?>'> <?php echo $_testo_contenuto; ?> </div>


                    </div>
                    <?php
                }
                ?>
            </div>
        </div>


        <div id="listaUnita">
            <?php
            $i=1;
            foreach ($this->unita as $unita) {
                ?>
                <h3>Unità <?php echo $i; ?></h3>

                <?php
                echo "<div>";
                foreach ($unita['contenuti'] as $contenuto) {
                    echo "<p><a href='" .
                    JRoute::_('index.php?option=com_gglms&view=contenuto&alias=' . $contenuto['alias'] . '')
                    . "'>" . $contenuto['titolo'] . "</a></p>";
                }
                echo "</div>";
                $i++;
            }
            ?>
        </div>


        <div id ="tv_categorie">

            <a href="index.php/webtv/categorie/cultura"><img src="components/com_gglms/images/elementi/cultura.png" width="100%"></a>            
            <a href="index.php/webtv/categorie/conversazione"><img src="components/com_gglms/images/elementi/conversazione.png" width="100%"></a>            
            <a href="index.php/webtv/categorie/grammatica"><img src="components/com_gglms/images/elementi/grammatica.png" width="100%"></a>            
            <a href="index.php/webtv/esercizi"><img src="components/com_gglms/images/elementi/esercizi.png" width="100%"></a>            



            <div id ="tv_social">

                <a href="https://www.facebook.com/pages/E-taliano/473722056082694" target="_blank"><img src="components/com_gglms/images/elementi/facebook-01.png" width="100%"></a>            
                <a href="/home/kunena"><img src="components/com_gglms/images/elementi/forum-01.png" width="100%"></a>            
                <a href=""><img src="components/com_gglms/images/elementi/youtube-01.png" width="100%"></a>            


            </div>
        </div>

      

       
    </div>


</div>

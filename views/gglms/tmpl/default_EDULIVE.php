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
$_backgroundVetrina = "http://www.englishlive.tv/it/mediatv/_contenuti/" . $this->Vetrina['id'] . "/" . $this->Vetrina['id'] . "_evidenza.jpg";
?>
<script>
    jQuery(function() {
        
        jQuery("#rt-top-surround").slideUp("slow");
        
        var _ItemId = <?php echo _ItemId; ?>;
        jQuery(".tv_categorie_lista").click(function(){
            var id = jQuery(this).attr('id');
            window.location="index.php?option=com_gglms&task=categorie&Itemid="+ _ItemId +"&id="+id;
        });
        
        jQuery(".tv_palinsesto_contenuto_titolo").click(function(){
            var id = jQuery(this).attr('id');
            window.location="index.php?option=com_gglms&task=contenuto&Itemid="+ _ItemId +"&id="+id;
        });
        
        jQuery("#tv_palinsesto_vetrina").click(function(){
            var id = jQuery(this).attr('id_contenuto');
            window.location="index.php?option=com_gglms&task=contenuto&Itemid="+ _ItemId +"&id="+id;
        });
    });
</script>

<h1>Home page webtv</h1>



<div id="tv_contenitore">

    <div id ="tv_colonna_palinsesto">

        <div id="tv_palinsesto_vetrina"  id_contenuto="<?php echo $this->Vetrina['id']; ?>" style="background-image:url('<?php echo $_backgroundVetrina; ?>'); background-repeat: no-repeat" >
            <div id="play"></div>
            <div id="tv_titolo_vetrina"><?php echo $this->Vetrina['titolo'] ?></div>
        </div>
        <div id="tv_palisesto_contenuti">
            <?php
            foreach ($this->ContentsOfTheDay as $val) {

                $m = floor(($val['durata'] % 3600) / 60);
                $s = ($val['durata'] % 3600) % 60;
                $_durata = $m . ":" . $s;


                $_img_contenuto = "http://www.englishlive.tv/it/mediatv/_contenuti/" . $val['id'] . "/" . $val['id'] . ".jpg";
                $_testo_contenuto = "<b>" . $val['titolo'] . "</b><br> [" . $_durata . "]";

                //var_dump($val);
                // creo le stelline del livello
                $levels_map = array(1 => 'A1', 2 => 'A2', 3 => 'B1', 4 => 'B2', 5 => 'C1', 6 => 'C2');
                $_livello_CEF = 'Level '.$levels_map[$val['livello']];
                
                $_livello = '';
                for ($i = 0; $i < $val['livello']; $i++)
                    $_livello.="<img class='tv_livello' src='components/com_gglms/images/stella-livello.png'>";
                for (; $i < 6; $i++)
                    $_livello.="<img class='tv_livello' src='components/com_gglms/images/stella-livello_bn.png'>";
                //
                ?>
                <div class="tv_palinsesto_contenuto">
                    <div class="tv_palinsesto_contenuti_img" style="background-image:url('<?php echo $_img_contenuto; ?>'); background-repeat: no-repeat" ></div>
                    <div class='tv_palinsesto_contenuto_titolo' id='<?php echo $val['id']; ?>'> <?php echo $_testo_contenuto; ?> </div>
                    <div class='tv_livelloCEF_contenuto'><?php echo $_livello_CEF; ?> </div>
                    <div class='tv_livello_contenuto'><?php echo $_livello; ?> </div> 

                </div>
                <?php
            }
            ?>
        </div>

    </div>

    <div id="blocchi_centrali">
        <div class="blocco_centrale">
            <a href="http://66.71.191.82:8988/client.html" target="_blank"><img src="components/com_gglms/images/banner_livehelp.jpg" alt="Live Help" title="Live Help"/></a>
        </div>
        <div class="blocco_centrale">
            <a href="http://edu-live.tv/home/index.php?option=com_gglms&task=categorie&Itemid=206&id=20"><img src="components/com_gglms/images/banner_trinity.jpg" alt="Trinity" title="Trinity" /></a>
        </div>
         <div class="blocco_centrale">
            <a href="index.php?option=com_gglms&task=categorie&id=24"><object width="190" height="130"><param name="movie" value="components/com_gglms/images/banner_CLIL.swf"></param><param name="wmode" value="transparent"></param><embed src="components/com_gglms/images/banner_CLIL.swf" type="application/x-shockwave-flash" wmode="transparent"width="190" height="130" title="CLIL"></embed></object></a>
        </div>
        <div class="blocco_centrale">
            <a href="http://edu-live.tv/home/index.php?option=com_gglms&task=categorie&id=7"><img src="components/com_gglms/images/banner_food.jpg" alt="Food" title="Food" /></a>
        </div>
    </div>

    <div id ="tv_categorie">
        <?php
        echo "<h2>CATEGORIES</h2>";
        foreach ($this->Categories as $val) {
            //Imposto l'icona del canale
            switch ($val['canale']) {
                case 1:
                    $_img_categoria = 'components/com_gglms/images/cult.jpg';
                    break;

                case 2:
                    $_img_categoria = 'components/com_gglms/images/edu.jpg';
                    break;
                case 3:
                    $_img_categoria = 'components/com_gglms/images/fun.jpg';
                    break;
                case 4:
                    $_img_categoria = 'components/com_gglms/images/plus.jpg';
                    break;

                default:
                    $_img_categoria = 'components/com_gglms/images/star.png';
                    break;
            }
            $_link = 'index.php?option=com_gglms&task=categorie&id=' . $val['id'];
            echo '<div class="tv_categorie_lista" id="' . $val['id'] . '"style="background-image:url(\'' . $_img_categoria . '\'); background-repeat: no-repeat">'
            . $val['categoria'] .
            '</div>';
        }
        ?>
        <div class="blocco_destro">
            <a href="link"><img src="components/com_gglms/images/banenr-esercizi.png" alt="Exercises" title="Exercises" /></a>
        </div>
        <div class="blocco_destro">
            <a href="index.php?option=com_gglms&view=listlevels"><img src="components/com_gglms/images/banner_livelli.jpg" alt="Levels" title="Levels" /></a>
        </div>


    </div>

    <!--<div id="blocco_bottom_right">
        <a href="link"><img src="components/com_gglms/images/richardlewis.png" alt="Richard" /></a>
    </div>-->

</div>
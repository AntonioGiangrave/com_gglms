<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
include 'head.php';
?>
<script type="text/javascript" src="components/com_gglms/jwplayer/jwplayer.js"></script>

<div id="box_video">
    <div id="player_video">Loading the player...</div>
    <div>
        <img width="550px" height="50px" src="components/com_gglms/images/box_ombra_sotto.png">
    </div>


    <div class="abstract">

        <?php echo $content['abstract']; ?>
    </div>
    <div style="clear:both"></div>
    <div id="box_pulsanti">
        <?php
        if ($content['mp3']) {
            ?>
            <a class="img_btn" href="<?php
        echo JRoute::_('index.php?option=com_gglms&mode=audio&alias=' . $content['alias'] . '');
            ?>"><img src="components/com_gglms/images/audio.png" />
                <span class="img_btn">AUDIO</span>
            </a>
            <?php
        }
        ?>
        <a class="img_btn" href=""><img src="components/com_gglms/images/download.png" /></a>
        <span class="img_btn">SCARICA AUDIO</span>

        <?php
        if ($content['flv']) {
            ?>
            <a class="img_btn" href="<?php echo JRoute::_('index.php?option=com_gglms&mode=flash&alias=' . $content['alias'] . ''); ?>"><img src="components/com_gglms/images/video.png" />
                <span class="img_btn">Se hai problemi a vedere questo video prova la modalità flash</span>
            </a>

            <?php
        }
        ?>
    </div>

    <!-- fine articolo -->




</div>
<script type="text/javascript">
    jwplayer("player_video").setup({
        file: "<?php echo JURI::root(true) . $path . $content_id; ?>.flv",
        image: "<?php echo JURI::root(true) . $path . $content_id; ?>.jpg"
    });
</script>

<?php
include 'sidebar.php';
//include 'bottom.php';
?>

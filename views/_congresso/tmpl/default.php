<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

// qualche trick per i browser
require 'components/com_gglms/models/libs/utils/browser_detection.php';
$browser = browser_detection('browser_name');
$mobile = browser_detection('mobile_test');
switch ($browser) {
    case 'opera':
        $video_params = 'preload="none"';
        break;
    default:
        $video_params = 'preload="auto"';
}
switch ($mobile) {
    case 'android':
        $video_params .= ' autoplay';
        break;
    default:
        $video_params .= '';
}

$id_contenuto = $this->contents[$this->active_content_idx]['id'];
$path = 'mediatv/_contenuti/' . $id_contenuto . '/';

?>
<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "ur-ebfab26a-f289-1387-4415-c51328a72c49"});</script>
<script type="text/javascript">
    var vjs;
    var old_tempo;
</script>

<h2 class="title"><?php echo $this->contents[$this->active_content_idx]['titolo']; ?></h2>

<div id="article">

    <div id="box_video">

        <div class="video-js-box" style="z-index: 9000">
            <video id="player_video" width="480" height="353"  controls="controls" <?php echo $video_params; ?> poster="<?php echo $path . $id_contenuto . ".jpg"; ?>">

                <source src="<?php echo $path . $id_contenuto; ?>.mp4" type="video/mp4" />
                <source src="<?php echo $path . $id_contenuto; ?>.webm" type="video/webm" />
                <source src="<?php echo $path . $id_contenuto; ?>.ogv" type="video/ogg" />
                <img src="<?php echo $path . $id_contenuto . ".jpg"; ?>" width="480" height="353" alt="Poster Image" title="No video playback capabilities." />
            </video>
            <div><img width="550px" src="components/com_gglms/images/box_ombra_sotto.png"></div>
        </div>
    </div>
<script type="text/javascript">
    vjs = new MediaElementPlayer('#player_video', {
        enablePluginDebug: false,
        enableKeyboard: false,
        plugins: ['flash', 'silverlight'],
        type: '',
        pluginPath: 'components/com_gglms/js/',
        flashName: 'flashmediaelement.swf',
        silverlightName: 'silverlightmediaelement.xap',
        defaultVideoWidth: 480,
        defaultVideoHeight: 353,
        pluginWidth: -1,
        pluginHeight: -1,
        timerRate: 250,
        features:  ['playpause','progress','current','duration','tracks','volume','fullscreen'],
        alwaysShowControls: false,
        iPadUseNativeControls: true,
        iPhoneUseNativeControls: true,
        AndroidUseNativeControls: true,
        success: function (mediaElement, domObject) {
            //                mediaElement.play();
            old_tempo = null;
            mediaElement.addEventListener('timeupdate', function(e) {
                time = mediaElement.currentTime.toFixed(0);
                sliding(time);
            }, false);
        },
        error: function () {
            console.log('Errore gravissimo');
        }
    });
</script>


    <div id="contenuti_congresso">
        <?php

            for($i=0,$tot=count($this->contents); $i<$tot; $i++) {
                echo '<div><a href="'.JRoute::_('index.php?option=com_gglms&view=congresso&alias=' . $this->contents[$i]['alias_congresso'] . '&Itemid=45&show='.$i).'">' . $this->contents[$i]['titolo'] . '</a></div>';
            }
        ?>
    </div>

    <div style="clear:both"></div>

    <div id="box_pulsanti">
        <?php
        if ($this->contents[$this->active_content_idx]['mp3']) {
            ?>

            <a class="img_btn" href="<?php
        echo JRoute::_('index.php?option=com_gglms&mode=audio&alias=' . $this->contents[$this->active_content_idx]['alias'] . '');
            ?>"><img src="components/com_gglms/images/audio.png" />
                <span class="img_btn">AUDIO</span>
            </a>
            <?php
        }
        ?>
        <a class="img_btn" href=""><img src="components/com_gglms/images/download.png" /></a>
        <span class="img_btn">SCARICA AUDIO</span>

        <?php
        if ($this->contents[$this->active_content_idx]['flv']) {
            ?>

            <a class="img_btn" href="<?php echo JRoute::_('index.php?option=com_gglms&mode=flash&alias=' . $this->contents[$this->active_content_idx]['alias'] . ''); ?>"><img src="components/com_gglms/images/video.png" />
                <span class="img_btn">Se hai problemi a vedere questo video prova la modalità flash</span>
            </a>

            <?php
        }
        ?>
    </div>
    <div class="abstract">

        <?php $this->contents[$this->active_content_idx]['abstract']; ?>
    </div>

</div>

<?php
FB::log("TPL VideoSlide");
// no direct access
defined('_JEXEC') or die('Restricted access');

$id = JRequest::getInt('id');
$path = $this->path . "/";
FB::log($path);
?>


<div id="gglms_container">
<div id="gglmsheader" class="row">
    <div class="col-xs-1">			
        path
    </div>

    <div class="vertical  col-xs-4">					
        <h2> <?php echo $this->elemento['titolo']; ?></h2>
    </div>

    <div class="col-xs-4">					
    </div>

    <div class="vertical  col-xs-1">					
        Scarica Slide
    </div>

    <div class="vertical  hidden-xs hidden-sm   col-xs-1">
        Proporzione
        <div id="slider"></div>
    </div>
</div>

<div class="container-video">
    <div class="row">
        <div id="boxvideo" class=" col-md-9 ">
            <video  style="width:100%; height:100%;" height="100%" controls="controls" preload="auto" class="img-thumbnail">
                <source type="video/mp4" src="<?php echo $path . $id . ".mp4"; ?>" />
                <!-- WebM/VP8 for Firefox4, Opera, and Chrome -->
                <source type="video/webm" src="<?php echo $path . $id . ".webm"; ?>" />
                <!-- Ogg/Vorbis for older Firefox and Opera versions -->
                <source type="video/ogg" src="<?php echo $path . $id . ".ogv"; ?>" />

                <track kind="slides" src="<?php echo $path; ?>vtt_slide.vtt" />			
            </video>
        </div>
        <div id="boxslide" class="col-md-3">
            <div class="mejs-slides-player-slides img-thumbnail"></div>
        </div>
    </div>
</div>
</div>


<script type="text/javascript">
 jQuery(function () {
     jQuery('#rt-mainbody-surround').replaceWith(jQuery('#gglms_container'))
   });
</script>
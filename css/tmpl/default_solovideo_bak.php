<?php
FB::log("TPL SoloVideo");
// no direct access
defined('_JEXEC') or die('Restricted access');

$id = JRequest::getInt('id');
$path = $this->path . "/";
FB::log($path);
?>

<script type="text/javascript">
   jQuery(function () {
       jQuery('#rt-body-surround').replaceWith(jQuery('#gglms_container'));
   });


   jQuery(document).ready(function($) {
       var player;
       var old_tempo;
       var old_tempo_schedecaso;


       var stato = <?php echo ($this->elemento['track']['cmi.core.lesson_status'] == 'completed') ? 1 : 0; ?>;
       var features = null;

       if (stato) {
        features = ['playpause', 'current', 'progress', 'duration', 'volume', 'fullscreen', 'tracks']
    }
    else {
        features = ['playpause', 'current', 'duration', 'volume', 'fullscreen', 'tracks']
    }


    var jumper_attuale = null;
    var jumper = new Array();
    <?php
    $i = 0;
    foreach ($this->jumper as $val) {
        ?>
        jumper[<?php echo $i++; ?>] = {
            'tstart': <?php echo $val['tstart']; ?>,
            'titolo': "<?php echo $val['titolo']; ?>"
        }
        <?php
    }
    ?>

    var pathschedacaso ="";
    var schedecaso_attuale = null;
    var schedecaso = new Array();
    <?php

    FB::log($this->schedecaso, "schede caso on go");

    $i = 0;
    foreach ($this->schedecaso as $val) {
        ?>
        schedecaso[<?php echo $i++; ?>] = {
            'tstart': <?php echo $val['tstart']; ?>,
            'titolo': "<?php echo $val['titolo']; ?>",
            'dialog': "<?php echo $val['dialog']; ?>"
        }
        <?php
    }
    ?>





  // declare object for video
  player = new MediaElementPlayer('video', {
    features: features,
    slidesSelector: '.mejs-slides-player-slides',
    autoplay: true,
    enableKeyboard: false,
    success: function(mediaElement, domObject) {
        old_tempo = null;

        mediaElement.addEventListener('timeupdate', function(e) {
            time = mediaElement.currentTime.toFixed(0);
            // showQuiz(time);
            fschedecaso(time);
            sliding(time);
            
        }, false);
    },
    error: function() {
        console.log('Errore');
    }
});

  player.play();
  jQuery('.jumper.enabled').click(function() {
    var rel = jQuery(this).attr('rel');
    player.setCurrentTime(rel);
    sliding(time);
});

  jQuery('.jumper.disabled').click(function() {
    alert("E' necessario guardare tutto il video prima di poter cliccare sui jumper");
});




  function fschedecaso(tempo_schedecaso) {



    if (old_tempo_schedecaso != tempo_schedecaso && typeof (schedecaso.length) != 'undefined') {

        old_tempo_schedecaso = tempo_schedecaso;
        var currTime = parseInt(tempo_schedecaso);
        var i = 0;
        var past_jumper_selector = new Array();
        while (i < schedecaso.length && currTime >= parseInt(schedecaso[i]['tstart'])) {
            past_jumper_selector[i] = '#' + i;
            i++;
        }
                i--; // col ciclo while vado avanti di 1
                if (i < schedecaso.length && i != schedecaso_attuale) { // se cambio jumper

                    console.log("cambio schedecaso -> AJAX per set position");
                    
                    schedecaso_attuale = i;
                    // cancello eventuali jumper azzurri

                    pathschedacaso = '<?php echo $this->url ?>/schedecaso/'+ schedecaso[i]["titolo"];
                    

                    jQuery('#panel_schedacaso').load(pathschedacaso, function() {
                        player.pause();
                        alert(schedecaso[i]["dialog"] );
                    });


                    // jumper attuale è azzurro
                    // jQuery('#' + i).css('background-color', '#98ACC6');
                }
            }
        }


        function sliding(tempo) {


            if (old_tempo != tempo && typeof (jumper.length) != 'undefined') {

                old_tempo = tempo;
                var currTime = parseInt(tempo);
                var i = 0;
                var past_jumper_selector = new Array();
                while (i < jumper.length && currTime >= parseInt(jumper[i]['tstart'])) {
                    past_jumper_selector[i] = '#' + i;
                    i++;
                }
                i--; // col ciclo while vado avanti di 1
                if (i < jumper.length && i != jumper_attuale) { // se cambio jumper

                    console.log("cambio slide ---> AJAX per set position");
                    
                    jumper_attuale = i;
                    // cancello eventuali jumper azzurri
                    jQuery('.jumper').css('background-color', '#fff');

                    // jumper attuale è azzurro
                    jQuery('#' + i).css('background-color', '#98ACC6');
                }
            }
        }






        var slide = ["hide", "col-sm-1", "col-sm-2", "col-sm-3", "col-sm-4", "col-sm-5", "col-sm-6", "col-sm-7", "col-sm-8", "col-sm-9", "col-sm-10", "col-sm-11", "col-sm-12"];
        var video = ["col-sm-12", "col-sm-11", "col-sm-10", "col-sm-9", "col-sm-8", "col-sm-7", "col-sm-6", "col-sm-5", "col-sm-4", "col-sm-3", "col-sm-2", "col-sm-1", "hide"];


        $('#slider')
        .slider({
            formatter: function(value) {
                return 'Current value: ' + value;
            }
        })
        .on("slide", function(slideEvt) {
            var val= slideEvt.value;
            console.log(val);
            jQuery("#boxslide").removeClass().addClass(slide[val]);
            jQuery("#boxvideo").removeClass().addClass(video[val]);
        });


// //////////////////////

// DA MIGLIORARE !!!!! //

// //////////////////////

//INIZIO COMPARSA JUMPER 
jQuery("#jumper").click(function(){

    if(jQuery( "#panel_jumper" ).hasClass('show')){

        $( "#sidepanel" ).removeClass('show').addClass('sidepanelhide');
        $(".sidepanel").removeClass('show').addClass('hide');
        $(".container-video").removeClass("container-videosidepanelshow").addClass("container-videosidepanelhide");
    }
    else {      
        $(".sidepanel").removeClass('show').addClass('hide');
        $("#sidepanel").removeClass('sidepanelhide').addClass('show');
        $("#panel_jumper").removeClass("hide").addClass('show');
        $(".container-video").removeClass("container-videosidepanelhide").addClass("container-videosidepanelshow");
    }
});
// FINE COMPARSA JUMPER 


//INIZIO COMPARSA schedacaso 
jQuery("#schedacaso").click(function(){

    if(jQuery( "#panel_schedacaso" ).hasClass('show')){
        $( "#sidepanel" ).removeClass('show').addClass('sidepanelhide');
        $(".sidepanel").removeClass('show').addClass('hide');
        $(".container-video").removeClass("container-videosidepanelshow").addClass("container-videosidepanelhide");
    }
    else {      
        $(".sidepanel").removeClass('show').addClass('hide');
        $("#sidepanel").removeClass('sidepanelhide').addClass('show');
        $("#panel_schedacaso").removeClass("hide").addClass('show');
        $(".container-video").removeClass("container-videosidepanelhide").addClass("container-videosidepanelshow");
    }
});
// FINE COMPARSA schedacaso 


//INIZIO COMPARSA normativa 
jQuery("#normativa").click(function(){

    if(jQuery( "#panel_normativa" ).hasClass('show')){
        $( "#sidepanel" ).removeClass('show').addClass('sidepanelhide');
        $(".sidepanel").removeClass('show').addClass('hide');
        $(".container-video").removeClass("container-videosidepanelshow").addClass("container-videosidepanelhide");
    }
    else {      
        $(".sidepanel").removeClass('show').addClass('hide');
        $("#sidepanel").removeClass('sidepanelhide').addClass('show');
        $("#panel_normativa").removeClass("hide").addClass('show');
        $(".container-video").removeClass("container-videosidepanelhide").addClass("container-videosidepanelshow");
    }
});
// FINE COMPARSA normativa 
});



</script>


<div id="gglms_container">
    <div id="gglmsheader" class="">

        <div class="col-xs-2">
            <img src="images/logo_progetto_blu_500.png" width="250px">
        </div>          



        <div class="col-xs-7">          
         <ul class="breadcrumb">
             <li><a href="index.php">Home</a><span class="divider"></span></li>
             <?php
             echo gglmsHelper::getBreadcrumb(NULL, NULL, $this->elemento['id']);
             ?>
             <li class="active"><?php echo $this->elemento['titolo']; ?></li>
         </ul>
     </div>

     <div class="  col-xs-1">
        <?php if(file_exists($path . $id . ".pdf"))
        echo '<a href="'.$path . $id . '.pdf"><img src="images/download.png"></a>';
        ?> 

    </div>

    <div id="bloxslider" class="  col-xs-1">

        <!-- <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> -->
        <!--        <input type="text" class="span2" id="slider" value="" data-slider-min="1" data-slider-max="12" data-slider-step="1" data-slider-value="4"  tooltip="hide"> -->
        <!-- <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> -->
        
    </div>

    <div class="  hidden-xs hidden-sm   col-xs-1">
        <img src="images/logo_abiformazione_200.png" width="250px">
    </div>


</div>





<div id="sidepanelDELETEME"  class="  sidepanelhideDELETREME">

   <div id="pulsanti" >

       <!-- <div id="jumper" class="pulsante"><img  width="50px" src="components/com_gglms/images/navigazione.png"/></div> -->
       <div id="schedacaso" class="pulsante"><img  width="50px" src="components/com_gglms/images/scheda.png"/></div>
       <div id="normativa" class="pulsante"><img width="50px"  src="components/com_gglms/images/normativa.png"/></div>
   </div>
   <div id= "panel_jumper" class="sidepanel hide">

      <?php
      $i = 0;
      foreach ($this->jumper as $var) {
        $_titolo = $var['titolo'];
        $_tstart = $var['tstart'];

                        //Genero il minutaggio del Jumper
        $h = floor($_tstart  / 3600);
        $m = floor(($_tstart % 3600) / 60);
        $s = ($_tstart % 3600) % 60;
        $_durata = sprintf('%02d:%02d:%02d', $h , $m, $s);

                        //DIV ID del jumper che serve poi impostare il colore di background
        $_jumper_div_id = $i;

                        //Anteprima Jumper
        $_id_contenuto = JRequest::getInt('id', 0);

        $_img_contenuto = $path . "images/normal/Slide" . ($i + 1) . ".jpg";
        $_background = "background-image: url('" . $_img_contenuto . "'); background-size: 60px 50px; background-position: center;  width: 60px; height: 50px;";
        $class = ($this->elemento['track']['cmi.core.lesson_status']=='completed') ? 'enabled' : 'disabled';


        $jumper='<div class="jumper ' . $class . '" id="' . $_jumper_div_id . '" rel="' . $_tstart . '">';
                        // $jumper.='<div class="anteprima_jumper" style="' . $_background . '"></div>';
        $jumper.=$_durata . "<br>" . $_titolo;
        $jumper.='</div>';
        echo $jumper;
        $i++;
    }
    ?>

</div>
<div id= "panel_schedacaso" class="sidepanel hide">
    SCHEDA CASO
</div>

<div id= "panel_normativa"  class="sidepanel hide">
    NORMATIVA
</div>



</div>






<div class="container-video container-videosidepanelhide">

    <div class=" col-lg-2 "></div>

    <div id="boxvideo" class=" col-sm-12 col-lg-8 ">
        <video  style="width:100%; height:100%; max-height: 100% !important;" height="100%" controls="controls" preload="auto" class="img-thumbnail">
            <source type="video/mp4" src="<?php echo $path . $id . ".mp4"; ?>" />
                <!-- WebM/VP8 for Firefox4, Opera, and Chrome -->
                <source type="video/webm" src="<?php echo $path . $id . ".webm"; ?>" />
                   <!-- Ogg/Vorbis for older Firefox and Opera versions -->
                   <source type="video/ogg" src="<?php echo $path . $id . ".ogv"; ?>" />

                    <!-- <track kind="slides" src="<?php echo $path; ?>vtt_slide.vtt" /> -->


                </video>
            </div>
               <!--  <div id="boxslide" class="col-sm-6">
                    <div class="mejs-slides-player-slides img-thumbnail"></div>
                </div> -->


                <div class=" col-lg-2 "></div>


            </div>


            <div id="moduli">



            </div>

        </div>

        <style type="text/css">

            .videocontent {
                width:80%;
                max-width: 1240px;
                margin: 0 auto;
            }

            .row{
                margin-left: 0px !important;
            }

            .mejs-container {
                width: 100% !important;
                height: auto !important;
                padding-top: 57%;
            }
            .mejs-overlay, .mejs-poster {
                width: 100% !important;
                height: 100% !important;
            }


            .mejs-mediaelement video {
                position: absolute;
                top: 0; left: 0; right: 0; bottom: 0;
                width: 100% !important;
                height: 100% !important;
                max-width: 100% !important;
                max-height: 100% !important;
            }


        </style>

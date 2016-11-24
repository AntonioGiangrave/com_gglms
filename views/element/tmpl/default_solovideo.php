<?php
FB::log("TPL SoloVideo - GGenius");
// no direct access
defined('_JEXEC') or die('Restricted access');

$id = JRequest::getInt('id');
$path = $this->path . "/";
FB::log($path);
?>

<script type="text/javascript">
    jQuery(function () {
//        jQuery('#rt-mainbody-surround').replaceWith(jQuery('#gglms_container'));
    });


    jQuery(document).ready(function ($) {
        var player;
        var old_tempo;
        var old_tempo_schedecaso;

        // Variabile per il bilanciamento video / slide
        var ratio = 5;

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


        // $("#scroll_moduli").als({
        //         // visible_items: 10,
        //         scrolling_items: 1,
        //         orientation: "horizontal",
        //         circular: "no",
        //         autoscroll: "no"
        //     });


        // declare object for video
        // player = new MediaElementPlayer('video', {
        //     features: features,
        //     slidesSelector: '.mejs-slides-player-slides',
        //     autoplay: true,
        //     enableKeyboard: false,
        //     success: function (mediaElement, domObject) {
        //         old_tempo = null;

        //         mediaElement.addEventListener('timeupdate', function (e) {
        //             time = mediaElement.currentTime.toFixed(0);
        //             // showQuiz(time);
        //             // fschedecaso(time); LE SCHEDE CASO NON CI SONO
        //             sliding(time);

        //         }, false);
        //         mediaElement.addEventListener('ended', function(e) {
        //                     stato = 1;
        //                     id_elemento = <?php echo $id; ?>;

        //                     jQuery.get("/home/index.php?option=com_gglms&task=setTrack", {
        //                         varName:"cmi.core.lesson_status", 
        //                         varValue: "completed", 
        //                         id_elemento: id_elemento
        //                     });
        //         }, false);

        //     },
        //     error: function () {
        //         console.log('Errore');
        //     }
        // });

        // player.play();
        // jQuery('.jumper.enabled').click(function () {
        //     var rel = jQuery(this).attr('rel');
        //     player.setCurrentTime(rel);
        //     sliding(time);
        // });

        // jQuery('.jumper.disabled').click(function () {
        //     alert("E' necessario guardare tutto il video prima di poter cliccare sui jumper");
        // });


        function fschedecaso(tempo_schedecaso) {



            if (old_tempo_schedecaso != tempo_schedecaso && typeof (schedecaso.length) != 'undefined') {

                old_tempo_schedecaso = tempo_schedecaso;
                var currTime = parseInt(tempo_schedecaso);
                var i = 0;

                while (i < schedecaso.length && currTime >= parseInt(schedecaso[i]['tstart'])) {
                    i++;
                }

                i--; // col ciclo while vado avanti di 1

                console.log("scheda selezionata " + i + "-" + parseInt(schedecaso[i]['tstart']) + schedecaso[i]["titolo"]);

                //jQuery('#panel_schedacaso_body').html("Scheda caso non ancora caricata.");

                if (i < schedecaso.length && i != schedecaso_attuale) {
                    console.log("cambio schedecaso -> AJAX per set position");
                    schedecaso_attuale = i;
                    // cancello eventuali jumper azzurri
                    pathschedacaso = '<?php echo $this->url ?>/schedecaso/' + schedecaso[i]["titolo"];



                    jQuery('#panel_schedacaso_body').load(pathschedacaso, function () {
                        if (schedecaso[i]["tstart"] == parseInt(currTime) && schedecaso[i]["tstart"] > 1) {

                            // $('#panel_schedacaso_body').scrollTo('#link');
                            // $("#panel_schedacaso_body").animate({ 
                            //     scrollTop: $( $("#link").attr('href') ).offset().top 
                            // }, 600);




                            player.pause();


                            $('#BtSchedaCaso').popover('show');
                            $('#BtSchedaCaso').on('shown.bs.popover', function () {
                                setTimeout(function () {
                                    $('#BtSchedaCaso').popover('hide');
                                }, 4000);
                            });
                        }
                        jQuery("#panel_schedacaso_body").mCustomScrollbar("disable");
                        // jQuery("#panel_schedacaso_body").mCustomScrollbar({
                        //     theme:"light-3",
                        //     axis:"y"
                        // });
                    });


                    // jumper attuale è azzurro
                    // jQuery('#' + i).css('background-color', '#98ACC6');
                }
            }
        }


        pathnormativa = '<?php echo $this->url . "/" . $id . ".html" ?>';
        jQuery('#panel_normativa_body').load(pathnormativa);

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

                    console.log("cambio slide -> AJAX per set position");

                    jumper_attuale = i;
                    // cancello eventuali jumper azzurri
                    jQuery('.jumper').css('background-color', '#fff');

                    // jumper attuale è azzurro
                    jQuery('#' + i).css('background-color', '#E4E4E4');


                }
            }
        }


        // var slide = ["hide", "col-sm-1", "col-sm-2", "col-sm-3", "col-sm-4", "col-sm-5", "col-sm-6", "col-sm-7", "col-sm-8", "col-sm-9", "col-sm-10", "col-sm-11", "col-sm-12"];
        // var video = ["col-sm-12", "col-sm-11", "col-sm-10", "col-sm-9", "col-sm-8", "col-sm-7", "col-sm-6", "col-sm-5", "col-sm-4", "col-sm-3", "col-sm-2", "col-sm-1", "hide"];


        // $('#slider')
        // .slider({
        //     formatter: function(value) {
        //         return 'Current value: ' + value;
        //     }
        // })
        // .on("slide", function(slideEvt) {
        //     var val= slideEvt.value;
        //     console.log(val);
        //     jQuery("#boxslide").removeClass().addClass(slide[val]);
        //     jQuery("#boxvideo").removeClass().addClass(video[val]);
        // });

        // $('#layout_a').click(function () {
        //     if (ratio > 1)
        //         ratio = ratio - 1;
        //     jQuery("#boxslide").removeClass().addClass(slide[ratio]);
        //     jQuery("#boxvideo").removeClass().addClass(video[ratio]);
        // });

        // $('#layout_c').click(function () {
        //     if (ratio < 12)
        //         ratio = ratio + 1;
        //     jQuery("#boxslide").removeClass().addClass(slide[ratio]);
        //     jQuery("#boxvideo").removeClass().addClass(video[ratio]);
        // });




// //////////////////////

// DA MIGLIORARE !!!!! //

// //////////////////////

//INIZIO COMPARSA JUMPER 
        // jQuery("#jumper").click(function () {

        //     if (jQuery("#panel_jumper").hasClass('show')) {

        //         $("#sidepanel").removeClass('show').addClass('sidepanelhide');
        //         $(".sidepanel").removeClass('show').addClass('hide');
        //         $(".container-video").removeClass("container-videosidepanelshow").addClass("container-videosidepanelhide");
        //         $(".pulsante").removeClass("container-videosidepanelshow").addClass("container-videosidepanelhide");
        //         $("#moduli").removeClass("container-videosidepanelshow").addClass("container-videosidepanelhide");
        //     }
        //     else {
        //         $(".sidepanel").removeClass('show').addClass('hide');
        //         $("#sidepanel").removeClass('sidepanelhide').addClass('show');
        //         $("#panel_jumper").removeClass("hide").addClass('show');
        //         $(".container-video").removeClass("container-videosidepanelhide").addClass("container-videosidepanelshow");
        //         $(".pulsante").removeClass("container-videosidepanelhide").addClass("container-videosidepanelshow");
        //         $("#moduli").removeClass("container-videosidepanelhide").addClass("container-videosidepanelshow");
        //     }
        // });
// FINE COMPARSA JUMPER 






    });



    // (function ($) {
    //     $(window).load(function () {

    //         $("#scroll_moduli").als({
    //             // visible_items: 10,
    //             scrolling_items: 1,
    //             orientation: "horizontal",
    //             circular: "no",
    //             autoscroll: "no"
    //         });

    //     });

    // })(jQuery);

</script>


<div id="gglms_container" class="tpl_custom_background">
    <div id="gglmsheader" >



        <div id="patway">
            <div class="col-xs-12 ">          
                <ul class="breadcrumb">
                    <li><a href="index.php">Home</a><span class="divider"></span></li>
                    <?php
                    echo gglmsHelper::getBreadcrumb(NULL, NULL, $this->elemento['id']);
                    ?>
                    <li class="active_bread"><?php echo $this->elemento['titolo']; ?></li>
                </ul>
            </div>
        </div>

    </div>








    <div id= "panel_jumper" class="sidepanel hide">

        <?php
        $i = 0;
        foreach ($this->jumper as $var) {
            $_titolo = $var['titolo'];
            $_tstart = $var['tstart'];

            //Genero il minutaggio del Jumper
            $h = floor($_tstart / 3600);
            $m = floor(($_tstart % 3600) / 60);
            $s = ($_tstart % 3600) % 60;
            $_durata = sprintf('%02d:%02d:%02d', $h, $m, $s);

            //DIV ID del jumper che serve poi impostare il colore di background
            $_jumper_div_id = $i;

            //Anteprima Jumper
            $_id_contenuto = JRequest::getInt('id', 0);

            $_img_contenuto = $path . "images/normal/Slide" . ($i + 1) . ".jpg";
            $_background = "background-image: url('" . $_img_contenuto . "'); background-size: 60px 50px; background-position: center;  width: 60px; height: 50px;";
            $class = ($this->elemento['track']['cmi.core.lesson_status'] == 'completed') ? 'enabled' : 'disabled';


            $jumper = '<div class="jumper ' . $class . '" id="' . $_jumper_div_id . '" rel="' . $_tstart . '">';
            // $jumper.='<div class="anteprima_jumper" style="' . $_background . '"></div>';
            $jumper.=$_durata . "<br>" . $_titolo;
            $jumper.='</div>';
            echo $jumper;
            $i++;
        }
        ?>

    </div>







    <!-- <div id="jumper" class="pulsante"><img  width="30px" src="images/tab_navigazione.png"/></div>    -->

  

    <div class="container-video container-videosidepanelhide">

        <!-- <div class="col-sm-3 col-lg-3 "></div> -->

        <div id="boxvideo" class=" col-xs-12  ">
            <!-- provo a togliere il max height 100% -->
            <!-- <video  style="width:100%; height:100%; max-height: 100% !important;" height="100%" controls="controls" preload="auto" class="img-thumbnail"> -->
            <video  style="width:100%; height:100%; " height="100%" controls="controls" preload="auto" class="img-thumbnail">
                <source type="video/mp4" src="<?php echo $path . $id . ".mp4"; ?>" />
                <!-- WebM/VP8 for Firefox4, Opera, and Chrome -->
                <source type="video/webm" src="<?php echo $path . $id . ".webm"; ?>" />
                <!-- Ogg/Vorbis for older Firefox and Opera versions -->
                <source type="video/ogg" src="<?php echo $path . $id . ".ogv"; ?>" />

                <track kind="slides" src="<?php echo $path; ?>vtt_slide.vtt" />
                <!-- <track kind="chapters" src="<?php //echo $path;     ?>vtt_capitoli.vtt" /> -->


            </video>
        </div>
        <!-- <div id="boxslide" class="col-xs-6">
            <div class="mejs-slides-player-slides img-thumbnail"></div>
        </div> -->

        <!-- <div class="col-sm-3 col-lg-3 "></div> -->

    </div>



    <!-- <div id="moduli" class="horizontal-images mCustomScrollBox mCS-inset-2 mCSB_vertical_horizontal mCSB_inside" data-mcs-theme="dark"> -->
    <!-- <div id="moduli" class="horizontal-images mCustomScrollBox mCS-rounded-dark mCSB_vertical_horizontal mCSB_outside" data-mcs-theme="dark"> -->

    <?php
    // foreach ($this->contenutiUnita as $item) {
    // echo outputHelper::getContent_Footer($item);
    // }
    ?>


    <!-- </div> -->



    <div id="moduli">
        <div class="als-container" id="scroll_moduli">
            <span class="als-prev"><img src="images/thin_left_arrow_333.png" alt="prev" title="precedente" /></span>
            <div class="als-viewport">
                <ul class="als-wrapper">

                    <?php
                    foreach ($this->contenutiUnita as $item) {
                        echo '<li class="als-item">';
                        echo outputHelper::getContent_Footer($item);
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
            <span class="als-next"><img src="images/thin_right_arrow_333.png" alt="next" title="successivo" /></span>
        </div>

    </div>


    <!-- Button trigger modal -->


    <!-- Modal -->
    <div id= "panel_schedacaso" class="modal fade"  tabindex="-1" backdrop="false" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-schedacaso">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Scheda caso</h4>
                </div>
                <div class="modal-body" id="panel_schedacaso_body">
                    ...
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button> -->

                </div>
            </div>
        </div>
    </div>

    <div id= "panel_normativa" class="modal fade"  tabindex="-1" backdrop="false" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-normativa">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Normativa</h4>
                </div>
                <div class="modal-body" id="panel_normativa_body">
                    ...
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button> -->

                </div>
            </div>
        </div>
    </div>





</div>




<style type="text/css">
    /* .modal-content{
         position:   absolute !important;
     }
 
 
     .modal-backdrop.in {
       opacity: 0.5 !important;
   }*/

</style>







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




<script type="text/javascript">




</script>
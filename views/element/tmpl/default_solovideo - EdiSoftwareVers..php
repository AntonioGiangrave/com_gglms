<?php
FB::log("TPL SoloVideo");
// no direct access
defined('_JEXEC') or die('Restricted access');

$id = JRequest::getInt('id');
$path = $this->path . "/";
FB::log($path);
?>

<script type="text/javascript">




    $(function() {


        var player;

        var showed = false;

        //Popopolo l'array jumper. Ogni Jumper Ã¨ formato dal titolo e dai secondi ai quali si attiva.
        //var jumper_old = null;
        var jumper_attuale = null;
        var jumper = new Array();
        var path_slide = "<?php echo $path . "images/"; ?>";

        var tview = 10;



        var stato = <?php echo ($this->elemento['track']['cmi.core.lesson_status'] == 'completed') ? 1 : 0; ?>;





        // var features = null;
        if (stato) {
            features = ['playpause', 'current', 'progress', 'duration', 'volume', 'fullscreen', 'tracks']
        }
        else {
            features = ['playpause', 'current', 'duration', 'volume', 'fullscreen', 'tracks']
        }


        // var id_elemento = <?php echo $this->elemento['id']; ?>;
        // var old_tempo;
        // var vjs;

        <?php
        // $i = 0;
        // foreach ($this->jumper as $val) {
        //     ?>
        //     jumper[<?php echo $i++; ?>] = {
        //         'tstart': <?php echo $val['tstart']; ?>,
        //         'titolo': "<?php echo $val['titolo']; ?>"
        //     }
        //     <?php
        // }
        


        // var domande = new Array();
        // <?php
        // $i = 0;
        // foreach ($this->quiz as $domanda) {
        //     ?>
        //     domande[<?php echo $i++; ?>] = <?php echo $domanda['time']; ?>;
        //     <?php
        // }
        // ?>




        // declare object for video
        player = new MediaElementPlayer('video', {
            features: features,
            slidesSelector: '.mejs-slides-player-slides',
            autoplay: true,
            success: function(mediaElement, domObject) {
                old_tempo = null;

                mediaElement.addEventListener('timeupdate', function(e) {
                    time = mediaElement.currentTime.toFixed(0);
                    sliding(time);
                    showQuiz(time);

                }, false);
            },
            error: function() {
                console.log('Errore');
            }
        });




    });



jQuery( document ).ready(function(){

var permanenza = 0;
var myTimer = setInterval(function() {
    permanenza = permanenza + 10;
    $.post( "index.php?option=com_gglms&task=setPermanenza", 
        { 
            uniqid: "<?php echo $this->uniqid;?>", 
            permanenza: permanenza
        })

}, 10000);
});


jQuery( document ).ready(function(){


    $("#rating").rating({ 'min':'0', 'max':'5'});
    getRating();
    getTotRating();
    getAverage();


    $('#rating').on('rating.change', function(event, value) {
        $.post( "index.php?option=com_gglms&task=rating", 
        { 
            func: "setRating", 
            id_elemento: "<?php echo $id;?>",
            rating: value
        })
        .done(function( data ) {
            getRating();
            getAverage();
            getTotRating();
        });
    });

    
    function getRating()
    {
        $.post( "index.php?option=com_gglms&task=rating", 
        { 
            func: "getRating", 
            id_elemento: "<?php echo $id;?>"
        })
        .done(function( data ) {
            if(data>0){
                $("#rating").rating('update', data);
                $("#rating").rating('refresh', {disabled: true});
            }
        });
    }

    function getTotRating()
    {

        totVotazioni  = 0;
        width = 0;

        $.post( "index.php?option=com_gglms&task=rating", 
        { 
            func: "totRating", 
            id_elemento: "<?php echo $id;?>"
        })
        .done(function( data ) {
            totVotazioni = data;
            $('.totRating').html("Votazioni: "+ data);



        //5 STELLE 
        $.post( "index.php?option=com_gglms&task=rating", 
        { 
            func: "totRating", id_elemento: "<?php echo $id;?>", star : "5"
        })
        .done(function( data ) {
            $('#tot5').html(data);
            width = data * 100 / totVotazioni + "%" ;
            $('#bar5').css("width" , width);
        });

        //4 STELLE 
        $.post( "index.php?option=com_gglms&task=rating", 
        { 
            func: "totRating", id_elemento: "<?php echo $id;?>", star : "4"
        })
        .done(function( data ) {
            $('#tot4').html(data);
            width = data * 100 / totVotazioni + "%" ;
            $('#bar4').css("width" , width);
        });

        //3 STELLE 
        $.post( "index.php?option=com_gglms&task=rating", 
        { 
            func: "totRating", id_elemento: "<?php echo $id;?>", star : "3"
        })
        .done(function( data ) {
            $('#tot3').html(data);
            width = data * 100 / totVotazioni + "%" ;
            $('#bar3').css("width" , width);
        });

        //2 STELLE 
        $.post( "index.php?option=com_gglms&task=rating", 
        { 
            func: "totRating", id_elemento: "<?php echo $id;?>", star : "2"
        })
        .done(function( data ) {
            $('#tot2').html(data);
            width = data * 100 / totVotazioni + "%" ;
            $('#bar2').css("width" , width);
        });

        //1 STELLE 
        $.post( "index.php?option=com_gglms&task=rating", 
        { 
            func: "totRating", id_elemento: "<?php echo $id;?>", star : "1"
        })
        .done(function( data ) {
            $('#tot1').html(data);
            width = data * 100 / totVotazioni + "%" ;
            $('#bar1').css("width" , width);
        });

    });







}



function getAverage()
{
    $.post( "index.php?option=com_gglms&task=rating", 
    { 
        func: "avgRating", 
        id_elemento: "<?php echo $id;?>"
    })
    .done(function( data ) {
        $('.avgRating').html(data);
        $("#fastrating").rating('update', data);
    });
}







});



jQuery(function() {



    // var list = '<?php echo $path . "list"; ?>';
    // list='http://localhost/mediagg/contenuti/83/list';

    // $('div.comment-container').comment({
    //     title: 'Comments',
    //     url_get: list,
    //     url_input: 'http://localhost/mediagg/contenuti/83/input',
    //     url_delete: 'http://localhost/mediagg/contenuti/83/url_delete',
    //     limit: 10,
    //     auto_refresh: false,
    //     refresh: 10000,
    //     transition: 'slideToggle',
    // });



});


jQuery(function() {

    var video = ["col-md-0", "col-md-1", "col-md-2", "col-md-3", "col-md-4", "col-md-5", "col-md-6", "col-md-7", "col-md-8", "col-md-9", "col-md-10", "col-md-11", "col-md-12"];
    var slide = ["col-md-12", "col-md-11", "col-md-10", "col-md-9", "col-md-8", "col-md-7", "col-md-6", "col-md-5", "col-md-4", "col-md-3", "col-md-2", "col-md-1", "col-md-0"];


    // jQuery.ui.slider.prototype.widgetEventPrefix = 'slider';

    // jQuery("#slider").slider({
    //     value: 3,
    //     min: 0,
    //     max: 13,
    //     step: 1,
    //     slide: function() {
    //         jQuery("#boxslide").removeClass().addClass(video[jQuery("#slider").slider("value")]);
    //         jQuery("#boxvideo").removeClass().addClass(slide[jQuery("#slider").slider("value")]);
    //     }
    // });
});



//INIZIO COMPARSA JUMPER 
jQuery(function(){
    jQuery(".slider-arrow").click(function(){
        if(jQuery(".slider-arrow").hasClass('show')){
            jQuery( ".slider-arrow, .panel_jumper, .container-video" ).animate({
              left: "+=300"
          }, 700, function() {
            // Animation complete.
        });
            jQuery(".slider-arrow").html("Nascondi jumper").removeClass('show').addClass('nascondi');





        }
        else {      
            jQuery( ".slider-arrow, .panel_jumper, .container-video" ).animate({
              left: "-=300"
          }, 700, function() {
            // Animation complete.
        });
            jQuery(".slider-arrow").html("Mostra Jumper").removeClass('nascondi').addClass('show');    
        }
    });

});

// FINE COMPARSA JUMPER 


//INIZIO COMPARSA MODULI

jQuery(".slider-arrow-moduli").click(function(){
    if(jQuery(".slider-arrow-moduli").hasClass('show')){
        jQuery( ".slider-arrow-moduli, .panel_moduli, .panel_jumper, .container-video" ).animate({
          bottom: "+=150"
      }, 700, function() {
            // Animation complete.
        });
        jQuery(".slider-arrow-moduli").html("Nascondi moduli").removeClass('show').addClass('nascondi');
    }
    else {      
        jQuery( ".slider-arrow-moduli, .panel_moduli, .panel_jumper, .container-video" ).animate({
          bottom: "-=150"
      }, 700, function() {
            // Animation complete.
        });
        jQuery(".slider-arrow-moduli").html("Mostra moduli").removeClass('nascondi').addClass('show');    
    }
});


// FINE COMPARSA MODULI



</script>



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
    }

</style>


<div id="gglms_container">

    <ul class="breadcrumb">
        <li><a href="index.php">Home</a><span class="divider"></span></li>
        <?php
        echo gglmsHelper::getBreadcrumb(NULL, NULL, $this->elemento['id']);
        ?>
        <li class="active"><?php echo $this->elemento['titolo']; ?></li>
    </ul>


    <!-- INIZIO COL -->
    <div id="gglms_menu">
        <?php
        echo outputHelper::menu(); 
        ?>
    </div>
    <!-- FINE COL -->


    <div id="gglms_content">
        <div class="box_info_contenuto  col-xs-12">
            <h3> <?php
                echo  $this->elemento['titolo'];
                ?> 
            </h3>
            <h5>
            <?php
            echo  $this->elemento['descrizione'];
            ?> 
            </h5>

            <span style="float: left !important;">  
            <input id="fastrating"
            type="number" 
            class="rating" 

            step=1
            data-size="xs" 
            data-rtl="false"
            data-min="0" data-max="5"
            data-glyphicon="false" 
            data-rating-class="rating-fa"
            data-show-caption="false"
            data-show-clear="false"
            readonly="true"  
            
            >
            </span>
            <span  class="totRating" style="float: left; position:relative; margin-left: 10px;"></span>





        </div>





        <div class="  col-xs-9">




            <div id="boxvideo"class="row" >
                <video  style="width:100%; height:100%;" height="100%" controls="controls" preload="auto" class="img-thumbnail">
                    <source type="video/mp4" src="<?php echo $path . $id . ".mp4"; ?>" />
                        <!-- WebM/VP8 for Firefox4, Opera, and Chrome -->
                        <source type="video/webm" src="<?php echo $path . $id . ".webm"; ?>" />
                            <!-- Ogg/Vorbis for older Firefox and Opera versions -->
                            <source type="video/ogg" src="<?php echo $path . $id . ".ogv"; ?>" />

                                <!-- <track kind="slides" src="<?php echo $path; ?>vtt_slide.vtt" />          -->
                            </video>
                        </div>

        <!-- <div id="boxslide" class="col-md-3">
            <div class="mejs-slides-player-slides img-thumbnail"></div>
        </div> -->
        <div class="box_info_contenuto row">
            <?php
            echo  $this->elemento['abstract'];            
            ?>

        </div>


        <div class="box_info_contenuto  row">
            <h3> RATING </h3>
            <div class="col-xs-4">

                <div class="avgRating" style="font-size: 50px; color:#d9534f; font-weight: 900"></div>
                <div class="totRating"></div>



                <!-- <div id="rating" data-average="12" data-id="1"></div> -->

                <input  
                id="rating"
                type="number" 
                class="rating" 

                step=1
                data-size="sm" 
                data-rtl="false"
                data-min="0" data-max="5"
                data-glyphicon="false" 
                data-rating-class="rating-fa"
                data-show-caption="false"
                data-show-clear="false"
                >
            </div>
            <div class="col-xs-8">
                Dettagli
                <div class="row">
                    <div class="rating_dettagli_label">
                        5 stelle 
                    </div>
                    <div class="rating_dettagli_bar">
                        <div class="progress">
                          <div id="bar5" class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="">
                            <!-- <span class="sr-only">80% Complete (danger)</span> -->
                        </div>
                    </div>
                </div>
                <div id="tot5" class="rating_dettagli_tot">
                </div>
            </div>

            <div class="row">
                <div class="rating_dettagli_label">
                    4 stelle 
                </div>
                <div class="rating_dettagli_bar">
                    <div class="progress">
                      <div id="bar4" class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style=" ">
                        <!-- <span class="sr-only">80% Complete (danger)</span> -->
                    </div>
                </div>
            </div>
            <div id="tot4" class="rating_dettagli_tot">
            </div>
        </div>

        <div class="row">
            <div class="rating_dettagli_label">
                3 stelle 
            </div>
            <div class="rating_dettagli_bar">
                <div class="progress">
                  <div id="bar3" class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style=" ">
                    <!-- <span class="sr-only">80% Complete (danger)</span> -->
                </div>
            </div>
        </div>
        <div id="tot3" class="rating_dettagli_tot">
        </div>
    </div>

    <div class="row">
       <div class="rating_dettagli_label">
            2 stelle 
        </div>
        <div class="rating_dettagli_bar">
            <div class="progress">
              <div id="bar2" class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style=" ">
                <!-- <span class="sr-only">80% Complete (danger)</span> -->
            </div>
        </div>
    </div>
    <div id="tot2" class="rating_dettagli_tot">
    </div>
</div>

<div class="row">
    <div class="rating_dettagli_label">
        1 stella
    </div>
    <div class="rating_dettagli_bar">
        <div class="progress">
          <div id="bar1" class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style=" ">
            <!-- <span class="sr-only">80% Complete (danger)</span> -->
        </div>
    </div>
</div>
<div id="tot1" class="rating_dettagli_tot">
</div>
</div>

</div>
</div>




<?php 
$user = JFactory::getUser();
$userid = $user->get('id');
$groups = JAccess::getGroupsByUser($userid);
if(in_array(23, $groups) || in_array(8, $groups))
{

    ?>
    <div class="box_info_contenuto row">
        <?php
            // global $mosConfig_absolute_path;
        //RS $comments = JPATH_BASE   . '/components/com_jcomments/jcomments.php';
       //RS  require_once($comments);
        //RS echo JComments::showComments($id, 'com_gglms', $imgtitle);
        ?>

    </div>


    <?php
}
?>






</div>


<div class=" col-xs-3">
    <div class="box_info_contenuto col-xs-12 " >
        <h3> TAGS </h3>
        <?php
        foreach ($this->parametri as $item) { 
            ?>
            <form class="microformparametri" action="<?php echo JURI::root(); ?>component/gglms/search" method="POST">
                <input type="hidden" name="search" id="formGroupInputSmall" value="t:<?php echo $item['alias']; ?>" >
                <button class="btn btn-default btn-tags" type="submit"><?php echo $item['parametro']; ?></button>
            </form>

            <?php
                    // echo '<form>'
                    // echo '<button type="button" class="btn btn-default btn-sm active">'. $item['parametro'] .'</button>';
        }
        ?>


    </div>









</div>





            <!-- <div class="bs-callout bs-callout-success">
                <h3>Vota questo contenuto </h3>
                <div class="exemple">
                    <div class="exemple5" data-average="10" data-id="5"></div>
                </div>
                
            </div> -->


        </div>
    </div>

    <?php 
    if(0==1)
    {
        ?>

        <div class="panel_jumper">


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

                $_img_contenuto = $path . "/slide/Slide" . ($i + 1) . ".jpg";
                $_background = "background-image: url('" . $_img_contenuto . "'); background-size: 60px 50px; background-position: center;  width: 60px; height: 50px;";
                $class = ($this->elemento['track']['cmi.core.lesson_status']=='completed') ? 'enabled' : 'disabled';


                $jumper='<div class="jumper ' . $class . '" id="' . $_jumper_div_id . '" rel="' . $_tstart . '">';
                $jumper.='<div class="anteprima_jumper"><img src="'.$_img_contenuto.'"> </div>';
                $jumper.=$_durata . " <br> " . $_titolo;
                $jumper.='</div>';
                echo $jumper;
                $i++;
            }
            ?>






        </div>

        <a href="javascript:void(0);" class="slider-arrow show">Mostra jumper</a>

        <?php
    }
    ?>

    <div class="scroll-pane ui-widget ui-widget-header ui-corner-all panel_moduli">
        <div class="scroll-content">

            <?php 


            foreach ($this->contenutiUnita as $item) {
                $img = "../../mediagg/contenuti/".$item['id']."/".$item['id'].".jpg" ;
                ?>


                <div class="scroll-content-item ui-widget-header ">

                    <?php
                    if($id==$item['id'])
                    {
                        echo '<img class="current_arrow" title="Contenuto corrente" src="components/com_gglms/images/arrow.png"> ';
                    }


                    if(!$item['prerequisiti']){
                        echo '<img class="img-rounded" title="Contenuto non ancora visionabile" src="components/com_gglms/images/state_red.jpg"> ';
                        echo  '<span style="color:grey">'.$contenutiUnita['titolo'].'</span>';
                    }else
                    {
                        if($item['stato']=="completed")
                            {echo '<img class="img-rounded" title="Contenuto già visionato" src="components/com_gglms/images/state_green.jpg">';}
                        else
                            {echo '<img class="img-rounded" title="Contenuto da visionare" src="components/com_gglms/images/state_grey.jpg"> ';}

                    }

                    ?>
                    <?php 
                    if($item['prerequisiti']){
                        echo '  <a href="'.JRoute::_('index.php?option=com_gglms&view=contenuto&alias='.$item['idlink']."-".$item['alias']).'">
                        <img src="'. $img .'" class="imgscorll img-thumbnail" title="'.$item['titolo'].'">
                    </a>';
                    echo '  <a href="'.JRoute::_('index.php?option=com_gglms&view=contenuto&alias='.$item['idlink']."-".$item['alias']).'">'
                    .$item['titolo'].
                    '</a>';
                }
                else
                {
                    echo '  <img src="'. $img .'" class="imgscorll img-thumbnail" title="'.$item['titolo'].'">  ';
                    echo    $item['titolo'] ;

                }


                ?>
            </div>


            <?php
        }

        ?>



    </div>
    <div class="scroll-bar-wrap ui-widget-content ui-corner-bottom">
       <div class="scroll-bar"></div>
   </div>
</div>
</div>

<!-- MODULI UNITA BOTTOM -->
<?php
if($this->contenutiUnita == 'OFF')
{
    ?>
    <a href="javascript:void(0);" class="">
        <button type="button" class="btn btn-warning slider-arrow-moduli show">Mostra moduli</button>
    </a>
    <?php
}
?>
<!-- FINE MODULI UNITA BOTTOM -->
<script type="text/javascript">
  // $(function () {
    // $('#rt-transition').replaceWith($('#gglms_container'))
  // });
</script>
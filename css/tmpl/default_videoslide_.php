<?php
FB::log("TPL VideoSlide");
// no direct access
defined('_JEXEC') or die('Restricted access');

$id = JRequest::getInt('id');
$path = $this->path . "/";
FB::log($path);
?>

<script type="text/javascript">




    jQuery(document).ready(function($) {


        var player;

        var showed = false;

        //Popopolo l'array jumper. Ogni Jumper Ã¨ formato dal titolo e dai secondi ai quali si attiva.
        //var jumper_old = null;
        var jumper_attuale = null;
        var jumper = new Array();
        var path_slide = "<?php echo $path . "images/"; ?>";

        var tview = 10;



        var stato = <?php echo ($this->elemento['track']['cmi.core.lesson_status'] == 'completed') ? 1 : 0; ?>



        var features = null;
        if (stato) {
            features = ['playpause', 'current', 'progress', 'duration', 'volume', 'fullscreen', 'tracks']
        }
        else {
            features = ['playpause', 'current', 'duration', 'volume', 'fullscreen', 'tracks']
        }


        var id_elemento = <?php echo $this->elemento['id']; ?>;
        var old_tempo;
        var vjs;

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
        var domande = new Array();
<?php
$i = 0;
foreach ($this->quiz as $domanda) {
    ?>
            domande[<?php echo $i++; ?>] = <?php echo $domanda['time']; ?>;
    <?php
}
?>




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

        player.play();
        jQuery('.jumper.enabled').click(function() {
            var rel = jQuery(this).attr('rel');
            player.setCurrentTime(rel);
            sliding(time);
        });

        jQuery('.jumper.disabled').click(function() {
            alert("E' necessario guardare tutto il video prima di poter cliccare sui jumper");
        });



        jQuery('.w').click(function() {
            jQuery(this).addClass('btn-danger');
        });

        jQuery('.r').click(function() {
            jQuery(this).addClass('btn-success');
            setTimeout(function() {
                jQuery('.ongo').slideUp(500, function() {
                    jQuery('.ongo').removeClass('ongo')
                    $('.container-video').slideDown(500);
                    showed = false;
                    player.play();
                });
            }, 2000);


        });



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
                    jQuery('#' + i).css('background-color', '#98ACC6');
                }
            }
        }

        function showQuiz(time) {
            var time = time;
            var container = $('.container-video');
            $.each(domande, function(key, value) {

                if (time == value && !showed) {
                    console.log(time + value + "OK");
                    domande.splice(0, 1);
                    showed = true;
                    player.pause();
                    $("#domanda" + value).addClass("ongo");
                    $('.container-video').hide(300);




                }
            });
        }

    });


    jQuery(function() {

        var video = ["col-md-0", "col-md-1", "col-md-2", "col-md-3", "col-md-4", "col-md-5", "col-md-6", "col-md-7", "col-md-8", "col-md-9", "col-md-10", "col-md-11", "col-md-12"];
        var slide = ["col-md-12", "col-md-11", "col-md-10", "col-md-9", "col-md-8", "col-md-7", "col-md-6", "col-md-5", "col-md-4", "col-md-3", "col-md-2", "col-md-1", "col-md-0"];


        jQuery.ui.slider.prototype.widgetEventPrefix = 'slider';

        jQuery("#slider").slider({
            value: 3,
            min: 1,
            max: 12,
            step: 1,
            slide: function() {
                jQuery("#boxslide").removeClass().addClass(video[jQuery("#slider").slider("value")]);
                jQuery("#boxvideo").removeClass().addClass(slide[jQuery("#slider").slider("value")]);
            }
        });
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
jQuery(function(){
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

});

// FINE COMPARSA MODULI












//INIZIO SCROLL PANE BOTTOM 


 jQuery(function() {
//scrollpane parts
var scrollPane = jQuery( ".scroll-pane" ),
scrollContent = jQuery( ".scroll-content" );
//build slider
var scrollbar = jQuery( ".scroll-bar" ).slider({
slide: function( event, ui ) {
if ( scrollContent.width() > scrollPane.width() ) {
scrollContent.css( "margin-left", Math.round(
ui.value / 100 * ( scrollPane.width() - scrollContent.width() )
) + "px" );
} else {
scrollContent.css( "margin-left", 0 );
}
}
});
//append icon to handle
var handleHelper = scrollbar.find( ".ui-slider-handle" )
.mousedown(function() {
scrollbar.width( handleHelper.width() );
})
.mouseup(function() {
scrollbar.width( "100%" );
})
.append( "<span class='ui-icon ui-icon-grip-dotted-vertical'></span>" )
.wrap( "<div class='ui-handle-helper-parent'></div>" ).parent();
//change overflow to hidden now that slider handles the scrolling
scrollPane.css( "overflow", "hidden" );
//size scrollbar and handle proportionally to scroll distance
function sizeScrollbar() {
var remainder = scrollContent.width() - scrollPane.width();
var proportion = remainder / scrollContent.width();
var handleSize = scrollPane.width() - ( proportion * scrollPane.width() );
scrollbar.find( ".ui-slider-handle" ).css({
width: handleSize,
"margin-left": -handleSize / 2
});
handleHelper.width( "" ).width( scrollbar.width() - handleSize );
}
//reset slider value based on scroll content position
function resetValue() {
var remainder = scrollPane.width() - scrollContent.width();
var leftVal = scrollContent.css( "margin-left" ) === "auto" ? 0 :
parseInt( scrollContent.css( "margin-left" ) );
var percentage = Math.round( leftVal / remainder * 100 );
scrollbar.slider( "value", percentage );
}
//if the slider is 100% and window gets larger, reveal content
function reflowContent() {
var showing = scrollContent.width() + parseInt( scrollContent.css( "margin-left" ), 10 );
var gap = scrollPane.width() - showing;
if ( gap > 0 ) {
scrollContent.css( "margin-left", parseInt( scrollContent.css( "margin-left" ), 10 ) + gap );
}
}
//change handle position on window resize
jQuery( window ).resize(function() {
resetValue();
sizeScrollbar();
reflowContent();
});
//init scrollbar size
setTimeout( sizeScrollbar, 10 );//safari wants a timeout
});


// FINE SCROLL PANE BOTTOM







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

<div id="gglmsheader" class="row">
    <div class="col-xs-1">			
        <a class="title" href="index.php?option=com_gglms&view=unita&id=<?php echo $this->elemento['unita']['categoriapadre']; ?>">
            <img src="components/com_gglms/images/NavigateLeft.png" width="50px"  title="<?php echo $this->elemento['unita']['categoriapadre']; ?>" > </a>
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
echo '	<a href="'.JRoute::_('index.php?option=com_gglms&view=contenuto&alias='.$item['idlink']."-".$item['alias']).'">
			<img src="'. $img .'" class="imgscorll img-thumbnail" title="'.$item['titolo'].'">
		</a>';
echo '	<a href="'.JRoute::_('index.php?option=com_gglms&view=contenuto&alias='.$item['idlink']."-".$item['alias']).'">'
			.$item['titolo'].
		'</a>';
}
else
{
echo '	<img src="'. $img .'" class="imgscorll img-thumbnail" title="'.$item['titolo'].'">	';
echo 	$item['titolo']	;

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
<a href="javascript:void(0);" class="slider-arrow-moduli show">Mostra moduli</a>
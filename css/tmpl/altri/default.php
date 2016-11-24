<?php
FB::log("tpl default di elemento");
// no direct access
defined('_JEXEC') or die('Restricted access');

$id = JRequest::getInt('id');
$path = $this->path."/";
FB::log($path);
?>


<script>
    var player;

    var showed = false;

    //Popopolo l'array jumper. Ogni Jumper Ã¨ formato dal titolo e dai secondi ai quali si attiva.
    var jumper_old = null;
    //var jumper_attuale;
    var jumper = new Array();
    var path_slide = "<?php echo $path . "images/"; ?>";
    // var path_pdf = "<?php echo $path . $this->elemento['path_pdf']; ?>";
    var tview = <?php echo (isset($this->elemento['track']['tview']) ? $this->elemento['track']['tview'] : 0); ?>;
    //var durata= 0;
    var stato = <?php echo isset($this->elemento['track']['stato']) ? $this->elemento['track']['stato'] : 0; ?>;
    var id_elemento = <?php echo $this->elemento['id']; ?>;
    var old_tempo;
    var vjs;
    var prova = tview;
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


</script>








<div id="percorso_elemento">
    Torna a <a class="title" href="index.php?option=com_gglms&view=unita&alias=<?php echo $this->elemento['unita']['alias']; ?>">
        <?php  echo $this->elemento['unita']['categoria']; ?></a>
    <span class="title"><h2> <?php echo $this->elemento['titolo']; ?></h2></span>
</div>

<div class="container-video">
    <div class="plugin-example-container">
        <div class="plugin-example">
            <div class="mejs-slides-player">
                <div class="mejs-slides-player-video">
                    <video width="60%" height="360" style="width:100%;height:100%" controls="controls" preload="auto">
                        <source type="video/mp4" src="<?php echo $path . $id. ".mp4"; ?>" />
                        <!-- WebM/VP8 for Firefox4, Opera, and Chrome -->
                        <source type="video/webm" src="<?php echo $path . $id. ".webm"; ?>" />
                        <!-- Ogg/Vorbis for older Firefox and Opera versions -->
                        <source type="video/ogg" src="<?php echo $path . $id. ".ogv"; ?>" />

                        <track kind="slides" src="<?php echo $path; ?>vtt_slide.vtt" />			
                        <!--<track kind="chapters" src="<?php echo $path; ?>vtt_capitoli.vtt" />-->            
                    </video>

                </div>

                <div class="mejs-slides-player-slides"></div>
                <div class="box_jumper">
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
                        //$class = ($this->elemento['track']['stato']) ? 'enabled' : 'disabled';
                        $class = 'enabled';

                        $jumper='<div class="jumper ' . $class . '" id="' . $_jumper_div_id . '" rel="' . $_tstart . '">';
                        // $jumper.='<div class="anteprima_jumper" style="' . $_background . '"></div>';
                        $jumper.=$_durata . " - " . $_titolo;
                        $jumper.='</div>';
                        echo $jumper;
                        $i++;
                    }
                    ?>

                </div>

                <div class="pulsanti">
                    <?php if ($this->elemento['path_pdf']) {
                        ?>
                        <a href="<?php echo $path . $this->elemento['path_pdf']; ?>"><h3><img src="images/pdf.png" width="50px" />  Scarica le slide del contenuto</h3></a>
                        <?php
                    }
                    ?>
                </div>


            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>

<?php
foreach ($this->quiz as $domanda) {
    ?>
    <div class="lightbox" id="domanda<?php echo $domanda['time']; ?>">

        <h3 class="text"><i><?php echo $domanda['domanda']; ?></i></h3>
        <h4>Scegli tra le seguenti l'opzione corretta: </h4>
        <button class="btn <?php echo $domanda['risposte'][0]['c']; ?>"><?php echo $domanda['risposte'][0]['r']; ?> </button>
        <button class="btn <?php echo $domanda['risposte'][1]['c']; ?>"><?php echo $domanda['risposte'][1]['r']; ?> </button>
        <?php if ($domanda['risposte'][2]) {
            ?>    
            <button class="btn <?php echo $domanda['risposte'][2]['c']; ?>"><?php echo $domanda['risposte'][2]['r']; ?> </button>
            <?php
        }
        ?>
    </div>
    <?php
}
?>


<script>
    jQuery(document).ready(function($) {
        // declare object for video
        player = new MediaElementPlayer('video', {
            features: ['playpause', 'current', 'progress', 'duration', 'volume', 'fullscreen', 'tracks'],
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
        jQuery('.jumper').click(function() {
            var rel = jQuery(this).attr('rel');
            player.setCurrentTime(rel);
            sliding(time);
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
                if (i < jumper.length && i != jumper_old) { // se cambio jumper
                    jQuery('#' + jumper_old).css('background-color', '#fff');
                    jumper_old = i;

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


</script>





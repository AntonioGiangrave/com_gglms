<?php
FB::log("TPL SCORM");
// no direct access
defined('_JEXEC') or die('Restricted access');


$path = $this->path . "/";
FB::log($path);

// echo $this->initializeCache;
?>

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


<p style="text-align:center; margin: 50px;">
    <button id="start"><h3>AVVIA TEST</h3> </button>
<!-- <img  src="images/avviatest.jpg"> -->
</p>








<script type="text/javascript">
    jQuery('#start').click(function () {


        var w = 825;
        var h = 830;
        var left = (screen.width / 2) - (w / 2);
        var top = (screen.height / 2) - (h / 2);
        var stile = "top=" + top + ", left=" + left + ", width=" + w + ", height=" + h + ", status=no, menubar=no, toolbar=no scrollbars=no";

        var SCOInstanceID = '<?php echo $this->elemento['id']; ?>';

        var pathscorm = '<?php echo $this->pathscorm; ?>';

        var id_utente = '<?php echo $this->id_utente; ?>';

        var url = '../../../../vsscorm/rte.php?SCOInstanceID=' + SCOInstanceID + '&pathscorm=' + pathscorm + '&id_utente=' + id_utente;

        window.open(url, "", stile);
    });




</script>
<?php
FB::log("TPL SoloVideo");
// no direct access
defined('_JEXEC') or die('Restricted access');

$id = JRequest::getInt('id');
$path = $this->path . "/";
FB::log($path);
?>


<ul class="breadcrumb">
    <li><a href="index.php">Home</a><span class="divider"></span></li>
    <?php
    echo gglmsHelper::getBreadcrumb(NULL, NULL, $this->elemento['id']);
    ?>
    <li class="active"><?php echo $this->elemento['titolo']; ?></li>
</ul>


<p style="text-align:center; margin: 50px;">
    <button id="start"> 
        <img  src="images/accedi.png">
    </button>

</p>



<!--<iframe src="http://lms.speakclub.it/html/ggenius?<?php //echo $this->stringadea; ?>" name="course" noresize="" width="1200" height="800" maxwidth="auto" style="framewrap: 0 !important;"></iframe>-->



<script type="text/javascript">
    var stringa = '<?php echo $this->stringadea; ?>';
    var url = 'http://lms.speakclub.it/html/ggenius?' + stringa;
    console.log(url);

    jQuery('#start').click(function () {
        var w = 1200;
        var h = 830;
        var left = (screen.width / 2) - (w / 2);
        var top = (screen.height / 2) - (h / 2);
        var stile = "top=" + top + ", left=" + left + ", width=" + w + ", height=" + h + ", status=no, menubar=no, toolbar=no scrollbars=no";
        window.open(url, "", stile);
    });
</script>
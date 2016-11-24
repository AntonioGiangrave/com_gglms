<?php
FB::log("TPL SCORM");
// no direct access
defined('_JEXEC') or die('Restricted access');

$id= $this->elemento['id'];
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

<div id="attestato">
<a href="index.php?option=com_gglms&task=attestato&content=<?php echo $id; ?>"><img src="components/com_gglms/css/image/icona_pdf.png"></a>

	<h2> Congratulazioni! </h2>
	Ora puoi scaricare l'attestato del corso cliccando sull'icona qui a fianco.<br>
	<!--Il tuo attestato resterà a disposizione e scaricabile anche nella sezione "I miei attestati".-->


</div>




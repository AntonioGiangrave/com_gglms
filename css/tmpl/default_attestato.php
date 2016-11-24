<?php
FB::log("TPL SCORM");
// no direct access
defined('_JEXEC') or die('Restricted access');

$id= $this->elemento['id'];
// echo $this->initializeCache;

?>
<div id="percorso_elemento">
	Torna a <a class="title" href="index.php?option=com_gglms&view=unita&alias=<?php echo $this->elemento['unita']['alias']; ?>">
	<?php  echo $this->elemento['unita']['categoria']; ?></a>
	<span class="title"><h2> <?php echo $this->elemento['titolo']; ?></h2></span>
</div>

<div id="attestato">
<a href="index.php?option=com_gglms&task=attestato&content=<?php echo $id; ?>"><img src="components/com_gglms/css/image/icona_pdf.png"></a>

	<h2> Congratulazioni! </h2>
	Ora puoi scaricare l'attestato del corso cliccando sull'icona qui a fianco.<br>
	Il tuo attestato resterà a disposizione e scaricabile anche nella sezione "I miei attestati".


</div>




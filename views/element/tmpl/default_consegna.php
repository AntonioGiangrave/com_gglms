<?php
FB::log("TPL SCORM");
// no direct access
defined('_JEXEC') or die('Restricted access');


$path = $this->path."/";
$id= $this->elemento['id'];
$alias= $this->elemento['alias'];
// echo $this->initializeCache;

?>
<div id="percorso_elemento">
	Torna a <a class="title" href="index.php?option=com_gglms&view=unita&alias=<?php echo $this->elemento['unita']['alias']; ?>">
	<?php  echo $this->elemento['unita']['categoria']; ?></a>
	<span class="title"><h2> <?php echo $this->elemento['titolo']; ?></h2></span>
</div>

<div id="attestato">
<a href="../mediagg/contenuti/<?php echo $id;?>/<?php echo $alias; ?>.pdf"><img src="components/com_gglms/css/image/icona_pdf.png"></a>

	<h2> Nel PDF qui allegato troverai le consegne per questa unità </h2>


</div>




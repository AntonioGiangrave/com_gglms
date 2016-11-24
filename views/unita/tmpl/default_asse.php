<?php
// no direct access
FB::log("-> tmpl Asse");

defined('_JEXEC') or die('Restricted access');
?>


<div id= "img_asse">
	<?php
	if(file_exists('../mediagg/images/unit/'.$this->unita["id"].'.jpg'))
		echo '<img class="img-responsive" src="../mediagg/images/unit/'.$this->unita["id"].'.jpg">';
	else
		echo '<img class="img-responsive" src="components/com_gglms/images/sample.jpg">';
	?>
</div>

<span class="titolobox" style='background-color:<?php echo $this->unita["colore"]; ?>;'> Unita di apprendimento </span>



<?php 
foreach ($this->unita['unitaFiglio'] as $unitaFiglio) {
	?>
	<div class="listaUnita">
		<div class="listimage">
			<?php
			echo '<a href="'.JRoute::_('index.php?option=com_gglms&view=unita&alias='.$unitaFiglio['alias']).'">
			<img src="components/com_gglms/images/'.$unitaFiglio["imgassociata"].'"></a>';
			?>
		</div>
		<div class="listtext">
			<?php
			echo '<a href="'.JRoute::_('index.php?option=com_gglms&view=unita&alias='.$unitaFiglio['alias']).'">'.$unitaFiglio['categoria'].'</a>';
			?>
		</div>
	</div>
	<?php
}
?>


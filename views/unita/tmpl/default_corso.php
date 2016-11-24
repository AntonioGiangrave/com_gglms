'<?php
// no direct access
FB::log("-> tmpl Corso");

defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">

	$(function () {
		$('[data-toggle="popover"]').popover()
	})

</script>

<div class="container-fluid">
	
	<div class="row">

		<div class="col-md-3">
			<div id= "img_asse">
				<?php 
				if(file_exists('../mediagg/images/unit/'.$this->unita["id"].'.jpg'))
					echo '<img class="img-responsive" src="../mediagg/images/unit/'.$this->unita["id"].'.jpg">';
				else
					echo '<img class="img-responsive" src="components/com_gglms/images/sample.jpg">';
				?>
			</div>
		</div>



		<div id="titolo_corso" class="col-xs-12 col-md-9">
			<h1><?php echo $this->unita["categoria"];	?></h1>
			<i><?php echo $this->unita["descrizione"];	?></i>
		</div>

	</div>
</div>


<?php 
foreach ($this->unita['unitaFiglio'] as $unitaFiglio) {
	?>
	<div class="listaUnita">
		<div class="row headerunita">
			<div class="col-xs-2">
			<?php 
				echo '<a href="'.JRoute::_('index.php?option=com_gglms&view=unita&alias='.$unitaFiglio['alias']).'">';
				if(file_exists('../mediagg/images/unit/'.$this->unitaFiglio["id"].'.jpg'))
					echo '<img class="img-responsive" src="../mediagg/images/unit/'.$this->unitaFiglio["id"].'.jpg">';
				else
					echo '<img class="img-responsive" src="components/com_gglms/images/sample.jpg">';
				echo '</a>';
				?>
		</div>

		<div class="col-xs-8">
			<?php 
			echo '<a href="'.JRoute::_('index.php?option=com_gglms&view=unita&alias='.$unitaFiglio['alias']).'">';
			echo "<h3>" . $unitaFiglio['categoria']. "</h3>" ;
			echo '</a>';
			echo "<i>" . $unitaFiglio['descrizione']."</i>";

			?>
		</div>
	</div>
	<div class="elementi">
		<?php
		foreach ($unitaFiglio['contenutiUnita'] as $contenutiUnita) {
			?>

			<span class='item'>
				<?php
				if(!$contenutiUnita['prerequisiti']){
					echo '<img class="img-rounded" title="Contenuto non ancora visionabile" src="components/com_gglms/images/state_red.jpg"> ';
					echo "<span rel=".$contenutiUnita['id']." class='opener glyphicon glyphicon-zoom-in' style='cursor:pointer;'></span> ";
					echo  '<span style="color:grey">'.$contenutiUnita['titolo'].'</span>';
				}else
				{
					if($contenutiUnita['stato']=="completed")
						{echo '<img class="img-rounded" title="Contenuto già visionato" src="components/com_gglms/images/state_green.jpg">';}
					else
						{echo '<img class="img-rounded" title="Contenuto da visionare" src="components/com_gglms/images/state_grey.jpg"> ';}


					echo '<a data-toggle="popover" 
					data-trigger = "hover"
					title="Abstract"
					data-content="'.$contenutiUnita['abstract'].'"
					data-placement="right"
					class="_popover"
					href="'.JRoute::_('index.php?option=com_gglms&view=contenuto&alias='.$contenutiUnita['idlink']."-".$contenutiUnita['alias']
						).'">'.$contenutiUnita['titolo'].'</a>';
				}
				echo "</span>"	;
				?>
				<div id="<?php echo $contenutiUnita['id']; ?>" class="dialog" title="Abstract - <?php echo  $contenutiUnita['titolo']; ?>">
					<p><?php echo $contenutiUnita['abstract']; ?></p>
				</div>

				<?php

			}
			?>
		</div>
	</div>
	<?php
}
?>
'
<?php
// no direct access
FB::log("-> tmpl Unita");

defined('_JEXEC') or die('Restricted access');
?>


<div id= "img_asse">
	<?php
	echo '<a href="'.JRoute::_('index.php?option=com_gglms&view=unita&alias='.$this->unita["unitaPadre"][0]["alias"]).'">
						<img class="imgasse" src="components/com_gglms/images/'.$this->unita["unitaPadre"][0]["imgassociata"].'"></a>';
	?>
</div>



<div id='boxunita'>
	<span class="titolobox" style='background-color:<?php echo $this->unita["unitaPadre"][0]["colore"]; ?>;'>Scheda tematica <?php echo $this->unita['categoria']; ?></span>

	<div class="mezzoboxunitacontenuti">
		<span style="color: <?php echo $this->unita["unitaPadre"][0]["colore"]; ?>; ">
				<?php 

				foreach ($this->unita['contenutiUnita'] as $contenutiUnita) {
					echo "<br>"	;
					if(!$contenutiUnita['prerequisiti']){

						echo '<img src="components/com_gglms/css/image/redcross.png">';
						echo  '<span style="color:grey">'.$contenutiUnita['titolo'].'</span>';


					}else
					{
						if($contenutiUnita['stato']=="completed")
						{
						echo '<img src="components/com_gglms/css/image/greenflag.png">';

						}
						else
						{
						echo '<img src="components/com_gglms/css/image/greyflag.png">';

						}
						echo '<a style="color:'. $this->unita["unitaPadre"][0]["colore"].';" href="'.JRoute::_('index.php?option=com_gglms&view=contenuto&alias='.$contenutiUnita['idlink']."-".$contenutiUnita['alias']
						).'">'.$contenutiUnita['titolo'].'</a>';


					}




					
				}

				?>

		</span>
	</div>
<!--	<div class="mezzoboxunitaimmagine">

		<?php
		echo '<img class="imgunita" src="components/com_gglms/images/'.$this->unita["imgassociata"].'">';
		?>

	</div> -->


	
	

</div>
<?php
defined('_JEXEC') or die('Restricted access');

$contenuti = array() ;
$unita =  array() ;

if(empty($this->results))
	echo "<h1> La ricerca non ha prodotto risultati </h1>";
{
	foreach ($this->results as $item) {
		if($item["tipologia"]== 2)
			array_push($contenuti, $item);
		else
			array_push($unita, $item);
	}
}

FB::log($this->results, "results");
FB::log($this->contenuti, "contenuti");
FB::log($this->unita, "unita");

?>




<?php
if($unita)
{
	?>

	<h1> Raccolte </h1>
	<div class="row">
		<?php
		foreach ($unita as $item) {
			?>
			<div class="box .col-md-2">
				<?php echo '<a href="component/gglms/contenuto/' . $item['idlink'] . "-" . $item['alias'] . '" >'; ?> 
				<div class="boximg">
					<?php echo '<img class="img-responsive" src="../mediagg/images/unit/'.$item["id"].'.jpg">'; ?> 
				</div>
				<div class="boxtitle">
					<?php echo $item['titolo']; ?>
				</div>

				<div class="boxinfo">
					<table width="100%">
						<tr> 			
							<td> Raccolta </td>
						</tr>
						<tr>	
							<td></td>
						</tr>
					</table>
				</div>
			</a>
		</div>
		<?php } ?>
	</div>
	<?php 
}

if($contenuti)
{
	?>
	<h1> Video </h1>
	<div class="row">
		<?php
		foreach ($contenuti as $item) {
			?>
			<div class="box .col-md-2">
				<?php echo '<a href="component/gglms/contenuto/' . $item['idlink'] . "-" . $item['alias'] . '" >'; ?> 
				<div class="boximg">
					<?php echo '<img class="img-responsive" src="../mediagg/contenuti/'.$item["id"].'/'.$item["id"].'.jpg">'; ?>
				</div>
				<div class="boxtitle">
					<?php echo $item['titolo']; ?>
				</div>

				<div class="boxinfo">
					<table width="100%">
						<tr> 			
							<td width="33%">Durata</td>
							<td width="33%">Gradimento</td>
							<td width="33%">Visite</td>
						</tr>
						<tr>	
							<td><span class="glyphicon glyphicon-time"> 5 min</span></td>
							<td></td>
							<td></td>
						</tr>
					</table>
				</div>
			</a>
		</div>
		<?php } ?>
	</div>
	<?php } ?>
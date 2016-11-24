<?php
defined('_JEXEC') or die('Restricted access');


//Preparo l'array di risultati
$results=array();
foreach ($this->content_type as $type) {
	$results[$type->id]=array();
}

//Popolo l'array di risultati
foreach ($this->results as $item) {
	array_push($results[$item['tipologia']], $item);
}


if(empty($this->results))
	echo "<h1> La ricerca non ha prodotto risultati </h1>";


FB::log($results, "results");

echo "<h1>Hai cercato: <i>".$_REQUEST["search"]."</i></h1>";
?>




<div id="tabs">

	<ul>
		<?php 
		foreach ($this->content_type	 as $type) {
			if(!empty($results[$type->id]))
				echo "<li><a href='#tabs-".$type->id."'>".$type->tipologia ." (<b>".sizeof($results[$type->id])."</b>)</a></li>";
		}
		?>
	</ul>



	<?php 
	foreach ($this->content_type as $type) {
		if(!empty($results[$type->id]))
		{
			echo '<div id="tabs-'.$type->id.'"> ';  //Apro la tab
			$curpage=1;
			$totpage=(int)(sizeof($results[$type->id]) / 3)+1;
			$curcontent =1;
			FB::log($totpage, "Numero di pagine per ". $type->tipologia);
			FB::log($curpage, "Pagina corrente ");
			FB::log($curcontent, "Contenuto corrente ");

			echo "<div id='".$type->id."page-".$curpage."' class='contentpage' >";


			foreach ($results[$type->id] as $item) {
				if($curcontent > 3 ){
					$curpage++;
					$curcontent=1;
					echo "</div>";
					echo "<div id='".$type->id."page-".$curpage."' class='contentpage' >";
				}


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



			<?php
			$curcontent++;
		}

		echo '</div>'; // Chiudo la Pagina
		echo '<div id="content-'.$type->id.'">Dynamic Content goes here</div>';
		echo '<div id="page-selection-'.$type->id.'">Pagination goes here</div>';

		echo '</div>'; // Chiudo la Tab
	}
}
?>

</div>  <!-- CHIUSURA TABS -->

<script type="text/javascript">
// init bootpag
jQuery(document).ready(function($) {


		alert("ok");


	$('#page-selection-2').bootpag({
		total: 10
	}).on("page", function(event,    num){
 		$("#content").html("Insert content"); 
 	});

	jQuery('#page-selection-2').html("pagina");

});

</script>
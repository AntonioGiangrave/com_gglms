<?php
defined('_JEXEC') or die('Restricted access');
require_once JPATH_COMPONENT.'/helpers/output.php';
require_once JPATH_COMPONENT.'/helpers/gglms.php';




FB::log($results, "results");



?>

 
<script type="text/javascript">
$(function () {
	$('#rt-transition').replaceWith($('#gglms_container'))
});

</script>




<?php
    //echo outputHelper::setGestioneNotificheJS()
?>

<div id="gglms_container" class="container">

	<ul class="breadcrumb">
		<li><a href="index.php">Home</a><span class="divider"></span></li>
		<li class="active">Search<span class="divider"></span></li>
                	
	</ul>


       
	<!-- INIZIO COLONNA -->
	<div id="gglms_menu" >

			<?php
                            if(!$this->isTestSearch)
                                echo outputHelper::menu(); 
                            else {
                                 
                                echo outputHelper::resultsListAttributes($this->resultsCountAttributes4filters,$this->attributeSearchParam,$this->search,$this->uri);
                                    }
			?>
            
        </div> 
                     
	<!-- FINE COLONNA -->
        
        
	<div id="gglms_content">

		<?php

		echo "<h1>Hai cercato: <i>".$_REQUEST["search"]."</i></h1>";
		if(empty($this->results))
			echo "<h3> La ricerca non ha prodotto risultati </h3>";
                
                    echo outputHelper::getTabbedView4TextSearch($this->results4AttributesFilters); 
		?>

	</div>
        
            
             
</div>





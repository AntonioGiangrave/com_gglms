<?php
// no direct access
FB::log("-> tmpl Box");

defined('_JEXEC') or die('Restricted access');
?>

<script type="text/javascript">

    jQuery(document).ready(function ($) {

        $('#area_salute').click(function () {
            $('.salute').click();
        });

        $('#area_benessere').click(function () {
            $('.benessere').click();
        });

        $('#area_informatica').click(function () {
            $('.informatica').click();
        });

        $('#area_lingue').click(function () {
            $('.lingue').click();
        });

        $('#area_lavoro').click(function () {
            $('.lavoro').click();
        });

        $('#area_tempolibero').click(function () {
            $('.tempolibero').click();
        });

        $('#rt-mainbody-wrapper').addClass('no-space');
        $('#rt-mainbody-wrapper').addClass('background-aree');

    });
</script>
<style type="text/css">

    .sprocket-mosaic-header{
        display: none !important;
    }

    #area_informatica, 
    #area_lingue, 
    #area_salute,
    #area_benessere,
    #area_lavoro,
    #area_tempolibero
    {
        cursor: pointer;
    }

</style>



<div class="container">
    <div class="container-fluid">
        <div class="row-fluid">
            <div id="area_informatica" class="span2"><img src="images/informatica_2.jpg"></div>
            <div id="area_lingue" class="span2"><img src="images/lingue_2.jpg"></div>
            <div id="area_salute" class="span2"><img src="images/salute_2.jpg"></div>
            <div id="area_benessere" class="span2"><img src="images/benessere_2.jpg"></div>
            <div id="area_lavoro" class="span2"><img src="images/lavoro_2.jpg"></div>
            <div id="area_tempolibero" class="span2"><img src="images/tempolibero_2.jpg"></div>
        </div>
    </div>
</div>
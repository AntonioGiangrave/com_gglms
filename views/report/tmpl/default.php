<?php
/**
 * @version		1
 * @package		gglms
 * @author 		antonio
 * @author mail         antonio@ggallery.it
 * @link
 * @copyright	Copyright (C) 2011 antonio - All rights reserved.
 * @license		GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#riepilogo').hide();
        $('#daverificare').hide();

        $(".panel-title-link-codiciverifica").click(function () {
            giorno = (this.id);
            $('#collapse' + giorno).html("caricamento dati...");
            $.post("index.php?option=com_gglms&task=getCodiciSicurezzaGiorno",
                    {giorno: giorno})
                    .success(function (data) {
                        $('#collapse' + giorno).html(data);
                    });
        });
        $(".panel-title-link-detail").click(function () {
            giorno = (this.id);
            $('#collapse' + giorno).html("caricamento dati...");
            $.post("index.php?option=com_gglms&task=getReportCoupon",
                    {giorno: giorno})
                    .success(function (data) {
                        $('#collapse' + giorno).html(data);
                    });
        });

        $("#showdettaglio").click(function () {
            $('#dettaglio').show();
            $('#riepilogo').hide();
            $('#daverifcare').hide();
        });

        $("#showriepilogo").click(function () {
            $('#dettaglio').hide();
            $('#riepilogo').show();
            $('#daverifcare').hide();
        });

        $("#showdaverificare").click(function () {
            $('#dettaglio').hide();
            $('#riepilogo').hide();
            $('#daverificare').show();
        });

        $("#tocheck").bind('input propertychange', function () {
            var codici = ($("#tocheck").val());

            $.post("index.php?option=com_gglms&task=setBadCode",
                    {codici: codici})
                    .success(function (data) {
                        $('#tocheck_report').append(data);
                        $("#tocheck").val('');
                    });
        });
    });
</script>



<div class="row">
    <div class="col-md-2"><a href="#"><button id="showriepilogo" class="btn btn-large  btn-warning"      type="button">Riepilogo</button></a></div>
    <div class="col-md-2"><a href="#"><button id="showdettaglio" class="btn btn-large  btn-info"      type="button">Dettaglio giornaliero</button> </a></div>
    <div class="col-md-2"><a href="#"><button id="showdaverificare" class="btn btn-large  btn-info"      type="button">Codici da verificare</button> </a></div>

    <div class="col-md-5 col-md-offset-1">
        <div  class="pullright">
            <textarea id="tocheck" class="textarea span5" cols="60"  rows="2" placeholder="codici da verificare">
            </textarea>
            <p id="tocheck_report"></p>
        </div>
    </div>
</div>

<hr>






<!--#####################-->
<!--TABELLA RIEPILOGATIVA-->
<!--#####################-->
<div id="riepilogo">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Corso</th>
                <th>Tot coupon bruciati</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($this->riepilogo as $corso) {
                $out = "";
                $out .= "<tr>";
                $out .= "<td>" . $corso['titolo'] . "</td>";
                $out .= "<td>" . $corso['tot'] . "</td>";
                $out .= "</tr>";
                echo $out;
            }
            ?>
        </tbody>
    </table>
</div>


<!--#####################-->
<!--DETTAGLIO GIORNI-->
<!--#####################-->
<div class="panel-group" id="dettaglio">
    <?php
    foreach ($this->giorni as $giorni) {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="panel-title-link-detail" 
                       data-toggle="collapse" 
                       data-parent="#accordion" 
                       href="#collapse<?php echo $giorni['giorno']; ?>" 
                       id="<?php echo $giorni['giorno']; ?>">
                        <button type="button" class="btn btn-default btn-mini">Dettaglio periodo</button>
                    </a>

                    <a class="panel-title-link-codiciverifica" 
                       data-toggle="collapse" 
                       data-parent="#accordion" 
                       href="#collapse<?php echo $giorni['giorno']; ?>" 
                       id="<?php echo $giorni['giorno']; ?>">
                        <button type="button" class="btn btn-primary btn-mini ">solo codici verifica</button>
                    </a>
                    <?php echo $giorni['giorno'] . "  (" . $giorni['tot'] . ")  "; ?> 
                </h4>
            </div>
            <div id="collapse<?php echo $giorni['giorno']; ?>" class="panel-collapse collapse">
                <div class="panel-body"></div>
            </div>
        </div>
        <?php
    }
    ?>
</div> 

<!--#####################-->
<!--Elenco DaVerificare-->
<!--#####################-->


<div id="daverificare" >



    <table id="contents" class="table ">
        <thead>
            <tr>
                <th>Coupon</th>
                <th>Codiceverifica</th>
                <th>Data utilizzo</th>
                <th>Corso</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($this->daverificare as $coupon) {

                if ($coupon['daverificare']==1)
                    $out .= "<tr class='danger'>";
                elseif ($coupon['daverificare']==2)
                    $out .= "<tr class='success'>";
                else
                    $out .= "<tr>";

                $out .= "<td>" . $coupon['coupon'] . "</td>";
                $out .= "<td>" . $coupon['codiceverifica'] . "</td>";
                $out .= "<td>" . $coupon['data_utilizzo'] . "</td>";
                $out .= "<td>" . $coupon['id_iscrizione'] . "</td>";

                if ($coupon['daverificare']==1)
                    $out .= "<td>" . $coupon['email'] . "</td>";
                else
                    $out .= "<td></td>";
                $out .= "</tr>";
            }
            echo $out;
            ?>
        </tbody>
    </table>


</div>
<?php
/**
 * @version		1
 * @package		webtv
 * @author 		antonio
 * @author mail         antonio@ggallery.it
 * @link
 * @copyright	Copyright (C) 2011 antonio - All rights reserved.
 * @license		GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<script>
    jQuery(function () {
        jQuery.ajaxSetup({cache: false});


        //Verifica Codice Sicurezza
        function convalida() {
            var pattern = /^[a-zA-Z0-9]{10}$/;
            var codicesicurezza = jQuery("#inputVerifica").val();
            if (codicesicurezza.search(pattern) == -1) {
                console.log("negativo");
                return false;
            } else {
                console.log("convalida è OK");
                return true;
            }
        }


        function britshConnector(data) {

            if (!window.btoa) {
                window.btoa = function(str) {
                    return Base64.encode(str);
                }
            }
            var now = new Date();
            var dd = now.getDate();
            var mm = now.getMonth() + 1;
            var yyyy = now.getFullYear();
            if(dd<10){dd='0'+dd;} if(mm<10){mm='0'+mm;}
            var today = yyyy.toString() + mm.toString() + dd.toString();
            now = yyyy+'-'+mm+'-'+dd;
            var rand = Math.floor(Math.random() * 100) + 1;
//                var seller = values['seller'].split('_');
//                var id_iscrizione = seller[1] + '-' + today + '-' + rand;


            var xml_gen_request = '<richiesta>' +
                '<id>'+data.mieidati.id+'</id>' +
                '<nome>'+data.mieidati.firstname+'</nome>' +
                '<cognome>'+data.mieidati.lastname+'</cognome>' +
                '<email>'+data.mieidati.email+'</email>' +
                '<coupon>'+data.coupon.coupon+'</coupon>' +
                '<tipofatturazione>'+data.mieidati.cb_tipofatturazione+'</tipofatturazione>' +
                '<ragionesociale>'+data.mieidati.cb_ragionesociale+'</ragionesociale>' +
                '<indirizzofatturazione>'+data.mieidati.cb_indirizzofatturazione+'</indirizzofatturazione>' +
                '<cittafatturazione>'+data.mieidati.cb_cittafatturazione+'</cittafatturazione>' +
                '<capfatturazione>'+data.mieidati.cb_capfatturazione+'</capfatturazione>' +
                '<partitaivacf>'+data.mieidati.partitaivacf+'</partitaivacf>' +

                '</richiesta>';


                var xml_gen_request_str = btoa(xml_gen_request);



//                var xml_ena_request = '<richiesta><id>'+seller[0]+'</id><id_iscrizione>'+ id_iscrizione +'</id_iscrizione><token>' + jQuery.md5(seller[1] + now) + '</token></richiesta>';
//                var xml_ena_request = '<richiesta><id>'+seller[0]+'</id><id_iscrizione>'+ id_iscrizione +'</id_iscrizione><token>' + now + '</token></richiesta>';
//                var xml_ena_request_str = btoa(xml_ena_request);
//                console.log(btoa(xml_ena_request_str));

            console.log(xml_gen_request_str);

            jQuery.get("http://bsinternational.eu/getstudente.php", {data: xml_gen_request_str},
                function (data) {
                    console.log(data);
                });


//            $.ajax({
//                url:"bsinternational.eu/getstudente.php",
//                data: {data: xml_gen_request_str}
//            }).complete(function(data) {
//
//                    console.log(data);
//
//                return false;
//
//            });
        }

        jQuery("button").click(function (e) {
            e.preventDefault();

            if (!convalida())
            {
                jQuery(".inputVerificagroup").addClass('error');
                jQuery(".help-inline-sicurezza").html("Codice non valido!");
            } else
            {
                jQuery("#button_conferma_codice").hide();
                jQuery(".inputVerificagroup").removeClass('error').addClass('success');
                jQuery(".help-inline-sicurezza").html("Codice valido");
                jQuery(".help-inline-coupon").html("Verifica in corso...");

                jQuery.get("index.php?option=com_gglms", {
                                                            task:'checkGroupon',
                                                            coupon: jQuery("#inputCoupon").val(), 
                                                            codiceverifica: jQuery("#inputVerifica").val()
                    },
                    
                    function (data) {
                        if (data.valido) {
                            jQuery(".inputCoupongroup").removeClass('error').addClass('success');
                            jQuery("#inputCoupon").prop('disabled', true);
                            jQuery("#report").html(data.mieicorsi);

                            //britshConnector(data);

                        } else
                        {
                            jQuery("#button_conferma_codice").show();
                            jQuery(".inputCoupongroup").addClass('error');

                        }
                        jQuery(".help-inline-coupon").html(data.report);
                    },
                    'json');
            }
        });
    });

</script>


<div id="box_coupon_container">

    <div id="box_coupon">

        <p>Per poter accedere alla piattaforma dovrai inserire nello spazio sottostante il Codice di sicurezza&quot; e il &quot;Codice Groupon&quot; ricevuti al momento dell'acquisto del corso tramite Groupon. </p>

        <p>Questa operazione andrà eseguita solo durante ial primo accesso; per le visite successive sarà sufficiente inserire username e password cliccando sul pulsante &quot;Accedi&quot;.</p>

        <h3>Inserisci qui i tuoi codici: </h3>


        <!-- <input class="field" id="box_coupon_field" type="text" name="nome" /> -->
        <!-- <button id="button_conferma_codice">Conferma codice</button> -->
        <div class="row-fluid">
            <div class="span12">


                <form class="form-horizontal">

                    <div class="control-group inputVerificagroup ">
                        <label class="control-label" for="inputVerifica">Codice di sicurezza</label>
                        <div class="controls">
                            <input class="input-xlarge" type="text" id="inputVerifica" placeholder="codice di sicurezza">
                            <span class="help-inline help-inline-sicurezza"></span>
                        </div>
                    </div>

                    <div class="control-group  inputCoupongroup ">
                        <label class="control-label" for="inputCoupon">Codice Groupon</label>
                        <div class="controls">
                            <input class="input-xlarge" type="text" id="inputCoupon" placeholder="codice groupon">
                            <span class="help-inline help-inline-coupon"> </span>

                        </div>
                    </div>

                    <div class="control-group">
                        <div class="controls">


                            <button id="button_conferma_codice" type="submit" class="btn">Conferma</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div id="report">
        </div>
    </div>

</div>










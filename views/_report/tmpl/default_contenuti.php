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
    jQuery(document).ready(function(){
 
        
        ///// PROVA TABELLA NUOVA /// 
        
        /*
         * Insert a 'details' column to the table
         */
        var nCloneTh = document.createElement( 'th' );
        var nCloneTd = document.createElement( 'td' );
        nCloneTd.innerHTML = '<img class="more" src="components/com_gglms/css/imgtable/details_open.png">';
        nCloneTd.className = "center";

        jQuery('#contents thead tr').each( function () {
            this.insertBefore( nCloneTh, this.childNodes[0] );
        } );
     
        jQuery('#contents tbody tr').each( function () {
            this.insertBefore(  nCloneTd.cloneNode( true ), this.childNodes[0] );
        } );
        
        /*
         * Initialse DataTables, with no sorting on the 'details' column
         */
        var oTable = jQuery('#contents').dataTable(
        {
            "aoColumnDefs": [
                { "bSortable": false, "aTargets": [ 0 ] }
            ],
            "aaSorting": [[3, 'desc']],
            "oLanguage": {
                "sLengthMenu": "Mostra _MENU_ righe per volta",
                "sZeroRecords": "Nessun risultato",
                "sInfo": "Da _START_ a _END_ di _TOTAL_ righe",
                "sInfoEmpty": "Da 0 a 0 di 0 righe",
                "sInfoFiltered": "(filtrati da _MAX_ record)",
                "sSearch": "Cerca:",
                "oPaginate": {
                    "sFirst":    "Inizio",
                    "sPrevious": "Precedente",
                    "sNext":     "Successivo",
                    "sLast":     "Fine"
                }
            }
        }
    );
        /* Add event listener for opening and closing details
         * Note that the indicator for showing which row is open is not controlled by DataTables,
         * rather it is done here
         */
        jQuery('#contents tbody td img.more').live('click', function () {
            var nTr = jQuery(this).parents('tr')[0];
            if ( oTable.fnIsOpen(nTr) )
            {
                this.src = "components/com_gglms/css/imgtable/details_open.png";
                oTable.fnClose( nTr );
            }
            else
            {
                jQuery('#contents tbody tr').each( function () {
                    var aData = oTable.fnGetData( nTr );
                    this.src = "components/com_gglms/css/imgtable/details_open.png"
                    oTable.fnClose(  oTable.fnGetData( nTr ) );
                } );
                
                
                
                this.src = "components/com_gglms/css/imgtable/details_close.png";
                var aData = oTable.fnGetData( nTr );
                
                oTable.fnOpen( nTr, "Caricamento dati in corso...", 'details' );
                
                jQuery.post("index.php?option=com_gglms&task=get_track", { content: aData[1]  },
                function(data) {
                    sOut= data;
                    oTable.fnOpen( nTr, data, 'details' );
                    
                    jQuery('#track').dataTable( {
                        "bProcessing": true,
                        "bJQueryUI": true,
                        "sPaginationType": "full_numbers"
                    });    
                });
            }
        } );
        
        
        /////////////////////////////
        
        
        
        jQuery('.lente').click(function(){
            
            jQuery( "#attivita" ).dialog( "open" );
        })
        
        jQuery('#attivita').dialog({
            height: 140,
            modal: true,
            autoOpen : false
                
        });
 
 
 
    });
 

 
</script>



<div>
    <table id="contents">
        <thead>
            <tr>
                <th>Titolo</th>
                <th>Descrizione</th>
                <th>Numero accessi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($this->ContentStat as $content) {
                echo "<tr>";
                echo "<td>" . $content['titolo'] . "</td>";
                echo "<td>" . $content['descrizione'] . "</td>";
                echo "<td>" . $content['totali'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    
    
</div>

<div id="attivita">attivita</div>

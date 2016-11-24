<?php /* Smarty version Smarty-3.1.11, created on 2016-05-27 10:55:29
         compiled from "/var/www/vhosts/trainingforyou.it/httpdocs/mediagg/contenuti/886/886.tpl" */ ?>
<?php /*%%SmartyHeaderCode:89940916457480b81464265-29400171%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '200c50e17c1b645909d02fa5f7398848e7bccb7e' => 
    array (
      0 => '/var/www/vhosts/trainingforyou.it/httpdocs/mediagg/contenuti/886/886.tpl',
      1 => 1464339300,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '89940916457480b81464265-29400171',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_57480b815a9f94_75057268',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57480b815a9f94_75057268')) {function content_57480b815a9f94_75057268($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include 'components/com_gglms/models/libs/smarty/smarty/plugins/modifier.capitalize.php';
?><style type="text/css">
.attestato {
	background-color: #fff;
	font-family: "Times New Roman", Times, serif;
	
}
body {
	background-color: #D4D0C9;
	font-family: Verdana, Geneva, sans-serif;
		color: #195182;

}
#container #attestato div h1 {
	text-align: center;
	
}
#container #attestato div p {
	text-align: center;
	
}
.testo {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 16px;
	
}
</style>
<style>
    #container {
        text-align:center;
		border-style: solid;
		border-color: #195182;
		border-width: thin;
				
    }
    #attestato {
        margin: 0 auto;
        text-align:center;
		
    }
    h1 {
        color: black;
        font-family: Times New Roman;
        font-size: 20pt;
        text-align:center;
		 color: #195182;
    }
    p {
        color: #000000;
        font-family: Times New Roman;
        font-size: 13pt;
        text-align:center;
    }
#sfondo logo {
	background-color: #FFF;
}
</style>

<!-- ******************************************************** -->



<div id="container">
    <div id="attestato">
        <div style="sfondo logo">
            <p><img src="<?php echo $_smarty_tpl->tpl_vars['data']->value['content_path'];?>
/logo_Prima.png" width="300px" align="center" /></p>
           
        </div>

        <div>
            <h1>ATTESTATO DI PARTECIPAZIONE</h1>
            <p> Si attesta che<br/>
                <strong><?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['data']->value['lastname']);?>
 <?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['data']->value['firstname']);?>
</strong><br /> 
                nato/a a <?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['data']->value['cb_luogonascita']);?>
 <?php if (!empty($_smarty_tpl->tpl_vars['data']->value['provinciadinascita'])){?>(<?php echo $_smarty_tpl->tpl_vars['data']->value['provinciadinascita'];?>
)<?php }?> il <?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['data']->value['cb_datanascita']);?>
</p>
            <p>ha frequentato interamente e con profitto il corso di formazione</p>
            <p style="font-size: 14pt"><strong>ADDETTO/RESPONSABILE ALL'AUTOCONTROLLO: <br/>SISTEMA HACCP PER ALIMENTARISTI<br/><span style="font-size: 10pt">(UNIT&Agrave; FORMATIVA A)</span></p>
            <p>ai sensi del Reg. Ce 852/04 e Dgr 793 del 29/06/2012<br/>
            della durata di 8 ore<br/>
            in modalit&agrave; E-Learning presso Piattaforma TRAININGFORYOU</p>

            <p style="font-size: 10pt">Il corso è organizzato e certificato da<br/>
            PRIMA Training &amp; Consulting srl<br/>
            ENTE DI FORMAZIONE ACCREDITATO <br/>
            DALLA REGIONE LIGURIA (D.M. 166/01)<br/>
            Viale Brigata Bisagno 2/27 - 16129 Genova<br/>
            info@webprima.it - www.webprima.it</p>
            
            <p><span align="right" style="font-size: 12pt;">
                Per Prima Training &amp; Consulting srl<br/>
                Dott.ssa Priscilla Dusi<br/> 
                <img width="150" src="<?php echo $_smarty_tpl->tpl_vars['data']->value['content_path'];?>
/firma_Dusi.png" align="right" />
            </p>

                            
            <p><span align="left" style="font-size: 12pt;">
                Genova, li <?php echo $_smarty_tpl->tpl_vars['data']->value['datali'];?>
 <br/>
                N. Protocollo: HACCP-<?php echo $_smarty_tpl->tpl_vars['data']->value['user_id'];?>
</span>
            </p>
            
            <p>PROGRAMMA DIDATTICO:<br/>
                <ul>
                    <li>Rischi e pericoli alimentari, fisici, microbiologici</li>
                    <li>Metodi di autocontrollo e principi del sistema HACCP</li>
                    <li>Legislazione alimentare: obblighi e responsabilit&agrave; dell'industria alimentare</li>
                    <li>Conservazione alimenti</li>
                    <li>Approvvigionamento materie prime e tracciabilit&agrave;</li>
                    <li>Pulizia e sanificazione dei locali e delle attrezzature</li>
                    <li>Igiene personale</li>
                    <li>I manuali di buona prassi igieniche</li>
                    <li>Ambiti, tipologia e significato del controllo ufficiale</li>
                </ul>
            </p>
             </div>
        </div>
    </div>
</div>   
<!-- ******************************************************** -->

<!-- ABILITARE LA SEGUENTE RIGA PER VISUALIZZARE LE VARIABILI -->
<!--<?php echo var_dump($_smarty_tpl->tpl_vars['data']->value);?>
 --><?php }} ?>
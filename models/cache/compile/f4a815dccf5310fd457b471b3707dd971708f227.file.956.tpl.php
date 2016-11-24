<?php /* Smarty version Smarty-3.1.11, created on 2016-07-13 16:19:17
         compiled from "/var/www/vhosts/trainingforyou.it/httpdocs/mediagg/contenuti/956/956.tpl" */ ?>
<?php /*%%SmartyHeaderCode:116455293357864de53621f1-08970548%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f4a815dccf5310fd457b471b3707dd971708f227' => 
    array (
      0 => '/var/www/vhosts/trainingforyou.it/httpdocs/mediagg/contenuti/956/956.tpl',
      1 => 1463729085,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '116455293357864de53621f1-08970548',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_57864de54bc711_28381041',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57864de54bc711_28381041')) {function content_57864de54bc711_28381041($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include 'components/com_gglms/models/libs/smarty/smarty/plugins/modifier.capitalize.php';
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
            <p style="font-size: 14pt"><strong>FORMAZIONE GENERALE<br />
              PER I LAVORATORI
          </p>
            <p>ai sensi del D. Lgs 81/2008 – Accordo Stato-Regioni 21/12/2011<br/>
            della durata di 4 ore<br/>
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
                N. Protocollo </span>
            </p>
            
            <p>PROGRAMMA DIDATTICO:<br/>
                <ul>
                    <li>Presentazione concetti generali di prevenzione e sicurezza sul lavoro</li>
                    <li>Concetti di rischio, danno, prevenzione, protezione, organizzazione della prevenzione aziendale</li>
                    <li>Diritti, doveri e sanzioni per i vari soggetti aziendali</li>
                    <li>Organi di vigilanza, controllo e assistenza</li>
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
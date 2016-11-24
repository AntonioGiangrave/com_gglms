<?php

/**
 * NOTA per la versione flash occorre impostare correttamente la variabile $live_site
 * 
 * @version		1
 * @package		gg_lms
 * @author 		antonio
 * @author mail         antonio@ggallery.it
 * @link		
 * @copyright           Copyright (C) 2011 antonio - All rights reserved.
 * @license		GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
// require_once JPATH_COMPONENT.'/helpers/gglms.php';
require_once JPATH_COMPONENT . '/helpers/output.php';
require_once JPATH_COMPONENT . '/helpers/rating.php';

class gglmsViewElement extends JViewLegacy {

    function display($tpl = null) {

        $document = & JFactory::getDocument();

        JHtml::_('jquery.framework');
        //ATTENZIONE, QUESTO MEDIAELEMENT E' STATO MODIFICATO ALLA RIGA 4596 PER PROBLEMA LINGUA
        $document->addScript('components/com_gglms/js/mediaelement-and-player.js');
        $document->addScript('components/com_gglms/js/jquery.als-1.7.min.js');

        
        // $document->addScript($host . 'administrator/components/com_gglms/jupload/js/jquery.fileupload.js');//RS
        // $document->addScript($host . 'administrator/components/com_gglms/jupload/js/procedure.js');  //RS

        $document->addStyleSheet('components/com_gglms/css/mediaelementplayer.css');
        $document->addStyleSheet('components/com_gglms/css/elemento.css');
        $document->addStyleSheet('components/com_gglms/css/general.css');

        //jComment
        // $document->addStyleSheet('components/com_gglms/js/jComment/css/jquery.comment.css');	
        // $document->addScript('components/com_gglms/js/jComment/js/jquery.comment');
        // Latest compiled and minified CSS
        $document->addStyleSheet("https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css");
        // Latest compiled and minified JavaScript
        $document->addScript('https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js');

        // $document->addScript('components/com_gglms/js/jRating/jRating.jquery.js');
        // $document->addStyleSheet('components/com_gglms/js/jRating/jRating.jquery.css');

        $model = & $this->getModel();

        $elemento = $model->getElemento();
        FB::info($elemento, "Elemento");

        // $parametri =  $model->getParametri();
        //               $allegati =  $model->getAllegati();

        $modelunita = & $this->getModel('Unita');
        if (empty($modelunita)) {
            JLoader::import('joomla.application.component.model');
            JLoader::import('gglms', JPATH_BASE . DS . 'components' . DS . 'com_gglms' . DS . 'models');
            $modelunita = & JModelLegacy::getInstance('unita', 'gglmsModel');
        }
         $where = "where id = ".$elemento['unita']['id'];
         $contenutiUnita = gglmsHelper::getContenuti($elemento['unita']['id']);
         FB::log($contenutiUnita, "ContenutiUnita");



        $user = JFactory::getUser();
        $id_utente = $user->get('id');
        $email = $user->get('email');


        $this->assignRef("id_utente", $id_utente);
        $this->assignRef("email", $email);





        // $files = $model->getFiles();
        // $this->assignRef('files', $files);


        $tmpls = $model->getTemplates();
        $tpl = $tmpls[$elemento['tipologia']]['tipologia'];

        FB::log($tmpls, "TemplateDisponibili");
        FB::log($tpl, "TemplateScelto");

        $path = "../mediagg/contenuti/" . $elemento['path'] . "/" . $elemento['id'];
        $basefilepath = "../mediagg/files/"; //RS


        switch ($tpl) {
            case 'videoslide': {
                    $jumper = $model->getJumperXML($path);

                    FB::log($jumper, "jumper");

                    if ($jumper) {

                        $model->createVTT_slide($elemento['id'], $path, $jumper);
                        $model->createVTT_capitoli($elemento['id'], $path, $jumper);
                        $this->assignRef('jumper', $jumper);
                    }
                }
                break;

            case 'solovideo':

                break;

            case 'testuale':
                break;


            case 'scorm': {

                    if ($elemento['path'])
                        $pathscorm = "../mediagg/contenuti/" . $elemento['id'] . "/" . $elemento['path'];
                    else
                        $pathscorm = "../mediagg/contenuti/" . $elemento['id'] . "/index_lms_html5.html";
                    $this->assignRef('pathscorm', $pathscorm);
                }
                break;

            case 'attestato':

                break;

            case 'upload':

                $document->addScript('components/com_gglms/js/jquery.fileupload.js');
                $document->addScript('components/com_gglms/js/jquery.ui.widget.js');
                $document->addStyleSheet('http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css');

                break;

            case 'british':
            
                $sku= $model->getSku();
                $this->assignRef('sku', $sku);

                break;           

            case 'deagostini':
                
                // lms.speakclub.it/html/ggenius?usr=user1_psw1_e01_2015-12-31
                // dove user1 è lo username
                // psw1 è la password
                // e01 è l'ID del corso (vi darò tutti gli id)
                // 2015-12-31 è la scadenza.



                $username = $user->get('username');
                

                $password = md5($username);
                $username = str_replace('_', '-', $username);   
                


                $elementodea = $elemento['meta_tag'];
                $scadenza = '2016-12-31';


                $stringadea=  'usr='.$username.'_'.$password.'_'.$elementodea.'_'.$scadenza;
                FB::log($stringadea,'stringadea');
                $this->assignRef('stringadea', $stringadea);

                break;           



            default:
                # code...
                break;
        }

        // $quiz= $model->getQuizXML($path);


        $uniqid = $id_utente . time();
        gglmsHelper::setLog($id_utente, $elemento['id'], $this->_elemento['tipologia'], $uniqid);


        $this->assignRef('elemento', $elemento);
        $this->assignRef('parametri', $parametri);
        $this->assignRef('allegati', $allegati);  //RS
        $this->assignRef('contenutiUnita', $contenutiUnita);
        $this->assignRef('quiz', $quiz);
        $this->assignRef('path', $path);
        $this->assignRef('basefilepath', $basefilepath); //RS

        $this->assignRef('uniqid', $uniqid);




        parent::display($tpl);
    }

}

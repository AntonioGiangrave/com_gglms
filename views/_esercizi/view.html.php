<?php

/**
 * @version		1
 * @package		webtv
 * @author 		antonio
 * @author mail	tony@bslt.it
 * @link		
 * @copyright	Copyright (C) 2011 antonio - All rights reserved.
 * @license		GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class webtvViewesercizi extends JView {

    function display($tpl = null) {

//          $document = JFactory::getDocument();
//        $document->addStyleSheet('components/com_gglms/css/tv_esercizi.css');

        $eserciziModel = & $this->getModel('esercizi');

        $esercizi = $eserciziModel->getEsercizi();



        $this->assignRef('esercizi', $esercizi);


        parent::display($tpl);
    }

}

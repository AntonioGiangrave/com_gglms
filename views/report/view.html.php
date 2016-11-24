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

class gglmsViewReport extends JViewLegacy {

    private $_dbg;
    private $_japp;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_dbg = JRequest::getBool('dbg', 0);
        $this->_japp = & JFactory::getApplication();
        
        $document = JFactory::getDocument();
        $document->addStyleSheet("https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css");
        $document->addScript('https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js');
        
    }

    public function display($tpl = null) {

        $document = & JFactory::getDocument();

        $model = & $this->getModel();


        // $tpl = JRequest::getVar('tpl');

        
        $giorni =   $model->getGiorni();
        $this->assignRef('giorni', $giorni);
        
        $riepilogo= $model->getTotCouponPerCorso();
        $this->assignRef('riepilogo', $riepilogo);
        
        $daverificare= $model->getCodiciDaVerificare();
        $this->assignRef('daverificare', $daverificare);

        
        parent::display($tpl);
    }

}


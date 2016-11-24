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

class webtvViewreport extends JView {

    private $_dbg;
    private $_japp;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_dbg = JRequest::getBool('dbg', 0);
        $this->_japp = & JFactory::getApplication();
    }

    public function display($tpl = null) {

        $document = & JFactory::getDocument();
        $document->addStyleSheet('components/com_gglms/css/lista.css');
        $document->addStyleSheet('components/com_gglms/css/jquery.dataTables.css');
        $document->addStyleSheet('components/com_gglms/css/jquery.dataTables_themeroller.css');
        $document->addScript('components/com_gglms/js/jquery.dataTables.js');


        $model = & $this->getModel();




        $tpl = JRequest::getVar('tpl');


        if ($tpl == "byContent") {
            $contentStat = $model->getContentStat();
            $this->assignRef('ContentStat', $contentStat);
        }

        if ($tpl == "byDate") {
            $AccesByDay = $model->getAccesByDay();
            $this->assignRef('AccesByDay', $AccesByDay);
        }

        parent::display($tpl);
    }

}


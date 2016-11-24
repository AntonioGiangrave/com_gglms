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

class webtvViewlistcategories extends JView {

    private $_dbg;
    private $_japp;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_dbg = JRequest::getBool('dbg', 0);
        $this->_japp = & JFactory::getApplication();
    }

    public function display($tpl = null) {
        $document = JFactory::getDocument();


        $document->addStyleSheet('http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css');

        $document->addStyleSheet('components/com_gglms/css/tv_listCategories.css');

        $model = & $this->getModel('webtv');
        if (empty($model)) {
            JLoader::import('joomla.application.component.model');
            JLoader::import('webtv', JPATH_BASE . DS . 'components' . DS . 'com_gglms' . DS . 'models');
            $model = JModel::getInstance('webtv', 'webtvModel');
            $model->setState('id', $myItemId);
        }
        $categorie = $model->getCategories('pubblicata=1', null, 'categoria ASC');
        //$categorie = $model->getCategoriesTotalItems('pubblicata=1', null, 'id ASC');

        $this->assignRef('categorie', $categorie);

        parent::display($tpl);
    }

}


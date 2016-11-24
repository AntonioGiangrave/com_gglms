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

class webtvViewcategorie extends JView {

    private $_dbg;
    private $_japp;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_dbg = JRequest::getBool('dbg', 0);
        $this->_japp = & JFactory::getApplication();
    }

    public function display($tpl = null) {


        $webtvModel = & $this->getModel('webtv');

        if (empty($webtvModel)) {
            JLoader::import('joomla.application.component.model');
            JLoader::import('webtv', JPATH_BASE . DS . 'components' . DS . 'com_gglms' . DS . 'models');
            $webtvModel = & JModel::getInstance('webtv', 'webtvModel');
            $webtvModel->setState('id', $myItemId);
        }

        $document = & JFactory::getDocument();
        $document->addStyleSheet(JURI::root(true) . '/components/com_gglms/css/tv_categorie.css');

        $categorieModel = & $this->getModel('categorie');


        $idcat = JRequest::getInt('id');
        $categoria = $webtvModel->getCategories('id=' . $idcat, null, 'categoria ASC,id ASC');

        $categoria = $categoria[0];


        $items_where = 'pubblicato=1';
        $items_orderby = 'datapubblicazione DESC';
        $items = & $categorieModel->getItemsByCategoria($items_where, $items_orderby);

        $pagination = & $categorieModel->getItemsByCategoria_Pagination($items_where, $items_orderby);


        $this->assignRef('categoria', $categoria);
        $this->assignRef('items', $items);
        $this->assignRef('pagination', $pagination);

        $config = & JFactory::getConfig();
        $document = & JFactory::getDocument();

        $document->setTitle($categoria['categoria'] . " - " . $config->getValue('config.sitename'));

        if ($categoria <> '') {
            $document->setDescription("Area tematica ".$categoria['categoria']);
        }

        if ($categoria['categoria'] <> '') {
            $document->setMetadata('keywords', $categoria['categoria']);
        }













        parent::display($tpl);
    }

}


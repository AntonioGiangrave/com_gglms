<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class webtvViewlistlevels extends JView {

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    public function display($tpl = null) {
        $document = JFactory::getDocument();
        $document->addScript('components/com_gglms/js/jquery.dataTables.min.js');
        $document->addScript('components/com_gglms/js/utils.js');
        $document->addScript('components/com_gglms/js/levelslist.js');
        $document->addStyleSheet('components/com_gglms/css/jquery.dataTables.css');
        $document->addStyleSheet('components/com_gglms/css/levelslist.css');
        $document->addStyleSheet('components/com_gglms/css/tv_categorie.css');

        $model = & $this->getModel('webtv');
        if (empty($model)) {
            JLoader::import('joomla.application.component.model');
            JLoader::import('webtv', JPATH_BASE . DS . 'components' . DS . 'com_gglms' . DS . 'models');
            $model = & JModel::getInstance('webtv', 'webtvModel');
            $model->setState('id', $myItemId);
        }

        $levels = $model->getLevelsWithContents();
        debug::vardump($levels);
        $start_level = JRequest::getVar('level', 1);
        debug::vardump($start_level);
        if (!in_array($start_level, $levels))
            $start_level = $levels[0];
        $levels_map = array(1 => 'A1', 2 => 'A2', 3 => 'B1', 4 => 'B2', 5 => 'C1', 6 => 'C2');

        $tot = $model->totContentsByLevel($start_level);
        $this->assignRef('levels', $levels);
        $this->assignRef('start_level', $start_level);
        $this->assignRef('levels_map', $levels_map);
        $this->assign('levels_map_json', json_encode($levels_map));
        $this->assignRef('tot', $tot);
        $this->assign('num_per_page', 5);
        $this->assign('view_level', $levels_map[$start_level]);
        parent::display($tpl);
    }

}


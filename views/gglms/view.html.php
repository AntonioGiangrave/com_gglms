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

class gglmsViewgglms extends JViewLegacy {

    function display($tpl = null) {
        $document = & JFactory::getDocument();
        $config = JFactory::getConfig();
        // $notifiche = $config->get('notifiche');
        // $document->addStyleSheet(JURI::root(true) . '/components/com_gglms/css/tv_home.css');
        // $document->addStyleSheet(JURI::root(true) . '/components/com_gglms/css/general.css');
        // $document->addStyleSheet(JURI::root(true) . '/components/com_gglms/css/search.css');
		
        // $this->assignRef('notifiche', $notifiche);
        

        
        
        parent::display($tpl);
    }

}

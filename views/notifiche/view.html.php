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

class gglmsViewNotifiche extends JViewLegacy {

    function display($tpl = null) {
        $document = JFactory::getDocument();
        $document->addStyleSheet('components/com_gglms/css/search.css');
        $document->addStyleSheet(JURI::root(true) . '/components/com_gglms/css/general.css');

        //RS $document->addScript('components/com_gglms/js/search.js');
        $document->addScript('//raw.github.com/botmonster/jquery-bootpag/master/lib/jquery.bootpag.min.js');
        

        parent::display($tpl);
    }

}

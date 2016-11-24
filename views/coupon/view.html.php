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

class gglmsViewCoupon extends JViewLegacy {

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    public function display($tpl = null) {


        // global $mainframe;
        $document = & JFactory::getDocument();
        // $document->addScript('components/com_gglms/js/jquery-ui-1.8.2.custom.min.js');
        
//        $document->addScript('components/com_gglms/js/miniUpload/assets/js/jquery.knob.js');
//        $document->addScript('components/com_gglms/js/miniUpload/assets/js/jquery.ui.widget.js');
//        $document->addScript('components/com_gglms/js/miniUpload/assets/js/jquery.iframe-transport.js');
//        $document->addScript('components/com_gglms/js/miniUpload/assets/js/jquery.fileupload.js');
//        $document->addScript('components/com_gglms/js/miniUpload/assets/js/script.js');

        $document->addStyleSheet(JURI::root(true) . '/components/com_gglms/js/miniUpload/assets/css/style.css');
        // $document->addStyleSheet(JURI::root(true) . '/components/com_gglms/css/coupon.css');


        parent::display($tpl);
    }

}

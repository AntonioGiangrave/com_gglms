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

class webtvViewProvagratuita extends JView {

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    public function display($tpl = null) {


        global $mainframe;
        $document = & JFactory::getDocument();
        $document->addScript('components/com_gglms/js/jquery-ui-1.8.2.custom.min.js');
        $document->addStyleSheet(JURI::root(true) . '/components/com_gglms/css/provagratuita.css');


        $user = JFactory::getUser();
        $userid = $user->get('id');
        if (!$userid) {
            $tpl = "loggati";
        } else {
            $model = & $this->getModel();
            $pg = $model->check_Coupon();

            if (!empty($pg)) {

                $this->assignRef('data_scadenza', $pg['data_scadenza_f']);
                $this->assignRef('ora_scadenza', $pg['ora_scadenza_f']);

                if (strtotime($pg['data_scadenza']) < strtotime(date("Y-m-d H:i:s")))
                    $tpl = "scaduta";
                else
                    $tpl = "incorso";
            } else
                $tpl = "attivala";
        }
        parent::display($tpl);
    }

}

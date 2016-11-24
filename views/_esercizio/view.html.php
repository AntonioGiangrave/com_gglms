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

class webtvViewesercizio extends JView {

    function display($tpl = null) {

        $id_esercizio = JRequest::getVar('id', 0);
        $urlback = base64_decode(JRequest::getVar('urlback', 0));

        $esercizio = "http://www.e-taliano.tv/home/mediatv/_esercizi/$id_esercizio/index.html";

        $this->assignRef('esercizio', $esercizio);
        $this->assignRef('urlback', $urlback);

        parent::display($tpl);
    }

}

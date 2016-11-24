<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class webtvViewcongressi extends JView {

    function display($tpl = null) {


        $document = JFactory::getDocument();
        
        //includo questo css solo per lo stile del titolo, ma che se ricopio in congresso posso anche togliere.. vabbe capito no.. 
        $document->addStyleSheet('components/com_gglms/css/tv_contenuto.css');

        $document->addStyleSheet('components/com_gglms/css/tv_congresso.css');

        $type = JRequest::getVar('type');

        $model = & $this->getModel();
        $results = $model->get_congressi($type);
        $this->assignRef('congressi', $results);
        $this->assign('type', $type);

        parent::display($tpl);
    }

}

// ~@:-]

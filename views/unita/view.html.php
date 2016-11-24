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

class gglmsViewUnita extends JViewLegacy {

    function display($tpl = null) {

        $document = & JFactory::getDocument();

        $document->addScript('components/com_gglms/js/jquery.min.js');
        $document->addStyleSheet(JURI::root(true) . '/components/com_gglms/css/unita.css');
        $document->addStyleSheet(JURI::root(true) . '/components/com_gglms/css/general.css');
        $document->addStyleSheet(JURI::root(true) . '/components/com_gglms/css/search.css');


        // Latest compiled and minified CSS
        $document->addStyleSheet("https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css");

        // Latest compiled and minified JavaScript
        $document->addScript('https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js');


        $document->addStyleSheet('http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
        // $document->addStyleSheet('components/com_gglms/js/bootstrapRating/css/star-rating.css');
        // $document->addScript('components/com_gglms/js/bootstrapRating/js/star-rating.js');


        FB::log("-> viewUnita");

        $user = JFactory::getUser();
        $app = JFactory::getApplication();


        $document = JFactory::getDocument();

        $model = & $this->getModel();

        $unita = $model->getUnita();


        $this->assignRef('unita', $unita);

        $arraytmpl=array(1 => 'corso', 2=> 'unit');

        // $tpl = $arraytmpl[$unita['tipologia']];

        $tpl = "box";



        // $document->setTitle($contenuto['titolo'] . " - " . $config->getValue('config.sitename'));

        /////
        // if ($contenuto['descrizione'] <> '') {
        //     $document->setDescription($contenuto['descrizione']);
        // }

        // if ($contenuto['meta_tag'] <> '') {
        //     $document->setMetadata('keywords', $contenuto['meta_tag']);
        // }

//
//		if ($this->params->get('robots'))
//		{
//			$this->document->setMetadata('robots', $this->params->get('robots'));
//		}
//
//		if ($app->getCfg('MetaAuthor') == '1')
//		{
//			$this->document->setMetaData('author', $this->item->author);
//		}
//
//		$mdata = $this->item->metadata->toArray();
//		foreach ($mdata as $k => $v)
//		{
//			if ($v)
//			{
//				$this->document->setMetadata($k, $v);
//			}
//		}
        /////

        parent::display($tpl);
    }

}

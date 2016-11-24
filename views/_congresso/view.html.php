<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

require_once 'components/com_gglms/models/libs/contenuto.lib.php';

class webtvViewcongresso extends JView {

    function display($tpl = null) {
        $document = JFactory::getDocument();
        $document->addScript('components/com_gglms/js/html5shiv.js');
        $document->addScript('components/com_gglms/js/mediaelement-and-player.min.js');
        $document->addScript('components/com_gglms/js/webtv.js');
        $document->addStyleSheet('components/com_gglms/css/mediaelementplayer.min.css');
        $document->addStyleSheet('components/com_gglms/css/tv_contenuto.css');
        $document->addStyleSheet('components/com_gglms/css/tv_categorie.css');
        $document->addStyleSheet('components/com_gglms/css/tv_congresso.css');
        
        $model =& $this->getModel();
        $contents = $model->get_congresso(JRequest::getVar('id',0));
        debug::vardump($contents, 'contents');
        $active_content_idx = JRequest::getVar('show', null);
        debug::vardump($active_content_idx, 'active');
        if (is_null($active_content_idx)) {
            $active_content_idx = 0;
        }
        // che cos'ho sul disco?
        $media = get_fs_media($contents[$active_content_idx]['id']);
        debug::vardump($media, 'media types');
        // in base ai media type che trovo carico il giusto template.
        if ($media['html5']) {
            $tpl = 'html5';
        } elseif ($media['flv']) {
            $tpl = 'flash';
        } elseif ($media['mp3']) {
            $tpl = 'audio';
        } 
        debug::vardump($contents, 'contents');

        $this->assignRef('active_content_idx', $active_content_idx);
        $this->assignRef('contents', $contents);

        parent::display($tpl);
    }
}
// ~@:-]

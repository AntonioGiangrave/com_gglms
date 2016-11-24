<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class gglmsViewsearch extends JViewLegacy {

    function display($tpl = null) {

        $document = JFactory::getDocument();
        $document->addStyleSheet('components/com_gglms/css/search.css');
        $document->addStyleSheet(JURI::root(true) . '/components/com_gglms/css/general.css');

        //RS $document->addScript('components/com_gglms/js/search.js');
        $document->addScript('//raw.github.com/botmonster/jquery-bootpag/master/lib/jquery.bootpag.min.js');
        $isAttributeSearch=false;
        
        
        FB::log($_REQUEST,"Post");
        $URI = $_SERVER["REQUEST_URI"];
        FB::log($URI," URI ");
        FB::log($_REQUEST['notifiche']. " 1: ".$_REQUEST['Video']." 2: ".$_REQUEST['Documento']." 3: ".$_REQUEST['ATTESTATI'] ," notifiche ");;
        
        

        if (($_REQUEST['search'])||($_REQUEST['attribute']!='')) {
            FB::log($_REQUEST," Filled Search ");
            $search_model = & $this->getModel('search');
            // $search_words = $search_model->query_handler($_REQUEST['search']);
            $search_words = $_REQUEST['search'];
       //      if (empty($search_words))
       //          throw new BadMethodCallException('Chiave di ricerca non valida', E_USER_ERROR);
            
            $current_page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;

            $search_model->logScearch($search_words);
            $results = $search_model->search($search_words, $_REQUEST['search_level'], $_REQUEST['search_category'], 12, ($current_page - 1) * 10);
            FB::log($results," results ");  //RS
            
            
            $isTestSearch = true; //RS
            
            
            $attributeSearchParam['livello']=$_REQUEST['livello'];
            $attributeSearchParam['area']=$_REQUEST['area'];
            $attributeSearchParam['formato']=$_REQUEST['formato'];
            $attributeSearchParam['prodotto']=$_REQUEST['prodotto'];
            
            FB::log($attributeSearchParam," attributeSearchParam ");  //RS
            
            $results4AttributesFilters = $search_model->search4filters($search_words, $_REQUEST['search_level'], $_REQUEST['search_category'], 12, ($current_page - 1) * 10,$attributeSearchParam);  //RS
            FB::log($results4AttributesFilters," results4AttributesFilters ");  //RS

            $i=0;
            foreach ($results4AttributesFilters as $result)
            {
                if (!in_array($result['id'],$resultsIdString))
                {
                    $resultsIdString[$i]=$result['id']; 
                    $i++;
                }
            }
            $resultsIdString=implode(",",$resultsIdString);
            FB::log($resultsIdString," resultsIdString ");  //RS

            $resultsCountAttributes4filters = $search_model->searchCountAttributes4filters($resultsIdString);  //RS
            FB::log($resultsCountAttributes4filters," resultsCountAttributes4filters ");  //RS
            // NOK $resultsAttributesSearch = $search_model->resultsAttributesSearch($_REQUEST['attribute'],$_REQUEST['attributeval'],$resultsIdString);  //RS
            // NOK FB::log($resultsCountAttributes4filters," resultsCountAttributes4filters ");  //RS
            
            //NOK $menuMgmt->name=$_REQUEST['attribute']; //RS
            //NOK $menuMgmt->action=$_REQUEST['action']; //RS
            //NOK $menuMgmt->search=$_REQUEST['search']; //RS
            
            
            /*  NOK
            if ($_REQUEST['attribute']!= '') //RS
            {
                $results4AttributesFilters = $resultsAttributesSearch;
                $results = $resultsAttributesSearch;
                FB::log($results," results  dentro attribute ");  //RS
            }
            
            */
            $this->assignRef('search_words', $search_words);
            $this->assignRef('search_words_json', json_encode($search_words));
            $this->assignRef('results', $results);
            $this->assignRef('results4AttributesFilters', $results4AttributesFilters);  //RS
            $this->assignRef('resultsCountAttributes4filters', $resultsCountAttributes4filters);  //RS
            $this->assignRef('isTestSearch', $isTestSearch);  //RS
            //NOK $this->assignRef('resultsAttributesSearch', $resultsAttributesSearch);  //RS
            $this->assignRef('resultsIdString', $resultsIdString);  //RS
            $this->assignRef('attributeSearchParam', $attributeSearchParam);  //RS
            $this->assignRef('search', $_REQUEST['search']);  //RS
            $this->assignRef('uri', $URI);  //RS
            //NOK $this->assignRef('menuMgmt', $menuMgmt);  //RS
            //$this->assign('filtered_level', $_REQUEST['search_level']);
            // $this->assign('filtered_category', $_REQUEST['search_category']);
            $this->assign('current_page', $current_page);

        } else {
            
            FB::log($_REQUEST," Empty Search ");
            $isTestSearch = false;  //RS
            $this->assign('search_words_json', "''");
            $this->assign('search_words', array());
            $this->assign('current_page', 1);
        }

        // $webtv_model = & $this->getModel('webtv');
        // if (empty($webtv_model)) {
        //     JLoader::import('joomla.application.component.model');
        //     JLoader::import('webtv', JPATH_BASE . DS . 'components' . DS . 'com_gglms' . DS . 'models');
        //     $webtv_model = & JModel::getInstance('webtv', 'webtvModel');
        //     $webtv_model->setState('id', $myItemId);
        // }
        
        
        // $levels = $webtv_model->getLevelsWithContents();
        // $levels_map = $webtv_model->getLevelsMap();
        // $categories = $webtv_model->getCategories();

         // $this->assignRef('levels', $levels);
         // $this->assignRef('levels_map', $levels_map);
         // $this->assignRef('categories', $categories);

        parent::display($tpl);
    }

}


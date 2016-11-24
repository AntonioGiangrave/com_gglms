<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * Model per la ricerca di contenuti. 
 */
class gglmsModelsearch extends JModelLegacy {

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    public function __destruct() {

    }


    public function logScearch($search_words){

       $user = & JFactory::getUser();


       $query = ' insert into #__gg_log_search (id_utente , stringa) value ('.$user->id.', "'.$search_words.'")';    

       fb::log($query , "query log search");

       $this->_db->setQuery($query);

       $contents = $this->_db->query();

       return true;



   }

   public function search($search_words, $level = null, $category = null, $row_count = 10, $offset = 0) {
    try {

        $user = JFactory::getUser();
        $userid = $user->get('id');
        $groups = JAccess::getGroupsByUser($userid, true);

            // $search_query = $this->_query_builder($search_words);
        $search_param = $this->query_params_handler($search_words);

        $search_words = $this->optimize_serch_words($search_words);

        $level = filter_var($level, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range' => 6)));
        $category = filter_var($category, FILTER_VALIDATE_INT);

        function _remove_empty($value) {
            return !empty($value) || $value === 0;
        }

        $query = '
        SELECT distinct*
        FROM 
        (
            SELECT id,  titolo , alias,  descrizione, "" as meta_tag, "" as abstract, tipologia, "1" as pubblicato, 0 as durata,  
            MATCH(titolo, descrizione) AGAINST('.$search_words.') as rank
            FROM vxvos_gg_unit
            WHERE 
            MATCH(titolo, descrizione) AGAINST('.$search_words.') 

            UNION  



            SELECT c.id, titolo, c.alias, descrizione,  meta_tag, abstract, tipologia, pubblicato, durata,
            MATCH(titolo, abstract, descrizione, meta_tag) AGAINST('.$search_words.') as rank
            FROM `vxvos_gg_contenuti` as c';
            
            if(!empty($search_param[0][0]))  //RS
                $query.= ' JOIN vxvos_gg_param_map as p on p.idcontenuto = c.id  JOIN vxvos_gg_param as p2 on p2.id = p.idparametro';
            $query.= '
            JOIN vxvos_gg_contenuti_acl as acl on c.id = acl.id_contenuto
            
            WHERE 

            id_group in ('.implode(",", $groups).') 

            ';


            if($search_words )
                $query.='and MATCH(titolo, abstract, descrizione, meta_tag) AGAINST('.$search_words.')'; 


            // if(!empty($search_param[0][0]) && $search_words )
            //     $query.= " and ";

            if(!empty($search_param[0][0]))
                $query.='and p2.alias in ('.implode(", ", $search_param[0]).') ';

            $query.=')as tutti
WHERE tutti.pubblicato = 1 '; 

            // if (!empty($level)) {
            //     $query .= ' AND livello=' . $level;
            // }
            // if (!empty($category)) {
            //     $query .= ' AND categoria REGEXP \'[[:<:]]' . $category . '[[:>:]]\'';
            // }

$query .= ' ORDER BY  rank desc';    


            // ATTUALMENTE SENZA LIMITE
            // $query .= ' LIMIT ' . $offset . ', ' . $row_count;

FB::log($query, "query ricerca ");
$this->_db->setQuery($query);

if (false === ($contents = $this->_db->loadAssocList()))
    throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);


            // FB::log($contents);
return $contents;
} catch (Exception $e) {
    FB::exception($e);
}
}




//RS

   public function search4filters($search_words, $level = null, $category = null, $row_count = 10, $offset = 0,$attributeSearchParam) {
    try {

        $user = JFactory::getUser();
        $userid = $user->get('id');
        $groups = JAccess::getGroupsByUser($userid, true);

            // $search_query = $this->_query_builder($search_words);
        $search_param = $this->query_params_handler($search_words);

        $search_words = $this->optimize_serch_words($search_words);

        //NOK $level = filter_var($level, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range' => 6)));
        //NOK $category = filter_var($category, FILTER_VALIDATE_INT);

       

        $query = '
       
            SELECT distinct *
        FROM 
        (
        SELECT id,  titolo , alias,  descrizione, "" as meta_tag, "" as abstract, tipologia, "1" as pubblicato, 0 as durata,"" as livello, "" as area,  
            MATCH(titolo, descrizione) AGAINST('.$search_words.') as rank
            FROM vxvos_gg_unit
            WHERE 
            MATCH(titolo, descrizione) AGAINST('.$search_words.') 

            UNION  
           
            SELECT c.id, titolo, c.alias, descrizione,  meta_tag, abstract, tipologia, pubblicato, durata,livello, area,'; // form.formato as formato, prod.prodotto as prodotto,
            $query.= 'MATCH(titolo, abstract, descrizione, meta_tag) AGAINST('.$search_words.') as rank
            FROM `vxvos_gg_contenuti` as c 
            JOIN vxvos_gg_contenuti_acl as acl on c.id = acl.id_contenuto';
     
            if(!empty($search_param[0][0]))
                $query.= ' JOIN vxvos_gg_param_map as p on p.idcontenuto = c.id  JOIN vxvos_gg_param as p2 on p2.id = p.idparametro';
            foreach( $attributeSearchParam as $key => $item )
                {
                if($key == 'prodotto' && $item != '')    
                     $query.= ' JOIN vxvos_gg_prodotti_contenuti_map as prodmap on c.id = prodmap.idcontenuto JOIN vxvos_gg_prodotti as prod on prod.id = prodmap.idprodotto';
                if($key == 'formato' && $item != '')        
                     $query.= ' JOIN vxvos_gg_formati_contenuti_map as formmap on c.id = formmap.idcontenuto JOIN vxvos_gg_formati  as form on form.id= formmap.idformato';
                }
            $query.= '
            WHERE 
            id_group in ('.implode(",", $groups).') 
            and c.id = acl.id_contenuto
            ';
        
            if($search_words )
                $query.='and MATCH(titolo, abstract, descrizione, meta_tag) AGAINST('.$search_words.')'; 
            if(!empty($search_param[0][0]))
                $query.='and p.idcontenuto = c.id and p2.id = p.idparametro and p2.alias in ('.implode(", ", $search_param[0]).') ';

            if (!empty($attributeSearchParam))
                foreach( $attributeSearchParam as $key => $item )
                {
                        if($key == 'livello' && $item != '')
                            $query.=' and '.$key.'='.$item; 
                        if($key== 'area' && $item != '')
                            $query.=' and '.$key.'='.$item;
                        if($key == 'formato' && $item != '')
                            $query.=' and  c.id = formmap.idcontenuto and form.id= formmap.idformato and form.formato = "'.$item.'"'; 
                        if($key == 'prodotto' && $item != '')
                           $query.=' and   c.id = prodmap.idcontenuto and prod.id= prodmap.idprodotto and prod.prodotto = "'.$item.'"'; 
                }
  
            $query.=')as tutti
                    WHERE tutti.pubblicato = 1 '; 
                
$query .= ' ORDER BY  rank desc';    
FB::log($query, "query search4filters ");

$this->_db->setQuery($query);

if (false === ($contents = $this->_db->loadAssocList()))
    throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);

            // FB::log($contents);
FB::log($contents, " return search4filters");
return $contents;


} catch (Exception $e) {
    FB::exception($e);
}
}


//RS 4 bck
   public function search4filters_bck($search_words, $level = null, $category = null, $row_count = 10, $offset = 0,$attribute,$attributeval) {
    try {

        $user = JFactory::getUser();
        $userid = $user->get('id');
        $groups = JAccess::getGroupsByUser($userid, true);

            // $search_query = $this->_query_builder($search_words);
        $search_param = $this->query_params_handler($search_words);

        $search_words = $this->optimize_serch_words($search_words);

        $level = filter_var($level, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range' => 6)));
        $category = filter_var($category, FILTER_VALIDATE_INT);

        

        $query = '
        SELECT distinct*
        FROM 
        (
            SELECT id,  titolo , alias,  descrizione, "" as meta_tag, "" as abstract, tipologia, "1" as pubblicato, 0 as durata,"" as livello, "" as area, "" as formato, "" as prodotto,  
            MATCH(titolo, descrizione) AGAINST('.$search_words.') as rank
            FROM vxvos_gg_unit
            WHERE 
            MATCH(titolo, descrizione) AGAINST('.$search_words.') 

            UNION  



            SELECT c.id, titolo, c.alias, descrizione,  meta_tag, abstract, tipologia, pubblicato, durata,livello, area, form.formato, prod.prodotto,
            MATCH(titolo, abstract, descrizione, meta_tag) AGAINST('.$search_words.') as rank
            FROM `vxvos_gg_contenuti` as c
            JOIN vxvos_gg_param_map as p on p.idcontenuto = c.id
            JOIN vxvos_gg_param as p2 on p2.id = p.idparametro
            JOIN vxvos_gg_contenuti_acl as acl on c.id = acl.id_contenuto
            JOIN vxvos_gg_prodotti_contenuti_map as prodmap on c.id = prodmap.idcontenuto
            JOIN vxvos_gg_formati_contenuti_map as formmap on c.id = formmap.idcontenuto
	    JOIN vxvos_gg_prodotti as prod on prod.id = prodmap.idprodotto
            JOIN vxvos_gg_formati  as form on form.id= formmap.idformato 
            
            WHERE 

            id_group in ('.implode(",", $groups).') 

            ';


            if($search_words )
                $query.='and MATCH(titolo, abstract, descrizione, meta_tag) AGAINST('.$search_words.')'; 


            // if(!empty($search_param[0][0]) && $search_words )
            //     $query.= " and ";

            if(!empty($search_param[0][0]))
                $query.='and p2.alias in ('.implode(", ", $search_param[0]).') ';

            $query.=')as tutti
WHERE tutti.pubblicato = 1 '; 

            // if (!empty($level)) {
            //     $query .= ' AND livello=' . $level;
            // }
            // if (!empty($category)) {
            //     $query .= ' AND categoria REGEXP \'[[:<:]]' . $category . '[[:>:]]\'';
            // }

$query .= ' ORDER BY  rank desc';    


            // ATTUALMENTE SENZA LIMITE
            // $query .= ' LIMIT ' . $offset . ', ' . $row_count;

FB::log($query, "query search4filters ");
$this->_db->setQuery($query);

if (false === ($contents = $this->_db->loadAssocList()))
    throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);


            // FB::log($contents);
FB::log($contents, " return search4filters");
return $contents;


} catch (Exception $e) {
    FB::exception($e);
}
}

//RS

/* NOK
          public function resultsAttributesSearch($attributeType,$attributeName,$idList) {
    try {
        $query = '
        
        
            SELECT c.id, titolo, c.alias, descrizione,  meta_tag, abstract, tipologia, pubblicato, durata,livello, area, form.formato, prod.prodotto
            
            FROM `vxvos_gg_contenuti` as c, 
                  vxvos_gg_prodotti as prod,
                  vxvos_gg_prodotti_contenuti_map as prodmap,
                  vxvos_gg_formati_contenuti_map as formmap,
                  vxvos_gg_prodotti as prod,
                  vxvos_gg_formati  as form
           
           
            WHERE c.id in ('.$idList.')';
            if ($attributeType == 'livello')
                $query.=' c.livello = '.$attributeName;
            if ($attributeType == 'area')
                $query.=' c.area = '.$attributeName;
            if ($attributeType == 'formato')   
            $query.='and form.id= formmap.idformato
                     and c.id = formmap.idcontenuto 
                     and form.formato = '.$attributeName;
            if ($attributeType == 'prodotto')   
            $query.='and prod.id= prodmap.idprodotto
                     and c.id = prodmap.idcontenuto 
                     and prod.prodotto = '.$attributeName;

FB::log($query, "query  searchAttributelist ");
$this->_db->setQuery($query);
//print_r($query);
//exit();
if (false === ($searchAttributelist = $this->_db->loadAssocList()))
    throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);


  

FB::log($searchAttributelist, " searchAttributelist");
return $searchAttributelist;


} catch (Exception $e) {
    FB::exception($e);
}
}
        
 */       
        

//RS

   public function searchCountAttributes4filters($idList) {
    try {

//RS count livello
        $queryLivello = '
        select livello,count(*)as count 
        FROM `vxvos_gg_contenuti` as c
        where id in ('.$idList.')
        group by livello';
        
FB::log($queryLivello, "query  livello searchCountAttributes4filters ");
$this->_db->setQuery($queryLivello);

if (false === ($counterslistlivello = $this->_db->loadAssocList()))
    throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);


            // FB::log($contents);
FB::log($counterslistlivello, " counterslistlivello");
$counterslist[livello]=$counterslistlivello;


//RS count Area
        $queryArea = 'select area,count(*)as count 
        FROM `vxvos_gg_contenuti` as c
        where id in ('.$idList.')
        group by area';


FB::log($queryArea, "query  area searchCountAttributes4filters ");
$this->_db->setQuery($queryArea);

if (false === ($counterslistarea = $this->_db->loadAssocList()))
    throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);


            // FB::log($contents);
FB::log($counterslistarea, " counterslistarea");
$counterslist[area]=$counterslistarea;


//RS count formato

        $queryFormato = '
        
            SELECT form.formato,count(*) as count
            FROM `vxvos_gg_contenuti` as c
            JOIN vxvos_gg_formati_contenuti_map as formmap on c.id = formmap.idcontenuto
            JOIN vxvos_gg_formati  as form on form.id= formmap.idformato 
            where c.id in ('.$idList.')
            group by form.formato';

FB::log($queryFormato, "query  formato  searchCountAttributes4filters ");
$this->_db->setQuery($queryFormato);

if (false === ($counterslistformato = $this->_db->loadAssocList()))
    throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);


            // FB::log($contents);
FB::log($counterslistformato, " counterslistformato");


$counterslist[formato]=$counterslistformato;


//RS count prodotto

        $queryProdotto = '
        SELECT prod.prodotto,count(*) as count
            FROM `vxvos_gg_contenuti` as c
            JOIN vxvos_gg_prodotti_contenuti_map as prodmap on c.id = prodmap.idcontenuto
            JOIN vxvos_gg_prodotti  as prod on prod.id= prodmap.idprodotto 
            where c.id in ('.$idList.') 
            group by prod.prodotto';


FB::log($queryProdotto, "query  prodotto searchCountAttributes4filters ");
$this->_db->setQuery($queryProdotto);

if (false === ($counterslistprodotto = $this->_db->loadAssocList()))
    throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);


            // FB::log($contents);
FB::log($counterslistprodotto, " counterslistprodotto");
$counterslist[prodotto]=$counterslistprodotto;



FB::log($counterslist, " counterslist totale");
return $counterslist;


} catch (Exception $e) {
    FB::exception($e);
}
}




public function query_params_handler($q){


    preg_match_all('/t:[a-z]*/', $q, $parametri);




    foreach ($parametri[0] as &$item) {
        $item="'".preg_replace('/t:/', '', $item)."'";
    }
    FB::log($parametri, "parametri cercati");

    return $parametri;
}



public function query_handler($q) {

    $q= preg_replace('/t:[a-z]*/', '', $q);

    FB::log($q, "testo cercato ripulito");

    preg_match_all('/(?<!")\b\w+\b|(?<=")\b[^"]+/', $q, $words);

    if (empty($words[0]))
        return 0;
    fb::log($words, "parole cercate ");

    $search_words = array();

    foreach ($words[0] as $word) {
        $word = filter_var($word, FILTER_SANITIZE_STRING);
        if (!empty($word))
            $search_words[] = $word;
    }

    fb::log($search_words, "search_words");

    return $search_words;
}

public function optimize_serch_words($search_words){

    $optimized_word ="";
    $search_words = $this->query_handler($search_words);


    if(!$search_words)
        return 0;

    foreach ($search_words as $word) {
        $optimized_word .= " '*".$word ."*' ";
    }


    $optimized_word .= ' IN BOOLEAN MODE';

    FB::log($search_words, "search_words");
    FB::log($optimized_word, "optimized_word");

    return $optimized_word;
}

}


<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die;

class gglmsHelper {

    
    
	public static function getContenutiSiteMap() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('titolo, descrizione, alias, datapubblicazione');
		$query->from('#__gg_contenuti');

		$db->setQuery((string) $query);
		$res = $db->loadAssocList();

		$xml = "";
		foreach ($res as $item) {

			$xml.="
			<url>
				<loc>http://www.mdwebtv.it/home/contenuto/" . $item['alias'] . ".html</loc>
				<lastmod>" . $item['datapubblicazione'] . "</lastmod>
				<changefreq>monthly</changefreq>
				<priority>1.0</priority>                    
			</url>
			";
		}
		return $xml;
	}

	public static function getCongressiSiteMap() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('congresso, alias');
		$query->from('#__gg_congressi');

		$db->setQuery((string) $query);
		$res = $db->loadAssocList();

		$xml = "";
		foreach ($res as $item) {

			$xml.="
			<url>
				<loc>http://www.mdwebtv.it/home/congresso/" . $item['alias'] . ".html</loc>
				<changefreq>monthly</changefreq>
				<priority>1.0</priority>                    
			</url>
			";
		}
		return $xml;
	}

	public static function getCategorieSiteMap() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('categoria, alias');
		$query->from('#__gg_categorie');

		$db->setQuery((string) $query);
		$res = $db->loadAssocList();

		$xml = "";
		foreach ($res as $item) {

			$xml.="
			<url>
				<loc>http://www.mdwebtv.it/home/categoria/" . $item['alias'] . ".html</loc>
				<changefreq>monthly</changefreq>
				<priority>1.0</priority>                    
			</url>
			";
		}
		return $xml;
	}

	public static function getbox(){

		$var = "box";
		return $var;
	}

	public static function setLog( $user_id, $element_id, $tipologia = null, $uniqid = null){

		$db = & JFactory::getDbo();

		$query = "
		INSERT IGNORE INTO #__gg_log (id_utente, id_contenuto, data_accesso, supporto, ip_address, uniqid)
		VALUE (".$user_id." ,  ".$element_id.", '".date('Y-m-d H:i:s')."', '". $tipologia."' , '".$_SERVER['REMOTE_ADDR']."' , '".$uniqid."')
		";

        FB::log($query, "setTrack");

		$db->setQuery($query);
		$results = $db->query();
	}


	public static function setPermanenza($permanenza , $uniqid ){

		$db = & JFactory::getDbo();

		$query = "

		UPDATE #__gg_log 
		SET
			permanenza = $permanenza
		WHERE 
 			uniqid = '$uniqid'
		
		";

        FB::log($query, "setPermanenza");

		$db->setQuery($query);
		$results = $db->query();
	}




	public static function getGiaVisto($itemid){

		$user = JFactory::getUser();
		$userid = $user->get('id');
		$db = JFactory::getDBO();

		try{ 
			$query = "select count(*) from #__gg_log where id_utente = $userid and id_contenuto = $itemid limit 1 ";
            // FB::log($query, "setTrack");
			$db->setQuery($query);
			$result = $db->loadResult();
		}
		catch (Exception $e)
		{
		}
		return $result;

	}


	public static function getViews($itemid){

		$db = JFactory::getDBO();

		try{ 
			$query = "select count(*) from #__gg_log where id_contenuto = $itemid limit 1 ";
            // FB::log($query, "setTrack");
			$db->setQuery($query);
			$result = $db->loadResult();
		}
		catch (Exception $e)
		{
		}
		return $result;

	}


	public static function getMostView(){

		try{


			$user = JFactory::getUser();
			$userid = $user->get('id');

			$groups = JAccess::getGroupsByUser($userid, true);

			$results = array();
			$db = JFactory::getDBO();
			$query = '  
			SELECT
				c.*, count(l.id) as views
			FROM
            	#__gg_log AS l
			Inner Join #__gg_contenuti AS c ON c.id = l.id_contenuto
			Inner Join #__gg_contenuti_acl AS acl ON acl.id_contenuto = c.id

			WHERE  id_group in ('.implode(",", $groups).')	
			


			GROUP BY c.id
			ORDER BY views DESC
			LIMIT 12
			';
			$db->setQuery($query);


			// FB::LOG($query, 'getMostView');


			if (false === ($results = $db->loadAssocList()))
				throw new RuntimeException($db->getErrorMsg(), E_USER_ERROR);

			foreach ($results as &$contenuto) {
				$contenuto['prerequisiti'] = gglmsHelper::_chek_prerequisiti($contenuto['id']);
				$contenuto['stato'] = gglmsHelper::_check_stato($contenuto['id']);
				$contenuto['giavisto'] = gglmsHelper::getGiaVisto($contenuto['id']);
				$contenuto['views'] = gglmsHelper::getViews($contenuto['id']);
			}
		}
		catch(Exception $e)
		{

		}

		return $results;

	}


	public static function getTopRated(){

		try{


			$user = JFactory::getUser();
			$userid = $user->get('id');

			$groups = JAccess::getGroupsByUser($userid, true);

			$results = array();
			$db = JFactory::getDBO();
			$query = '  
			SELECT
				c.*,
				sum(rating) as totRating
			FROM
            	#__gg_rating AS r
			Inner Join #__gg_contenuti AS c ON c.id = r.id_contenuto
			Inner Join #__gg_contenuti_acl AS acl ON acl.id_contenuto = c.id

			WHERE  id_group in ('.implode(",", $groups).')	

			GROUP BY c.id
			ORDER BY totRating desc
			LIMIT 12
			';
			$db->setQuery($query);


			// FB::LOG($query, 'getMostView');


			if (false === ($results = $db->loadAssocList()))
				throw new RuntimeException($db->getErrorMsg(), E_USER_ERROR);

			foreach ($results as &$contenuto) {
				$contenuto['prerequisiti'] = gglmsHelper::_chek_prerequisiti($contenuto['id']);
				$contenuto['stato'] = gglmsHelper::_check_stato($contenuto['id']);
				$contenuto['giavisto'] = gglmsHelper::getGiaVisto($contenuto['id']);
				$contenuto['views'] = gglmsHelper::getViews($contenuto['id']);
			}
		}
		catch(Exception $e)
		{

		}

		return $results;

	}




	public static function content_type(){


		try {
			$db = JFactory::getDBO();
			$query = '  
			select 
            	 *
			from (
				SELECT *
				FROM 
                            #__gg_contenuti_tipology
                            where pubblicato = 1

				UNION ALL

				SELECT *
				FROM 
                            #__gg_unit_tipology
				) as tutti
ORDER BY ordinamento
'; 

FB::log($query, "query typology");
$db->setQuery($query);

if (false === ($results = $db->loadObjectList()))
	throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);

FB::log($results, 'contenttype');
return $results;
} catch (Exception $e) {
	FB::exception($e);
}
}

public static function getBreadcrumb($unitid = null , $link = null , $itemid = null){

	$out="";
	$db = JFactory::getDBO();
	$query = $db->getQuery(true);
	$query->select('id, titolo, alias, categoriapadre');
	$query->from('#__gg_unit as u ');
    // $query->where('1 =  1 ');

    //se mi arriva l'id unita
	if($unitid){
		$query->where(' u.id = '.$unitid);
	}

    //se mi arriva l'id contenuto
	else if($itemid){
		$query->join('inner ' ,'#__gg_unit_map as m on m.idunita=u.id ');
		$query->where(' m.idcontenuto = ' .$itemid);
	}

    //tolgo le categorie speciali
	$query->where(' tipologia != 110 ');
	$query->limit('1');

	$db->setQuery($query);
	$res = $db->loadAssoc();

	if($res['categoriapadre'] != '1' )
		$out.= gglmsHelper::getBreadcrumb($res['categoriapadre']);

	$out .= '<li><a href="'.JURI::base()."component/gglms/unita/".$res['alias'].'">'.$res['titolo'].'</a><span class="divider"></span></li>'   ;

	return $out;
}


public static function getSubUnit($unit_id){
	try{
		$results = array();
		$db = JFactory::getDBO();
		$query = '  
		SELECT id,  titolo , alias,  descrizione, "" as meta_tag, "" as abstract, tipologia, "1" as pubblicato  

		FROM #__gg_unit 

		WHERE categoriapadre = '.$unit_id .' 

		ORDER BY ordinamento 

		';

		// FB::log($query, "getSubUnit query");

		$db->setQuery($query);
		if (false === ($results = $db->loadAssocList()))
			throw new RuntimeException($db->getErrorMsg(), E_USER_ERROR);
	}
	catch(Exception $e)
	{

	}


	foreach ($results as  $key => $item) {
		 $sub_content = gglmsHelper::getTOTContenuti($item['id']);
		 $sub_unit = gglmsHelper::getSubUnit($item['id']);

		 if(!$sub_content && !$sub_unit)
		 	unset($results[$key]);

	}

	// FB::log($results, "getSubUnit Results");
	return $results;
}



public static function getContenuti($unit_id){
	try{
		
		$user = JFactory::getUser();
		$userid = $user->get('id');

		// jimport( 'joomla.access.access' );
		//RICORSIVITA' GRUPPI
		//TRUE 	:Gruppi ricorsivi.
		//FALSE :Solo gruppi di diretta appartenenza
		$groups = JAccess::getGroupsByUser($userid, true);

		$results = array();
		$db = JFactory::getDBO();
		$query = '  

		SELECT DISTINCT u.*, c.*
		FROM #__gg_unit_map as u
		LEFT JOIN #__gg_contenuti as c on c.id = u.idcontenuto 

		Left Join #__gg_contenuti_acl AS acl ON acl.id_contenuto = c.id

		WHERE 
		u.idunita ='.$unit_id .' 
		and c.pubblicato = 1
		and id_group in ('.implode(",", $groups).')	

		ORDER BY ordinamento';

		//FB::log($query, "getContenuti");

		$db->setQuery($query);

		if (false === ($results = $db->loadAssocList()))
			throw new RuntimeException($db->getErrorMsg(), E_USER_ERROR);

	}
	catch(Exception $e)
	{

	}

	return $results;
}

//RS
public static function getTOTContenuti4TextSearch()
 {
	try{

            //verifica id unita se ha contenuti per ACL
            
		$user = JFactory::getUser();
		$userid = $user->get('id');
		

		// jimport( 'joomla.access.access' );
		//RICORSIVITA' GRUPPI
		//TRUE 	:Gruppi ricorsivi.
		//FALSE :Solo gruppi di diretta appartenenza
		$groups = JAccess::getGroupsByUser($userid , true);


		$totcontenuti=0;
		$db = JFactory::getDBO();
		$query = '  
		SELECT count(*)
                    FROM #__gg_unit_map as u
                    LEFT JOIN #__gg_contenuti as c on c.id = u.idcontenuto 
                    INNER Join #__gg_contenuti_acl AS acl ON acl.id_contenuto = c.id
                    WHERE 
        	u.idunita ='.$unit_id .' 
        	and c.pubblicato = 1
			and id_group in ('.implode(",", $groups).')	

                ORDER BY ordinamento
        ';
        

         // FB::LOG($query, "query getTOTContenuti4Search");
        // FB::LOG($groups, "groups getTOTContenuti");


        $db->setQuery($query);

        $totcontenuti = $db->loadResult();
        
        /* RS
        $subunit=gglmsHelper::getSubUnit($unit_id);
        foreach ($subunit as $unit) {
        	$parziali = gglmsHelper::getTOTContenuti($unit['id']);
        	$totcontenuti +=$parziali;
        }
        */

        
    }
    catch(Exception $e)
    {

    }
 FB::LOG($totcontenuti, "lista getTOTContenuti4Search");
    return $totcontenuti;
}
        
        
        

public static function getTOTContenuti($unit_id){
	try{

            //verifica id unita se ha contenuti per ACL
            
		$user = JFactory::getUser();
		$userid = $user->get('id');

		// jimport( 'joomla.access.access' );
		//RICORSIVITA' GRUPPI
		//TRUE 	:Gruppi ricorsivi.
		//FALSE :Solo gruppi di diretta appartenenza
		$groups = JAccess::getGroupsByUser($userid , true);


		$totcontenuti=0;
		$db = JFactory::getDBO();
		$query = '  
		SELECT count(*)
        FROM #__gg_unit_map as u
        LEFT JOIN #__gg_contenuti as c on c.id = u.idcontenuto 

        INNER Join #__gg_contenuti_acl AS acl ON acl.id_contenuto = c.id
		

        WHERE 
        	u.idunita ='.$unit_id .' 
        	and c.pubblicato = 1
			and id_group in ('.implode(",", $groups).')	

        ORDER BY ordinamento
        ';
        

         // FB::LOG($query, "query getTOTContenuti");
        // FB::LOG($groups, "groups getTOTContenuti");


        $db->setQuery($query);

        $totcontenuti = $db->loadResult();

        $subunit=gglmsHelper::getSubUnit($unit_id);
        foreach ($subunit as $unit) {
        	$parziali = gglmsHelper::getTOTContenuti($unit['id']);
        	$totcontenuti +=$parziali;
        }


        
    }
    catch(Exception $e)
    {

    }
 // FB::LOG($totcontenuti, "lista getTOTContenuti");
    return $totcontenuti;
}





public static function _chek_prerequisiti($item_id) {

    // FB::log($prerequisiti, "prerequisiti");
	$db = JFactory::getDBO();
	$query = 'SELECT prerequisiti FROM #__gg_contenuti WHERE id = '.$item_id;
	$db->setQuery($query);
	$prerequisiti = $db->loadResult();


	if(!$prerequisiti || $prerequisiti=="")
		return 1;

	try {
		$prerequisiti = explode(",", $prerequisiti);

		foreach ($prerequisiti as $prerequisito) {
			$check = gglmsHelper::_check_stato($prerequisito);
            // FB::log($prerequisito."-".$check, "prerequisito   check");
			if($check!="completed")
				return 0;
		}

	} catch (Exception $e) {
		return 0;
	}
	return 1;
}

public static function _check_stato($elementid){
	try{
		$user = JFactory::getUser();
		$userid = $user->get('id');
		$db = JFactory::getDBO();

		$query = "  SELECT varValue 
		FROM 
               #__gg_scormvars
		WHERE 
		SCOInstanceID = $elementid
		AND 
		UserID = $userid
		AND 
		varName= 'cmi.core.lesson_status'
		LIMIT 1
		";

		$db->setQuery($query);
		$check = $db->loadResult();

	}
	catch(Exception $e){
		return 0;
	}
	return $check;
}

}
<?php
defined('_JEXEC') or die;
require_once 'models/libs/FirePHPCore/fb.php';
/**
 * Joomla component com_gglms
 * 
 * @package WebTV
 * Router.php
 */
class GglmsRouter extends JComponentRouterBase
{

function build(&$query) {
    $segments = array();

    if (isset($query['view'])) {
        $segments[] = $query['view'];
        unset($query['view']);
    }

    if (isset($query['id'])) {
        $segments[] = $query['id'];
        unset($query['id']);
    }

    if (isset($query['type'])) {
        $segments[] = $query['type'];
        unset($query['type']);
    }

    if (isset($query['alias'])) {
        $segments[] = $query['alias'];
        unset($query['alias']);
    }

    if (isset($query['unit'])) {
     //   $segments[] = $query['alias'];
        unset($query['unit']);
    }

    // FB::log($segments, "segments" );
    return $segments;
}

function parse(&$segments) {
	
    $db = JFactory::getDbo();
    $vars = array();
    FB::info($segments, "SEGMENTS1");

    switch ($segments[0]) {


        case 'unita':

        
        $vars['view'] = 'unita';
            if(is_numeric($segments[1])){
                $vars['id'] = $segments[1];
            }
            else
            {
            $query = $db->setQuery($db->getQuery(true)
                ->select('id')
                ->from('#__gg_unit')
                ->where('alias="' . $segments[1] . '"')
            );

            $vars['id'] = $db->loadResult();
            }

        
        break;


        case 'categorie':
        $vars['view'] = 'categorie';
        $query = $db->setQuery($db->getQuery(true)
            ->select('id')
            ->from('#__gg_categorie')
            ->where('alias="' . $segments[1] . '"')
            );

        $vars['id'] = $db->loadResult();
        break;


      
        case 'coupon':
        $vars['view'] = 'coupon';

        break;


      
        case 'report':
        $vars['view'] = 'report';

        break;

        case 'notifiche':
        $vars['view'] = 'notifiche';
        break;

        case 'contenuto':
        case 'elemento':	
        FB::log( "ROUTER -> ELEMENTO ");
        $vars['view'] = 'element';

        if (strpos($segments[1], ':') === false) {
            $alias = $segments[1];    
        }
        else
        {
            list($idlink, $alias) = explode(':', $segments[1], 2);
            $vars['idlink'] = $idlink;
        }
        $query = $db->setQuery($db->getQuery(true)
            ->select('id')
            ->from('#__gg_contenuti')
            ->where('alias="' . $alias . '"')
            );
            $vars['id'] = $db->loadResult();

        break;



        default:
        $query = $db->setQuery($db->getQuery(true)
            ->select('id')
            ->from('#__gg_contenuti')
            ->where('alias="' . $segments[0] . '"')
            );
        if ($db->loadResult()) {
            $vars['id'] = $db->loadResult();
            $vars['view'] = 'contenuto';
            break;
        }



    }
    return $vars;
}
}
function gglmsBuildRoute(&$query)
{
	$router = new GglmsRouter;
	return $router->build($query);
}

function gglmsParseRoute($segments)
{
	$router = new GglmsRouter;

	return $router->parse($segments);
}



<?php
/*------------------------------------------------------------------------
# com_citruscart
# ------------------------------------------------------------------------
# author   Citruscart Team  - Citruscart http://www.citruscart.com
# copyright Copyright (C) 2014 Citruscart.com All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://citruscart.com
# Technical Support:  Forum - http://citruscart.com/forum/index.html
# Fork of Tienda
# @license GNU/GPL  Based on Tienda by Dioscouri Design http://www.dioscouri.com.
-------------------------------------------------------------------------*/
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');

jimport('joomla.application.component.controller');

$app = JFactory::getApplication();

//j3 compatibility
if(!defined('DS')){
	define('DS','/');
}
require_once(JPATH_SITE.'/libraries/dioscouri/loader.php');

require_once (JPATH_ADMINISTRATOR.'/components/com_citruscart/helpers/toolbar.php');

require_once (JPATH_ADMINISTRATOR.'/components/com_citruscart/helpers/version.php');

//JHtml::_('behavior.core');
JHtml::_('jQuery.framework');

JHtml::_('bootstrap.framework');
// Check the registry to see if our Citruscart class has been overridden
if ( !class_exists('Citruscart') )
    JLoader::register( "Citruscart", JPATH_ADMINISTRATOR."/components/com_citruscart/defines.php" );

// load the config class
Citruscart::load( 'Citruscart', 'defines' );

// Load Custom Language File if needed (com_citruscart_custom)
if(Citruscart::getInstance()->get('custom_language_file', '0'))
{
	$lang = JFactory::getLanguage();
	$extension = 'com_citruscart_custom';
	$base_dir = JPATH_ADMINISTRATOR;
	$lang->load($extension, $base_dir, null, true);
}

// before executing any tasks, check the integrity of the installation
Citruscart::getClass( 'CitruscartHelperDiagnostics', 'helpers.diagnostics' )->checkInstallation();

// Require the base controller
Citruscart::load( 'CitruscartController', 'controller' );

// Check if protocol is specified
//$protocol = JRequest::getWord('protocol', '');
$protocol = $app->input->getWord('protocol', '');


// Require specific controller if requested
$controller = $app->input->getWord('controller', $app->input->get('view') );

// if protocol is specified, try to load the specific controller
if(strlen($protocol))
{
	// file syntax: controller_json.php
	if (Citruscart::load( 'CitruscartController'.$controller.$protocol, "controllers.".$controller."_".$protocol )) {
    	$controller .=  $protocol;
	}
}
else
{
	if (!Citruscart::load( 'CitruscartController'.$controller, "controllers.$controller" )) {
    	$controller = '';
	}
}


if (empty($controller))
{
    // redirect to default
    $default_controller = new CitruscartController();
    $redirect = "index.php?option=com_citruscart&view=" . $default_controller->default_view;
    $redirect = JRoute::_( $redirect, false );
    $app->redirect( $redirect );
}

$doc = JFactory::getDocument();
$uri = JURI::getInstance();
$js = "var com_citruscart = {};\n";
$js.= "com_citruscart.jbase = '".$uri->root()."';\n";
$doc->addScriptDeclaration($js);

$parentPath = JPATH_ADMINISTRATOR . '/components/com_citruscart/helpers';
DSCLoader::discover('CitruscartHelper', $parentPath, true);

$parentPath = JPATH_ADMINISTRATOR . '/components/com_citruscart/library';
DSCLoader::discover('Citruscart', $parentPath, true);

JHtml::_('script', 'media/citruscart/js/common.js', false, false);
JHtml::_('script', 'media/citruscart/js/citruscart_admin.js', false, false);
JHtml::_('stylesheet', 'media/citruscart/css/common.css');

// load the plugins
JPluginHelper::importPlugin( 'citruscart' );

// Create the controller
$classname = 'CitruscartController'.$controller;

$controller = Citruscart::getClass( $classname );

// ensure a valid task exists
$task = $app->input->getString('task');

if (empty($task))
{
    $task = 'display';
}

$app->input->set('task',$task);

// Perform the requested task
$controller->execute( $task );

// Redirect if set by the controller
$controller->redirect();

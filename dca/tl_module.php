<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @package   CalendarFeature
 * @author    Sebastian Buck
 * @license   LGPL
 * @copyright Erdmann & Freunde
 */


 // Anpassung der Palette
 $GLOBALS['TL_DCA']['tl_module']['palettes']['eventlist'] = str_replace(
     'perPage',
     'perPage,events_featured',
     $GLOBALS['TL_DCA']['tl_module']['palettes']['eventlist']
 );

// Feld hinzufügen
$GLOBALS['TL_DCA']['tl_module']['fields']['events_featured'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['events_featured'],
	'default'                 => 'all_items',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('all_events', 'featured_events', 'unfeatured_events'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(18) NOT NULL default ''"
);

<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @package   CalendarHighlight
 * @author    Sebastian Buck
 * @license   LGPL
 * @copyright Erdmann & Freunde
 */


 // Anpassung der Palette
 $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] = str_replace(
     'noComments;',
     'noComments,featured;',
     $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default']
 );

 // Anpassung der Klassen
 $GLOBALS['TL_DCA']['tl_calendar_events']['fields']['noComments']['eval']['tl_class'] = 'clr w50 m12';

 // Feld hinzufügen
 $GLOBALS['TL_DCA']['tl_calendar_events']['fields']['featured'] = array(
   'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['featured'],
   'exclude'                 => true,
   'filter'                  => true,
   'inputType'               => 'checkbox',
   'eval'                    => array('tl_class'=>'w50 m12'),
   'sql'                     => "char(1) NOT NULL default ''"
 );

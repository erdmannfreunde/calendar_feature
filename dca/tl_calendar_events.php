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


 // Toggler-Operation einfügen
array_insert(
  $GLOBALS['TL_DCA']['tl_calendar_events']['list']['operations'],
  6,
  array (
  'feature' => array (
    'label'               => &$GLOBALS['TL_LANG']['tl_calendar_events']['feature'],
    'icon'                => 'featured.gif',
    'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.tl_calendar_feature.toggleFeatured(this,%s)"',
    'button_callback'     => array('tl_calendar_feature', 'iconFeatured')
  )
));

 // Anpassung der Palette
 $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] = str_replace(
     'noComments;',
     'noComments,featured;',
     $GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default']
 );

 // Anpassung der Klassen
 $GLOBALS['TL_DCA']['tl_calendar_events']['fields']['noComments']['eval']['tl_class'] = 'clr w50 m12';

 // Feld hinzufügen
 $GLOBALS['TL_DCA']['tl_calendar_events']['fields']['featured'] = array (
   'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['featured'],
   'exclude'                 => true,
   'filter'                  => true,
   'inputType'               => 'checkbox',
   'eval'                    => array('tl_class'=>'w50 m12'),
   'sql'                     => "char(1) NOT NULL default ''"
 );


 /**
  * Funktionen, zum featuren/unfeaturen von Terminen; Basierend auf tl_news
  */
 class tl_calendar_feature extends tl_calendar_events
 {
   /**
 	 * Return the "feature/unfeature element" button
 	 *
 	 * @param array  $row
 	 * @param string $href
 	 * @param string $label
 	 * @param string $title
 	 * @param string $icon
 	 * @param string $attributes
 	 *
 	 * @return string
 	 */
 	public function iconFeatured($row, $href, $label, $title, $icon, $attributes)
 	{

 		if (strlen(Input::get('fid')))
 		{
 			$this->toggleFeatured(Input::get('fid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
 			$this->redirect($this->getReferer());
 		}

 		// Check permissions AFTER checking the fid, so hacking attempts are logged
 		if (!$this->User->hasAccess('tl_calendar_events::featured', 'alexf'))
 		{
 			return '';
 		}

 		$href .= '&amp;fid='.$row['id'].'&amp;state='.($row['featured'] ? '' : 1);

 		if (!$row['featured'])
 		{
 			$icon = 'featured_.gif';
 		}

 		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label, 'data-state="' . ($row['featured'] ? 1 : 0) . '"').'</a> ';
 	}

  /**
	 * Feature/unfeature a news item
	 *
	 * @param integer       $intId
	 * @param boolean       $blnVisible
	 * @param DataContainer $dc
	 *
	 * @return string
	 */
	public function toggleFeatured($intId, $blnVisible, DataContainer $dc=null)
	{
		// Check permissions to edit
		Input::setGet('id', $intId);
		Input::setGet('act', 'feature');
		$this->checkPermission();

		// Check permissions to feature
		if (!$this->User->hasAccess('tl_calendar_events::featured', 'alexf'))
		{
			$this->log('Not enough permissions to feature/unfeature news item ID "'.$intId.'"', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$objVersions = new Versions('tl_calendar_events', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_calendar_events']['fields']['featured']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_calendar_events']['fields']['featured']['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, ($dc ?: $this));
				}
				elseif (is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, $this);
				}
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_calendar_events SET tstamp=". time() .", featured='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$objVersions->create();
		$this->log('A new version of record "tl_calendar_events.id='.$intId.'" has been created'.$this->getParentEntries('tl_news', $intId), __METHOD__, TL_GENERAL);
	}
 }

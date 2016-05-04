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

namespace Contao;


/**
 * Provide methods to get all events of a certain period from the database.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
abstract class EventsHighlight extends \Events
{

  /**
	 * Get all events of a certain period
	 *
	 * @param array   $arrCalendars
	 * @param integer $intStart
	 * @param integer $intEnd
	 *
	 * @return array
	 */
	protected function getAllEvents($arrCalendars, $intStart, $intEnd, $settingFeatured)
	{
		if (!is_array($arrCalendars))
		{
			return array();
		}

		$this->arrEvents = array();

		foreach ($arrCalendars as $id)
		{
			$strUrl = $this->strUrl;
			$objCalendar = \CalendarModel::findByPk($id);

			// Get the current "jumpTo" page
			if ($objCalendar !== null && $objCalendar->jumpTo && ($objTarget = $objCalendar->getRelated('jumpTo')) !== null)
			{
				/** @var \PageModel $objTarget */
				$strUrl = $objTarget->getFrontendUrl((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ? '/%s' : '/events/%s');
			}

			// Get the events of the current period
			$objEvents = \CalendarEventsModelHighlight::findCurrentByPid($id, $intStart, $intEnd, $arrOptions=array(), $settingFeatured);

			if ($objEvents === null)
			{
				continue;
			}

			while ($objEvents->next())
			{
				$this->addEvent($objEvents, $objEvents->startTime, $objEvents->endTime, $strUrl, $intStart, $intEnd, $id);

				// Recurring events
				if ($objEvents->recurring)
				{
					$arrRepeat = deserialize($objEvents->repeatEach);

					if ($arrRepeat['value'] < 1)
					{
						continue;
					}

					$count = 0;
					$intStartTime = $objEvents->startTime;
					$intEndTime = $objEvents->endTime;
					$strtotime = '+ ' . $arrRepeat['value'] . ' ' . $arrRepeat['unit'];

					while ($intEndTime < $intEnd)
					{
						if ($objEvents->recurrences > 0 && $count++ >= $objEvents->recurrences)
						{
							break;
						}

						$intStartTime = strtotime($strtotime, $intStartTime);
						$intEndTime = strtotime($strtotime, $intEndTime);

						// Skip events outside the scope
						if ($intEndTime < $intStart || $intStartTime > $intEnd)
						{
							continue;
						}

						$this->addEvent($objEvents, $intStartTime, $intEndTime, $strUrl, $intStart, $intEnd, $id);
					}
				}
			}
		}

		// Sort the array
		foreach (array_keys($this->arrEvents) as $key)
		{
			ksort($this->arrEvents[$key]);
		}

		// HOOK: modify the result set
		if (isset($GLOBALS['TL_HOOKS']['getAllEvents']) && is_array($GLOBALS['TL_HOOKS']['getAllEvents']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getAllEvents'] as $callback)
			{
				$this->import($callback[0]);
				$this->arrEvents = $this->{$callback[0]}->{$callback[1]}($this->arrEvents, $arrCalendars, $intStart, $intEnd, $this);
			}
		}

		return $this->arrEvents;
	}


}

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


 class EventsHighlight extends \Controller
 {

   public function getFeaturedEvents($arrEvents, $arrCalendars, $intStart, $intEnd, $objModule) {

     // Only show events accordingly to moule featured settings
 		foreach ($arrEvents as $key=>$days)
 		{
 			foreach ($days as $day=>$events)
 			{
 				foreach ($events as $arrCol=>$event)
 				{
          switch ($objModule->events_featured) {
            case 'featured_events':
              if ($event[featured] != 1) {
                // nicht gefeatured Events löschen
                unset($arrEvents[$key][$day][$arrCol]);
              }
              break;

            case 'unfeatured_events':
              if ($event[featured] == 1) {
                // gefeatured Events löschen
                unset($arrEvents[$key][$day][$arrCol]);
              }
              break;

            case 'all_events':
              // do nothing
              break;

            default:
              // do nothing
              break;
          }
 				}
 			}
 		}

    return $arrEvents;
   }
 }

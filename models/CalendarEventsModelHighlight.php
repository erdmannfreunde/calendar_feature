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



 class CalendarEventsModelHighlight extends \CalendarEventsModel
 {
   /**
    * Find events of the current period by their parent ID
    *
    * @param integer $intPid     The calendar ID
    * @param integer $intStart   The start date as Unix timestamp
    * @param integer $intEnd     The end date as Unix timestamp
    * @param array   $arrOptions An optional options array
    *
    * @return \Model\Collection|\CalendarEventsModel[]|\CalendarEventsModel|null A collection of models or null if there are no events
    */
   public static function findCurrentByPid($intPid, $intStart, $intEnd, array $arrOptions=array(), $settingFeatured)
   {
     $t = static::$strTable;
     $intStart = intval($intStart);
     $intEnd = intval($intEnd);

     switch ($settingFeatured) {
       case 'featured_events':
         $SQLfeatured = ' AND '.$t.'.featured="1"';
         break;

       case 'unfeatured_events':
         $SQLfeatured = ' AND '.$t.'.featured!="1"';
         break;

       case 'all_events':
         $SQLfeatured = '';
         break;

       default:
         $SQLfeatured = '';
         break;
     }

     $arrColumns = array("$t.pid=? $SQLfeatured AND (($t.startTime>=$intStart AND $t.startTime<=$intEnd) OR ($t.endTime>=$intStart AND $t.endTime<=$intEnd) OR ($t.startTime<=$intStart AND $t.endTime>=$intEnd) OR ($t.recurring='1' AND ($t.recurrences=0 OR $t.repeatEnd>=$intStart) AND $t.startTime<=$intEnd))");

     if (!BE_USER_LOGGED_IN)
     {
       $time = \Date::floorToMinute();
       $arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
     }

     if (!isset($arrOptions['order']))
     {
       $arrOptions['order']  = "$t.startTime";
     }

     return static::findBy($arrColumns, $intPid, $arrOptions);
   }
 }

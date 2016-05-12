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



/**
 * HOOKS
 *
 */
 $GLOBALS['TL_HOOKS']['getAllEvents'][] = array('EventsHighlight', 'getFeaturedEvents');

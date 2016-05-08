<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Contao\EventsHighlight'          => 'system/modules/calendar_highlight/classes/EventsHighlight.php',

	// Models
	'CalendarEventsModelHighlight'    => 'system/modules/calendar_highlight/models/CalendarEventsModelHighlight.php',

	// Modules
	'Contao\ModuleEventlistHighlight' => 'system/modules/calendar_highlight/modules/ModuleEventlistHighlight.php',
));

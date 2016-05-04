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
	'Contao\EventsHighlight'          => 'system/modules/calendar_hightlight/classes/EventsHighlight.php',

	// Models
	'CalendarEventsModelHighlight'    => 'system/modules/calendar_hightlight/models/CalendarEventsModelHighlight.php',

	// Modules
	'Contao\ModuleEventlistHighlight' => 'system/modules/calendar_hightlight/modules/ModuleEventlistHighlight.php',
));

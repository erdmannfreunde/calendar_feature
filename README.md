# calendar_feature
Mit der Erweiterung **calendar_feature** kannst du Events – so wie du es von den News kennst – hervorheben.

Im Frontend-Modul kannst du außerdem festlegen, ob du alle, nur hervorgehobene, oder alle nicht hervorgehobenen Events anzeigen möchtest.

*Die Erweiterung wurde von [riesewebdesign](http://www.riese-webdesign.at/) beauftragt und finanziert.*

## Migration zu Core-Einstellungen

Seit Contao 4.10 sind die hervorgehobenen Events Bestandteil des Contao-Core, sodass diese Erweiterung nicht mehr benötigt wird. 
Zur Migration für die Konfiguration der Ereignismodule gibt es die Erweiterung [bwein-net/contao-migrate-calendar-feature](https://packagist.org/packages/bwein-net/contao-migrate-calendar-feature), die man ab Contao 4.10 installieren kann:

```
composer require bwein-net/contao-migrate-calendar-feature
```

Nach der Migration der Einstellungen über die Konsole oder das Install-Tool können beide Erweiterungen gefahrlos deinstalliert werden:

```
composer remove bwein-net/contao-migrate-calendar-feature
composer remove erdmannfreunde/calendar_feature
```

# PlayerSettingsManager

### Register new player settings

```php
use HQGames\forms\elements\Toggle;
use HQGames\player\settings\PlayerSettingsEntry;
use HQGames\player\settings\PlayerSettingsManager;

class Plugin extends PluginBase {
	public function onEnable() {
		$this->egisterSettingsEntries();	
	}
	
	public function registerSettingsEntries(): void{
		$element = new Toggle("%forms.playersettings.enable_something", $default=false);
		$entryForPermission = new PlayerSettingsEntry("controller_name", $element, $default, "hqgames.playersettings.enable_something");
		$entryForAll = new PlayerSettingsEntry("controller_name", $element, $default, null);
		
		PlayerSettingsManager::registerSettingsEntry($entryForPermission);
		$this->registerSettingsEntry($entryForAll);
	}
}
```
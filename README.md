# Bridge

### How to register permissions?
```php
class MyPermission extends Permission{
	const COMMAND_MY_PERMISSION = 'command.my-permission';
}
class Main extends \pocketmine\plugin\PluginBase{
	public function onEnable(){
		MyPermissions::register();
	}
}
```

<a href='https://ko-fi.com/xx_arox' target='_blank'><img height='36' style='border:0px;height:36px;' src='https://cdn.ko-fi.com/cdn/kofi3.png?v=3' border='0' alt='Buy Me a Coffee' /></a>

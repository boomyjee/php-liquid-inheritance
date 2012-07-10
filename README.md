# php-liquid-inheritance

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](./LICENSE) ![PHP version](https://img.shields.io/badge/php-%3E%3D5.3.9-blue)
![Awesome](https://camo.githubusercontent.com/fef0a78bf2b1b477ba227914e3eff273d9b9713d/68747470733a2f2f696d672e736869656c64732e696f2f62616467652f617765736f6d652533462d796573212d627269676874677265656e2e737667)
![Vulnerabilities](https://img.shields.io/badge/vulnerabilities-0-brightgreen)

Php port for https://github.com/danwrong/liquid-inheritance.
It adds Django-style template inheritance to the Liquid templating language.

## Usage
- include `Liquid` library into your app
- include `LiquidTagBlock.php` and `LiquidTagExtends.php` into your app
- create new instance of `Liquid`
- register new tags (`LiquidTagBlock` and `LiquidTagExtends`)
- parse template file and render it

## Example
```php
require_once('./Liquid.class.php');
require_once('../LiquidTagBlock.php');
require_once('../LiquidTagExtends.php');

define('BASE_PATH', dirname(__FILE__));

$liquid = new LiquidTemplate(BASE_PATH . '/templates/');
$liquid->registerTag('title','LiquidTagBlock');
$liquid->parse(file_get_contents(BASE_PATH.'/templates/default.tpl'));

echo $liquid->render();
```

Full example you can find in the `demo` folder.

### License

Plugin is [MIT licensed](./LICENSE).

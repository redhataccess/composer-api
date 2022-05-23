## CONTENTS OF THIS FILE

* Introduction
* Requirements
* Installation
* Configuration
* Usage
  * Example


## INTRODUCTION

The Composer API package provides utilities for using Composer from PHP scripts.

[![Total Downloads](https://poser.pugx.org/redhataccess/composer-api/downloads)](https://packagist.org/packages/redhataccess/composer-api)
[![Monthly Downloads](https://poser.pugx.org/redhataccess/composer-api/d/monthly)](https://packagist.org/packages/redhataccess/composer-api)
[![License](https://poser.pugx.org/redhataccess/composer-api/license)](https://packagist.org/packages/redhataccess/composer-api)


## REQUIREMENTS

This package has no requirements outside of Composer.


## INSTALLATION

* Install as you would normally install a Composer package. Visit
  https://getcomposer.org/doc/00-intro.md for further information.


## CONFIGURATION

The package has no configurable settings. Installing or uninstalling it should
have no side effects.


## USAGE

Initialization:
```
$composer = Composer::getInstance('path/to/root/composer.json', 'path/to/root');
```

Returns `Composer\Composer` object:
```
$composer::getComposer();
```

Returns array with `PackageInterface` objects (array with all installed packages, without bower, npm, etc from `fxp/composer-asset-plugin`):
```
$composer::getLocalPackages();
```

Find package by full name and version:
```
$composer::findPackage($name, $version);
```

Find package by string:
```
$composer::searchPackage($query);
```

Update package by name or all packages with [console options](https://getcomposer.org/doc/03-cli.md#update):
```
$composer::updatePackage($name, $options);
$composer::updateAllPackages($options);
```

Delete package by name or all packages with [console options](https://getcomposer.org/doc/03-cli.md#remove):
```
$composer::deletePackage($name, $options);
$composer::deleteAllPackages($options);
```

Run any composer [commands](https://getcomposer.org/doc/03-cli.md):
```
$composer::runCommand($command, $options);
```


### Example

You can see the work of the component on the example of yii2 module - [bookin/yii2-composer-gui](https://github.com/bookin/yii2-composer-gui)

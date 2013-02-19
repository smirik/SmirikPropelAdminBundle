Getting started
===============

SmirikPropelAdminBundle is a missing admin generator for Symfony2. It allows to create config-based flexible administrative tool for any propel data. It works "out of the box". You only need to install it & run the console command.

## Prerequisites

* Symfony 2.1+
* PHP 5.3+
* Bootstrap is not required but highly recommended

### Translations

All text data used in the bundle are translatable. Use translation files to provide custom translations.

### Step 1: Download SmirikPropelAdminBundle via composer

Add SmirikPropelAdminBundle in your `composer.json`:

```js
{
    "require": {
        "smirik/propel-admin-bundle": "*"
    }
}
```

Update vendors:

``` bash
$ php composer.phar update smirik/propel-admin-bundle
```

### Step 2: Enable the bundle

Enable this bundle in the kernel:

``` php
<?php
/** app/AppKernel.php */

public function registerBundles()
{
    $bundles = array(
        // ...
        new Smirik\PropelAdminBundle\SmirikPropelAdminBundle(),
    );
}
```
### That's all!

Clear cache & run console command to generate your first admin page:

``` bash
$ php app/console propeladmin:generate
```

### Additional

It is highly recommended to install `smirik/bootstrap-bundle` or add `bootstrap.css` and `bootstrap.js` to the specified layout because a lot of actions depends on this lib.

### See also

- [More about console generator](generator.md)
- [Advanced configuration](configure.md)
- [Deal with relations](builders.md)
- [How to create own action or column](builders.md)

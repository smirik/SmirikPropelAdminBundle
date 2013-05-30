Getting started
===============

SmirikPropelAdminBundle is a missing admin generator for Symfony2. It allows to create config-based flexible administrative tool for any propel data. It works "out of the box". You only need to install it & run the console command.

## Prerequisites

* Symfony 2.1+
* PHP 5.3+
* Bootstrap is not required but highly recommended

### Translations

All text data used in the bundle are translatable. Use translation files to provide custom translations.


## Installation
### Step 1: Download SmirikPropelAdminBundle via composer

Add SmirikPropelAdminBundle in your `composer.json`:

```js
{
    "require": {
        "smirik/propel-admin-bundle": "dev-master"
    }
}
```

or via CLI:

``` bash
$ php composer.phar require smirik/propel-admin-bundle
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

### Step 3: Configuration

Add `SmirikPropelAdminBundle` to assetics in `config.yml` and activate form theming from SmirikBootstrapBundle:
``` yaml
...
assetic:
    ...
    bundles: [ ..., "SmirikBootstrapBundle", "SmirikPropelAdminBundle" ]
...
twig:
    ...
    strict_variables: %kernel.debug%
    form:
      resources:
        - 'SmirikBootstrapBundle:Form:fields.html.twig'

```


### That's all!

Clear cache & run console command to generate your first admin page:

``` bash
$ php app/console propeladmin:generate
```

### Additional

It is highly recommended to install `smirik/bootstrap-bundle` or add `bootstrap.css` and `bootstrap.js` to the specified layout because a lot of actions depends on this lib.

### Screenshots

![Demo list view](https://github.com/smirik/SmirikPropelAdminDemo/blob/master/src/Smirik/PropelAdminDemoBundle/Resources/doc/demo-01.png?raw=true "Demo table view")

Other demo screenshots:

* [Filtering the results](https://github.com/smirik/SmirikPropelAdminDemo/tree/master/src/Smirik/PropelAdminDemoBundle/Resources/doc/demo-02.png)
* [Edit form](https://github.com/smirik/SmirikPropelAdminDemo/tree/master/src/Smirik/PropelAdminDemoBundle/Resources/doc/demo-03.png)

### See also

- [More about console generator](generator.md)
- [Advanced configuration](configure.md)
- [Deal with relations](relations.md)
- [How to create own action or column](builders.md)
- [How to handle file uploads](upload.md)

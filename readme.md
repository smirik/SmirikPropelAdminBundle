SmirikPropelAdminBundle
=======================

[![Build Status](https://travis-ci.org/smirik/SmirikPropelAdminBundle.png)](https://travis-ci.org/smirik/SmirikPropelAdminBundle)

SmirikPropelAdminBundle is a missing admin generator for Symfony2. It allows to create config-based flexible administrative tool for any propel data. It works "out of the box". You only need to install it & run the console command.

Features:

- Console "out of the box" creating admin tool.
- Overriding any templates and/or actions via config or inheritance.
- Admin tools for any bundle even external like FOSUserBundle.

### Reminder

If you are using `smirik/propel-admin-bundle` since version `1.0`, please use `1.0.x` branch for compability issues.

### Changelog

- [2013-08-08]: New publish/unpublish standard action. Major refactoring. Update documentation. 
- [2013-08-07]: Branch to 1.0.x for support. 
- [2013-05-30]: Update documentation for file-based fields & related stuff.
- [2013-05-30]: Add support for file-upload fields.


### Documentation

Check [Resources/doc/index.md](Resources/doc/index.md) for the documentation.

### Getting started

All instructions are in [documentation](https://github.com/smirik/SmirikPropelAdminBundle/tree/master/Resources/doc/index.md).

### Demo

To see the functionality in action you can clone sandbox: [SmirikPropelAdminDemoBundle](https://github.com/smirik/SmirikPropelAdminDemo). It is already configured project with 2 models (Category and Page) & generated PropelAdmin classes & configs.

### Contribution & Tests

To run test suite install dependencies:
``` bash
php composer.phar install
```

and run `phpspec` test suite:

``` bash
bin/phpspec run
```

Any contribution & pull requests are welcome. `git-flow` is used for this project, that's why use `develop` branch for new features. `master` branch is used only for hotfixes & stable releases.

### License

MIT. Complete License is available in [Resources/meta/LICENSE](Resources/meta/LICENSE)


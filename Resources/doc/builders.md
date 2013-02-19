Creating builder
================

SmirikPropelAdminBundle allows to create new actions & columns easily via Dependency Injection. First of all take a look for standard actions and builders. Let's create new custom action.

Generally there are several steps:

- Create new class implenting `Smirik\PropelAdminBundle\Action\ActionInterface`. You may also extends `Smirik\PropelAdminBundle\Action\Action` abstract class to get all attributes & getters & setters.
- Create builder class implementing `Smirik\PropelAdminBundle\Builder\Action\ActionBuilderInterface`.
- Setup builder as service with tag `smirik_propel_admin.action.builder` & alias.
- Create & specify custom options.

#### Step 1. Creating new class

Let's create `CustomAction` (type: object) with standard data. It will look as simple link, not button. Of course there is another way just to override template for given attribute, but let's see creation *in action*:

``` php
<?php

use Smirik\PropelAdminBundle\Action\ObjectAction;

namespace Acme\DemoBundle\Action;

class CustomAction extends ObjectAction
{

    protected $template = 'AcmeDemoBundle:Admin/Action:custom.html.twig';

    public function getAlias()
    {
        return 'custom';
    }
    
    public function getType()
    {
        return 'object';
    }

}
```

We extend `ObjectAction` class to get all attributes we need in. It has already implemented `ActionInterface`.

#### Step 2. Creating builder class

``` php
<?php

namespace Acme\DemoBundle\Builder\Action;

use Acme\DemoBundle\Action\CustomAction;

class CustomActionBuilder implements ActionBuilderInterface
{

    public function create($options)
    {
        $action = new CustomAction();
        $action->setup($options);
        return $action;
    }

}
```

#### Step 3.  Setup as service

Add to `services.yml` in `AcmeDemoBundle`.

```
admin.custom.action.builder:
    class: Acme\DemoBundle\Builder\Action\CustomActionBuilder
    tags:
        - { name: smirik_propel_admin.action.builder, alias: custom }
```

#### Step 4. Setup config:

Add to `PropelAdmin/Admin*Controller.yml` custom declaration:
``` yaml
actions:
...
    my:
        label:   My Action
        name:    my
        route:   admin_test_my
        builder: custom
        options:
            route_with_id: true
            confirmation:  false
```

That's it! Full custom declaration of new action is available in `AjaxObjectAction.php` and `AjaxObjectActionBuilder.php`. Look at code & create your own builder.


Chain based actions
=======================

PropelAdminBundle has already the functionality for `status` field: active / inactive or unreviewed / approved / rejected e.t.c. The simple configuration is:

``` yaml
actions:
    ...
    is_active:
        route:   admin_%controller%_enable
        extends: is_active
    ...
```

That's it. Standard enable action is already activated by `propeladmin:generate:routing` command. This configuration will work only for simple boolean field called `is_active`. If you wish to set up custom field, use the full declaration:

``` yaml
custom_field:
    label:   Custom Field
    name:    custom_field
    builder: chain
    options:
        route_with_id: true
        confirmation: false
    getter: getIsActive
    setter: setIsActive
    data:
        - { key: 0, text: 'Status0' }
        - { key: 1, text: 'Status1' }
```

All configs except confirmation are required. It is required to use autoincrement keys starting from 0. You may use any integer but then you have to write a bijective transformer.

### Standard action: chain

You may use standard *chain* action to provide chain functionality for any number of fields. It requires full declaration of action as written above. You don't need to create new action in controller or declare new routing in `routing.yml`. It is already built in.

### How it works

The `chain` action provides *chain functionality*. It means that it will update the row from 0 to the last value specified in *data* section of configs. Of course you can specify not only 2 possible statuses but as many as you need, e.g.:

``` yaml
custom_field:
    data:
        - { key: 0, text: 'Status0' }
        - { key: 1, text: 'Status1' }
        - { key: 2, text: 'Status2' }
        - { key: 3, text: 'Status3' }
```

When you press on button with status *Status0*, the enable-action will update the object to the next status *Status1*. When you press button with last status (3 in example above) the counter will flush to 0. 

You may use also text statuses if you really need. But then you have to declare custom setter & getter:

``` php
<?php

class MyModel extends BaseMyModel
{
    
    public function setCustomStatus($status_code)
    {
        if (0 == $status_code) {
            $this->setStatus('enabled');
        } else {
            $this->setStatus('disabled');
        }
    }
    
    public function getCustomStatus()
    {
        $status = $this->getStatus();
        if ('enabled' == $status) {
            return 1;
        }
        return 0;
    }
    
}
```

``` yaml
...
    getter: getCustomStatus
    setter: setCustomStatus
    data:
        - { key: 0, text: 'Status0' }
        - { key: 1, text: 'Status1' }

```

### See also

- [More about console generator](generator.md)
- [Advanced configuration](configure.md)
- [Deal with relations](relations.md)
- [How to create own action or column](builders.md)
- [How to handle file uploads](upload.md)

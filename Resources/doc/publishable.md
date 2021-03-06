Publishable out of the box
=======================

There is a great [Publishable behavior](https://github.com/willdurand/PublishableBehavior) for Propel. It allows you to add publish/unpublish objects via `publishable` behavior. `PropelAdminBundle` supports this behavior out of the box. There are several steps how to setup it.

### Step 0. Behavior

Check that you have already implemented `publishable` behavior. Your model should have `publish` and `unpublish` methods. 

### Step 1. Yaml configuration

If your column is called `is\_active` you can just extends standard `publish` template:

``` yaml
actions:
    ...
    is_active:
        route:   admin_%controller%_enable
        extends: publish
    ...
```

Full declaration is:

``` yaml

label:   Publish
name:    is_active
builder: publish
options:
    route_with_id: true
    confirmation: false
    template: SmirikPropelAdminBundle:Admin/Action:publish.html.twig
    filter: SmirikPropelAdminBundle:Admin/Action/Filter:publish.html.twig 
data:
    - { key: 0, text: '<i class="icon-plus"></i>' }
    - { key: 1, text: '<i class="icon-minus"></i>' }
```

You can easily change text (icons) on the button or replace name of column (`name`) in the database.

### Step 2. Enable routing

Add to your `routing.yml`


``` yaml
admin_%mcontroller_name%_publish:
  pattern: /%route_for_controller%/publish
  defaults: { _controller: AcmeDemoBundle:DefaultController:Publish }
```

Publish is a standard action. That's why it is already enabled if you have used console build command.

That's it. Now it will work out of the box including filtering. If your model has more statuses (like unreviewed, approved, rejected) then you can use [chain](chain.md) action builder.

### See also

- [Index](index.md)
- [More about console generator](generator.md)
- [Advanced configuration](configure.md)
- [Deal with relations](relations.md)
- [How to create own action or column](builders.md)
- [How to handle file uploads](upload.md)
- [AJAX chain builder](chain.md)
- [Publish & unpublish action](publish.md)
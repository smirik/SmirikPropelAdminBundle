Advanced configuration
======================

## Column configuration

#### Use build-in columns declarations

A lot of models have the same attributes such as `id, name, is_active` e.t.c. For this purposes SmirikPropelAdminBundle supports column extending:

```
is_active:
    extends: is_active
```

Yeah, that's all. It adds column with the parameters below:

``` yaml
is_active:
    label:   Is active
    name:    is_active
    type:    boolean
    builder: simple
    options:
        listable: true
        editable: true
        sortable: true
        filterable: true
```

For now it is supported:

* id
* name
* title
* is\_active
* file
* image
* default

Of course you can override any data, e.g. name:

``` yaml
is_active:
    extends: is_active
    label:   Enabled
```

#### Overriding column's templates

Each column has own template based on type. E.g. boolean columns have 2 templates: 1th for filtering the results (dropdown yes/no) and 2th for listing (OK icon or FAIL icon). You can specify in config the template for each column:

``` yaml
is_active:
    extends: is_active
    label:   Enabled
    templates: 
        filter: 'AcmeDemoBundle:Admin/Column:custom-filter.html.twig'
        list:   'AcmeDemoBundle:Admin/Column:custom-list.html.twig'
```

## Action configuration

#### General
Generally action's configuration is pretty the same as written above. There are 3 built-in actions: `create, edit, delete`.

``` yaml
create:
    route:   admin_tests_new
    extends: create
edit:
    route:   admin_tests_edit
    extends: edit
delete:
    route:   admin_tests_delete
    extends: delete
```

The full list of options depends on [builder](builders.md). For example, object builder has the following configuration:

``` yaml
assign:
    label:   Assign
    name:    assign
    route:   admin_test_assign
    builder: object
    options:
        route_with_id: true
        confirmation: true
```

All parameters except `confirmation` are required.

#### Pre-defined builders

For more information about builders look in [documentation](builders.md)

SmirikPropelAdminBundle provides 3 standard action builders:

* single. It allows to create single action such as `create`. It does not have `object_id`, it is only a link based on provided `route`.
* object. It creates action based on `route` and current `object_id`. It adds action to each object in the table view.
* ajax_object. Based on object action. It creates ajax action for each object & opens modal window with the content provided by `route` response. *Works in test mode*.
* chain. Allows to create chain-based actions with several statuses.


#### Chain action

See the [documentation](chain.md)

#### Overriding templates

Action has one template related to list view in table. You can override it providing `template` attribute in config file. Each action builder type has default view located in `Resources/view/Admin/Action`.

## Grid configuration

SmirikPropelAdminBundle provides flexible way to override any template using by `DataGrid`. Here is default configuration:

``` yaml
columns:
    ...
actions:
    ...
templates:
    form
        edit:   SmirikPropelAdminBundle:Admin/Form:edit.html.twig
        new:    SmirikPropelAdminBundle:Admin/Form:new.html.twig
        fields: SmirikPropelAdminBundle:Admin/Form:fields.html.twig
    list: array(
        mass_actions:   SmirikPropelAdminBundle:Admin/List:mass_actions.html.twig
        paginate:       SmirikPropelAdminBundle:Admin/List:paginate.html.twig
        single_actions: SmirikPropelAdminBundle:Admin/List:single_actions.html.twig
        table_filters:  SmirikPropelAdminBundle:Admin/List:table_filters.html.twig
        table_header:   SmirikPropelAdminBundle:Admin/List:table_header.html.twig
    index:         SmirikPropelAdminBundle:Admin:index.html.twig
    index_content: SmirikPropelAdminBundle:Admin:index_content.html.twig
    row:           SmirikPropelAdminBundle:Admin:row.html.twig
```

You can override any template listed above. The default content is in `Resources/views/Admin` folder.

### See also

- [More about console generator](generator.md)
- [Advanced configuration](configure.md)
- [Deal with relations](relations.md)
- [How to create own action or column](builders.md)
- [How to handle file uploads](upload.md)

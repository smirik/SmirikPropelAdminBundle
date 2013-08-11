Upgrade to 1.1.x from 1.0.x
===========================

### Controller yaml config

* Action `create` was renamed to `new`:

**New version**
``` yaml
new:
    route: admin_entity_new
    extends: new
```

Old version
``` yaml
create:
    route: admin_entity_new
    extends: create
```

* `ajax_object` action was removed.
* `ajax_flag` action was renamed to `chain`.
* All console command were merged into one: `propel:admin:build`.
* Console command option `model_prefix` was renamed to `url_prefix`.
* If you have extended standard `AbstractAdminController` actions (like `index`, `edit` e.t.c.), please check current implementation. It was changed due to refactoring. E.g. `grid` object moved to independent service instead of property.
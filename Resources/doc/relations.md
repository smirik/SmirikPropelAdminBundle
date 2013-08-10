How to add relation column
==========================

Let we have `Comment` model & related `user_id` field — foreign key to fos_user table. It is really simple to create this relation in `SmirikPropelAdminBundle`.

#### Step 1. Configure.

```
columns:
...
    user_id:
        label:   User
        name:    user
        extends: default
```

> You may also use [console generator](generator.md) to fill configs.

We use `user` as name for the column. It works because `Comment` model has methods `getUser()` and `setUser()`. If you don't have these methods in your model, just add them to `Comment`.

#### Step 2. Configure Form

`propeladmin:generate:controller` command does not work with object fields for now. That's why you have to setup model type in Form/Type class manually. Replace `user` declaration in `Form/Type/CommentType.php` to:

``` php
->add('user', 'model', array(
    'class' => 'FOS\UserBundle\Propel\User',
))
```

That's it! Now Create & Edit form works fine.

#### Step 3. Ordering & Filtering

If you want to add sort & filter functionality to `user` field, you have to create 2 methods in `CommentQuery` class:

``` php
public function filterByUser($text, $scope = null)
{
	return $this
		->useUserQuery()
			->filterByUsername($text, $scope)
		->endUse();
}
	
public function orderByUser($order)
{
	return $this
		->useUserQuery()
			->orderByUsername($order)
		->endUse();
}
```

#### Step 4. Customization

If you want to customize list view of `User`, use [template overriding](configure.md).

#### Collections

`SmirikPropelAdminBundle` supports one-to-many relations. For this purposes there is `CollectionColumn` class. You have to specify them in config or use type `collection` in [console generator command](generator.md):

``` yaml
comments:
    label: Comments
    name: comments
    type: collection
    builder: simple
    options:
        listable: true
        editable: true
        sortable: true
        filterable: true
```

> You have to check that `Comment` class has mehods `getComments`.

### See also

- [Index](index.md)
- [More about console generator](generator.md)
- [Advanced configuration](configure.md)
- [Deal with relations](relations.md)
- [How to create own action or column](builders.md)
- [How to handle file uploads](upload.md)
- [AJAX chain builder](chain.md)
- [Publish & unpublish action](publish.md)

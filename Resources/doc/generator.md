Console generator
===============

Console generator is a powerful tool to create new AdminController & general settings. It will create the bundle you have specified the following directories:

- Resources/config/PropelAdmin
- Controller/Base
- Form/Type
- Form/Type/Base

Also one-time task creates the files below:

- Resources/config/PropelAdmin/AdminDemoController.yml
- Controller/Base/AdminDemoController.php
- Controller/AdminDemoController.php (if not exists)
- Form/Type/Base/DemoType.php
- Form/Type/DemoType.php (if not exists)
- Resources/config/routing.yml (separate task)


### propeladmin:generate:controller

There are 2 different options: 

* You can specify parameters as options in command line.
* You can use build-in dialogs to provide the same parameters. Most parameters are pre-installed, so you may just press enter if the settings are OK.

Also this task consists of 3 steps:

* general data configuration,
* columns & properties^
* action's configuration.

Let's consider AcmeDemoBundle. We will create admin panel for users from FOSUserBundle:

##### Command with all options
```bash
php app/console propeladmin:generate:controller --controller=FOS/UserBundle --model_name=User --model=FOS/UserBundle/Propel/User --query=FOS/UserBundle/Propel/UserQuery --form=Acme/DemoBundle/Form/Type/UserType --admin_prefix=admin --model_prefix=users --layout=AcmeDemoBundle:Admin:layout.html.twig
```

##### Detailed description

AcmeDemoBundle, Test model.

Option | Description | Sample | Default 
--- | --- | --- | ---
controller | Place where to store controller class | Acme/DemoBundle | not specified
model\_name | Name of propel model | Test | not specified
model | Full model class with namespace | Acme/DemoBundle/Model/Test | based on model\_name
query | Full model query class with namespace | Acme/DemoBundle/Model/TestQuery | based on model
form | Full form type class with namespace | Acme/DemoBundle/Form/Type/TestType | based on model
admin\_prefix | Prefix for admin URLs in controller | admin | admin
model\_prefix | Prefix for current model | tests | not specified
layout | Prefix for current model | AcmeDemoBundle:Admin:layout.html.twig | based on controller + Admin folder + layout.html.twig

##### Column's creation

The generator will show you all avaliable columns according to \*TableMap class. You may use any name for the column but you have to create getter & setter for this name. For each column you have to provide:

Option | Description
--- | ---
name | name used for getter & setter (generally, it's the same as in DB)
label | name of the column to show in templates
type | column's type. Now are supported: `string, integer, text, boolean, date, collection`. For object-type columns (for relations) use `string`.
listable | show in the table view
editable | add to form type class & edit form
sortable | add sorting by current column
filterable | add filtering for current column. Filtering is be based also on column's type.

If you want to stop adding columns just press `<return>`

##### Actions

On the last you can activate standard actions: `create, edit, delete`. Create & Edit actions are based on Form/Type class created previously.

##### Final words

Files are created in the end of the command. So you can stop task by pressing `ctrl + c` any time.

After creation you may modify any created files except `Base/*`. If you modify the Database, you can just add configs to the `PropelAdmin/*.yml` file. Also it is possible to create all files manually because of simplicity of configs.

*See also*:

- [More about console generator](generator.md)
- [Advanced configuration](configure.md)
- [Deal with relations](relations.md)
- [How to create own action or column](builders.md)

File & image uploading
======================

PropelAdminBundle supports file & image uploading due to standard FileColumn. 

#### Files

Open your yaml configuration file (e.g. AdminDemoController.yml). Short declaration for file type is:

``` yaml
file:
    extends: file
```

Full list of options:

``` yaml
label: File
name:  file
type:  file
builder: file
options:
    listable:   true
    editable:   true
    sortable:   false
    filterable: false
    upload_path: /uploads/documents/
    randomize_name: true
```

You may specify any path behind your web\_root directory.

In your FormType class specify file field with applied `FileToTextTransformer`:

``` php
// src/Acme/DemoBundle/Form/Type/Admin/ModelType.php
...
$builder->add($builder->create('file', 'file', array('required' => false))->prependNormTransformer(new \Smirik\PropelAdminBundle\Form\DataTransformer\FileToTextTransformer()))
```

That's it. You can also create several file-based fields.


#### Images

Images management is pretty the same. Specify `image` type in your yaml file (you may also use full declaration):
``` yaml
image:
    extends: image
```

Form declaration is the same, the only difference is in views.

*See also*:

- [More about console generator](generator.md)
- [Advanced configuration](configure.md)
- [Deal with relations](relations.md)
- [How to create own action or column](builders.md)
- [How to handle file uploads](upload.md)
- [AJAX chain builder](chain.md)
- [Publish & unpublish action](publish.md)

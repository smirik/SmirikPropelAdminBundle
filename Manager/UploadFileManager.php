<?php
    
namespace Smirik\PropelAdminBundle\Manager;

class UploadFileManager extends \Symfony\Component\DependencyInjection\ContainerAware
{
    
    public function getDefaultValues($file_columns, $item)
    {
        $values = array();
        foreach ($file_columns as $column) {
            $values[$column->getName()] = $column->getValue($item);
        }
        return $values;
    }
    
    public function uploadFiles($form, $file_columns, $item, $default_values)
    {
        foreach ($file_columns as $column)
        {
            $elem = $form[$column->getName()]->getData();
            
            $getter = 'get'.$column->getGetter();
            $setter = 'set'.$column->getGetter();
            
            if ($elem instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {

                $new = strtolower($item->getId() . '_' . $elem->getClientOriginalName());
                if ($column->randomizeName()) {
                    $new = time().'_'.$new;
                }
                $elem->move($this->container->get('kernel')->getRootDir() . '/../web'.$column->getUploadPath(), $new);
                $item->{$setter}($new);

            } elseif (isset($form['delete_'.$column->getName()])) {
                $item->{$setter}(null);
            } elseif (isset($default_values[$column->getName()])) {
                $item->{$setter}($default_values[$column->getName()]);
            }
        }
        
    }
    
}
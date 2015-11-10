<?php
    
namespace Smirik\PropelAdminBundle\Manager;

class UploadFileManager extends \Symfony\Component\DependencyInjection\ContainerAware
{
    
    /**
     * Get default values for all uploaded files from database & returns hash with data.
     * @param array $file_columns
     * @param mixed $item
     * @return array
     */
    public function getDefaultValues($file_columns, $item)
    {
        $values = array();
        foreach ($file_columns as $column) {
            $values[$column->getName()] = $column->getValue($item);
        }
        return $values;
    }
    
    /**
     * Upload files into upload_path dir specified in options. If no file or file was not changed -> put default value.
     * @todo Make it more clear
     * @param Symfony\Component\Form\AbstractType $form
     * @param array $file_columns
     * @param mixed $item
     * @param array $default_values
     * @return void
     */
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

            } elseif (isset($form['delete_' . $column->getName()]  && 
                ($form['delete_' . $column->getName()]->getData() === true)
            ) {
				$current_file = $this->container->get('kernel')->getRootDir() . '/../web'.$column->getUploadPath().$item->{$getter}();
				if (file_exists($current_file) === true ) {
					unlink($current_file);
				}
                $item->{$setter}(null);
            } elseif (isset($default_values[$column->getName()])) {
                $item->{$setter}($default_values[$column->getName()]);
            }
        }
        
    }
    
}

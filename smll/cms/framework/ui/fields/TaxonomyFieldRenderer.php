<?php
namespace smll\cms\framework\ui\fields;

use smll\cms\framework\ui\fields\interfaces\IFieldRenderer;

class TaxonomyFieldRenderer extends FieldRenderer
{
    public function render()
    {
        $output = '<select multiple="multiple" name="'.$this->getFieldName().'[]" id="input-'.$this->getFieldName().'">';
        foreach ($this->getOptions()->getIterator() as $id => $term) {
            $active = false;
            if (is_array($this->getData())) {
                if (in_array($term, $this->getData())) {
                    $active = true;
                }
            } else {
                if ($term == $this->getData()) {
                    $active = true;
                }
            }
            $output .= '<option value="'.$id.'"';
            if ($active) {
                $output .=" selected ";
            }
            $output .= '>'.$term->getTitle().'</option>';
        }
        $output .= '</select>';
        return $output;
    }

}
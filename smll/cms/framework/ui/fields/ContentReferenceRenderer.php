<?php
namespace smll\cms\framework\ui\fields;

use smll\cms\framework\ui\fields\interfaces\IFieldRenderer;
use smll\cms\framework\ui\fields\interfaces;

/**
 *
 * @author ksdkrol
 *
 */

class ContentReferenceRenderer extends FieldRenderer
{
    public function render()
    {
         
        $output = '<div class="input-append">
                <input type="text" name="" id="'.$this->getFieldName().'-typahead" class="typahead" value="" />
                <input name="'.$this->getFieldName().'" type="hidden" value="'.$this->getData().'" />
                        <a class="btn add-on browser" href="#" data-browser="page"><i class="icon-hdd"></i></a>
                        </div>';

        return $output;
    }
}
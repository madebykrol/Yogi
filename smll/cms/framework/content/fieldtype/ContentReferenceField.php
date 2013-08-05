<?php
namespace smll\cms\framework\content\fieldtype;

/**
 *
 *
 * @author Kristoffer "mbk" Olsson
 * @version beta 1.0
 *
 * ContentReferenceField is a field processing and rendering a PageReference input
 *
 *
 * [DefaultRenderer(smll\cms\framework\ui\fields\ContentReferenceRenderer)]
 */
class ContentReferenceField extends BaseFieldType
{
    protected $dataType = "contentRef";
    
    public function __construct()
    {
    
    }
    
    public function renderField($data, $parameters = null)
    {
        /**
         * @todo Rewrite prototype code.
         */
        $output = "";
        if ($this->multifield) {
            foreach ($data as $index => $d) {
                $this->renderer->setData($d);
                $this->renderer->setValidationError($this->error);
                $this->renderer->setFieldName($this->name."[]");
                $output .= $this->renderer->render();
            }
        } else {
            $this->renderer->setData($data);
            $this->renderer->setValidationError($this->error);
            $this->renderer->setValidationError($this->error);
            $this->renderer->setFieldName($this->name);
            $output = $this->renderer->render();
        }
    
        return $output;
    }
}
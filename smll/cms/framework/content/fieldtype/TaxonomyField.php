<?php
namespace smll\cms\framework\content\fieldtype;

use smll\cms\framework\content\taxonomy\interfaces\IVocabulary;

use smll\framework\utils\HashMap;

use smll\cms\framework\content\fieldtype\interfaces\ITaxonomyFieldType;

use smll\cms\framework\content\taxonomy\interfaces\ITaxonomyRepository;

use smll\cms\framework\content\fieldtype\interfaces\IFieldSettings;

use smll\cms\framework\ui\fields\interfaces\IFieldRenderer;

use smll\cms\framework\content\fieldtype\interfaces\IFieldType;

/**
 *
 *
 * @author Kristoffer "mbk" Olsson
 * @version beta 1.0
 *
 * PageReferenceField is a field processing and rendering a PageReference input
 *
 * [DefaultRenderer(smll\cms\framework\ui\fields\TaxonomyFieldRenderer)]
 */
class TaxonomyField extends BaseFieldType implements ITaxonomyFieldType
{

    protected $dataType = "number";
    protected $taxRepo = null;
    public function setTaxonomyRepository(ITaxonomyRepository $taxRepo)
    {
        $this->taxRepo = $taxRepo;
    }

    public function renderField($data, $parameters = null)
    {

        $this->renderer->setFieldName($this->getName());
        $this->renderer->setData($data);

        $vocabulary = $this->taxRepo->getVocabularyByName($this->getFieldSettings()->get('Vocabulary'));

        if ($vocabulary instanceof IVocabulary) {
            $this->renderer->setOptions($vocabulary->getTerms());
        }
        return $this->renderer->render();
    }

    public function processData($data, $index = 0)
    {
        $vocabulary = $this->taxRepo->getVocabularyByName($this->getFieldSettings()->get('Vocabulary'));
        if (is_array($data)) {
            foreach ($data as $index => $d) {
                $data[$index] = $this->taxRepo->getTerm($d);
            }
        } else {
            $data = $this->taxRepo->getTerm($data);
        }
        return $data;
    }

}
<?php
namespace smll\cms\framework\content\fieldtype\interfaces;

use smll\cms\framework\content\taxonomy\interfaces\ITaxonomyRepository;

interface ITaxonomyFieldType extends IFieldType
{
    public function setTaxonomyRepository(ITaxonomyRepository $taxonomyRepository);
}
<?php
namespace smll\cms\models;

class VocabularyModel
{
    /**
     * [FormField]
     * [Label=Vocabulary name]
     * [InputType=text]
     * [Required]
     * @var string
     */
    public $name;

    /**
     * [FormField]
     * [Label=Description]
     * [InputType=textarea]
     * [Required]
     * @var string
     */
    public $description;
}
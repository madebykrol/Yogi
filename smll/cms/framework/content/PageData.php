<?php
namespace smll\cms\framework\content;

use smll\cms\framework\content\interfaces\IPageReference;

use smll\cms\framework\content\interfaces\IPageData;

/**
 *
 * @author ksdkrol
 *
 */
abstract class PageData implements IPageData
{

    /**
     * [Editable]
     * [ContentField(Type=Text, DisplayName=Title, Required=true, Tab=Content, Searchable=true, WeightOrder=-50)]
     */
    public $title;

    /**
     * [Editable]
     * [ContentField(Type=Boolean, DisplayName=Publish, Required=true, Tab=Settings)]
     */
    public $published;

    /**
     * [ContentField(Type=Hidden, Tab=Settings)]
     * @var unknown
     */
    public $id;
    
    /**
     * [Editable]
     * [ContentField(Type=Text, DisplayName=External URL, Required=true, Tab=Settings)]
     */
    public $externalUrl;


    /**
     * [Editable]
     * [ContentField(Type=Text, DisplayName=Author name, Required=true, Tab=Content)]
     * @var unknown
     */
    public $authorName;

    /**
     * [ContentField(Type=Hidden, Tab=Settings)]
     * @var unknown
     */
    public $ident;
    
    /**
     * [Editable]
     * [ContentField(Type=Boolean, DisplayName=Visible in menu, Required=false, Tab=Menu, WeightOrder=-100)]
     * @var unknown
     */
    public $visibleInMenu;

    /**
     * [Editable]
     * [ContentField(Type=Text, DisplayName=Order in menu tree, Required=false, Tab=Menu)]
     * @var unknown
     */
    public $peerOrderWeight;


    /**
     * [Editable]
     * [ContentField(Type=PageReference, DisplayName=Parent, Required=false, Tab=Menu)]
     * @var unknown
     */
    public $parentId;

    /**
     * [ContentField(Type=Hidden, Tab=Content, DisplayName=, Required=true)]
     * @var unknown
     */
    public $creationDate;

    private $pageReference = null;

    public function getPageReference()
    {
        return $this->pageReference;
    }

    public function setPageReference(IPageReference $reference)
    {
        $this->pageReference = $reference;
    }

}
<?php
namespace smll\cms\framework\content\utils\interfaces;

use smll\cms\framework\content\interfaces\IContentProperty;

use smll\cms\framework\content\interfaces\IPageReference;

use smll\framework\io\file\interfaces\IFileReference;

use smll\framework\utils\ArrayList;

use smll\cms\framework\content\PageData;
use smll\cms\framework\content\interfaces\IPageData;
use smll\cms\framework\content\interfaces\IPageDataField;
use smll\framework\utils\Guid;

/**
 * The Content framwork CRUD.
 * Create, Retreive, Update and Delete page types.
 * @author Kristoffer "mbk" Olsson
 *
 */
interface IPageDataRepository
{


    /**
     * Add or update a IPageData to the database.
     * @param IPageData $page
     */
    public function addPage(IPageData $page);

    /**
     * @return IPageReference
    */
    public function getRootPage();


    public function getPageReference($id);

    
    

    public function setPageParent($id, $parentId);
    
    public function setPeerOrderWeight($id, $order);

    /**
     *
     * Overloaded
     * getPage(int $id)
     * getPage(Guid $id)
     *
     * @param $id
     * @return IPageData
    */
    public function getPageData(IPageReference $page);

    /**
     *
     * Like getPage but returns a raw dataset
     * @return array
     * @param $id
    */
    public function getPageRaw($id);

    /**
     * Overloaded
     * removePage(int $id);
     * removePage(Guid $id);
     * @param unknown $id
    */
    public function removePage($id);

    /**
     * Overloaded
     * removePageType(int $id);
     * removePageType(Guid $id);
     * @param unknown $id
    */
    public function removePageType($id);

    public function setPageCrudListener(ICrudListener $listener);

    public function setPropertyForPage($pageId, IContentProperty $prop);

    public function removePropertyForPage($pageId, IContentProperty $prop);
    
    public function publishPage($id);
    public function unpublishPage($id);

}
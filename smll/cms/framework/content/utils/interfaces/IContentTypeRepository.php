<?php
namespace smll\cms\framework\content\utils\interfaces;

use smll\framework\utils\Guid;

use smll\cms\framework\content\interfaces\IPageDataField;

interface IContentTypeRepository
{
    /**
     * Return all installed content types for a certain data type
     * @param unknown $type
     */
    public function getContentTypes($dataType);
    
    /**
     * Returns all installed content types regardless of data type
     */
    public function getAllContentTypes();
    
    
    /**
     * Add or update a field to a PageType
     * These fields are representing input fields for IPageData.
     * @param int $id
     * @param IPageDataField $field
     */
    public function addContentTypeField($pageTypeId, IPageDataField $field);
    
    /**
     * Add or update a pagetype to the database
     * @param unknown $type
     * @param unknown $file
     * @param unknown $controller
     * @param unknown $name
     * @param string $description
     * @param string $guid
     */
    public function addContentType($type, $file, $controller, $name, $permissions, $description = null, Guid $guid = null);
    
    public function getContentDefinitionTypeByName($defType);
    public function getContentDefinitionByName($def,$pageTypeId);
   
    
    public function setFieldRenderer($pageType, $pageDefinitionTypeId, $renderer);
    
    
    
    /**
     * @return IFieldTypeRenderer
     * @param numeric $pageType
     * @param numeric $pageDefinitionTypeId
    */
    public function getFieldRenderer($pageType, $pageDefinitionTypeId);
    
    /**
     * Get all fields for a page type
     * @param int $id
     * @return IPageDataField
    */
    public function getContentDataFields($type);
    
    /**
     *
     * Return empty IPageData of $type;
     *
     * Overloaded
     * getPageType(string $type);
     *
     * @return IPageData
    */
    public function getContentTypeByName($type);
    
    /**
     *
     * Return empty IPageData of $type;
     *
     * Overloaded
     * getPageType(string $type);
     * getPageType(int $type);
     * getPageType(Guid $type);
     *
     * @return IPageData
    */
    public function getContentTypeId($type);
    
    
    /**
     * Get complete namespace and class for content type.
     * @param unknown $type
    */
    public function getContentTypeNamespaceClass($type);
}

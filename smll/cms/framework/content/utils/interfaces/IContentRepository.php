<?php
namespace smll\cms\framework\content\utils\interfaces;
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
interface IContentRepository {
	
	
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
	
	/**
	 * Add or update a pagetype to the database
	 * @param unknown $type
	 * @param unknown $file
	 * @param unknown $controller
	 * @param unknown $name
	 * @param string $description
	 * @param string $guid
	 */
	public function addPageType($type, $file, $controller, $name, $permissions, $description = null, Guid $guid = null);
	
	/**
	 * Add or update a field to a PageType 
	 * These fields are representing input fields for IPageData.
	 * @param int $id
	 * @param IPageDataField $field
	 */
	public function addPageTypeField($pageTypeId, IPageDataField $field);
	
	public function getPageDefinitionTypeByName($defType);
	public function getPageDefinitionByName($def,$pageTypeId);
	
	public function setPageParent($id, $parentId);
	
	public function setPeerOrderWeight($id, $order);
	
	/**
	 * Get all fields for a page type
	 * @param int $id
	 * @return IPageDataField
	 */
	public function getPageDataFields($type);
	
	/**
	 * 
	 * Return empty IPageData of $type;
	 * 
	 * Overloaded
	 * getPageType(string $type);
	 * 
	 * @return IPageData
	 */
	public function getPageTypeByName($type);
	
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
	public function getPageTypeId($type);
	
	/**
	 * Returns a array of PageTypes.
	 * @return ArrayList
	 */
	public function getPageTypes();
	
	/**
	 * Get complete namespace and class for content type.
	 * @param unknown $type
	 */
	public function getPageTypeNamespaceClass($type);
	
	/**
	 * 
	 * Overloaded 
	 * getPage(int $id)
	 * getPage(Guid $id)
	 * 
	 * @param $id
	 * @return IPageData
	 */
	public function getPage($id);
	
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
	
	public function getFileReference($ident);
	public function setFileReference(IFileReference $ref);
	public function removeFileReference($ident);
	
}
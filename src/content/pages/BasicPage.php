<?php
namespace src\content\pages;
use smll\cms\framework\content\PageData;
/**
 * @author ksdkrol
 * [Permissions(View=Anonymous|User,Edit=Editor|Administrator, Delete=Editor|Administrator)]
 * [ContentType(DisplayName=Basic page,Guid=6847184b-b515-4144-ac2f-1046945fd6e4,Description=Create a new basic page)] 
 */
class BasicPage extends PageData {
	
	/**
	 * [Editable]
	 * [ContentField(Type=XmlString, DisplayName=Body, Required=true, Tab=Content, Searchable=true)]
	 */
	public $mainBody;
	
}
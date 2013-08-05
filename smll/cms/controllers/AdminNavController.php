<?php
namespace smll\cms\controllers;

use smll\cms\framework\content\utils\interfaces\IContentTypeRepository;

use smll\framework\utils\Guid;

use smll\framework\utils\JsonConverter;
use smll\cms\models\ContentNavTreeModel;
use smll\cms\framework\content\interfaces\IPageReference;
use smll\cms\framework\ui\interfaces\IMenuItem;
use smll\cms\framework\ui\MenuItem;
use smll\cms\framework\ui\MenuTree;
use smll\cms\framework\interfaces\IPageTypeBuilder;
use smll\cms\framework\content\utils\interfaces\IPageDataRepository;
use smll\framework\utils\HashMap;
use smll\framework\mvc\Controller;
use smll\cms\models\AdminNavModel;
use smll\cms\models\EditorTool;

/**
 * [Authorize]
 * @author ksdkrol
 *
 */
class AdminNavController extends Controller
{

    private $pageDataRepository;
    private $contentTypeRepository;

    public function __construct(IPageDataRepository $repo, 
            IContentTypeRepository $contentTypeRepository)
    {
        $this->pageDataRepository = $repo;
        $this->contentTypeRepository = $contentTypeRepository;
    }

    public function topNav()
    {
        return $this->view();
    }

    public function editToolsNav($id = null)
    {
        $adminNav = new AdminNavModel();

        $editTool = new EditorTool("edit", "Pages", new HashMap(array('id' => $id)), 'icon-edit', true, false);
        if (!isset($id) && $id == "") {
            $editTool->active = false;
        }
        $adminNav->tools->add('edit', $editTool);
        
        $copyTool = new EditorTool("copy", "Pages", new HashMap(array('id' => $id)), 'icon-copy', true, false);
        if (!isset($id) && $id == "") {
            $editTool->active = false;
        }
        $adminNav->tools->add('copy', $copyTool);

        $removeTool = new EditorTool("remove", "Pages", new HashMap(array('id' => $id)), 'icon-trash', true, false);
        if (!isset($id) && $id == "") {
            $removeTool->active = false;
        }
        $adminNav->tools->add('remove', $removeTool);

        $publishTool = new EditorTool("publish", "Pages", new HashMap(array('id' => $id)), 'icon-thumbs-up', true, false);
        if (!isset($id) && $id == "") {
            $publishTool->active = false;
        }
        $adminNav->tools->add('publish', $publishTool);


        foreach ($this->contentTypeRepository->getContentTypes('PageData')->getIterator() as $pageType) {
            $adminNav->pageTypes->add($pageType->name, $pageType->displayName);
        }

        return $this->view($adminNav);
    }
    
    public function contentToolsNav($id = null)
    {
        $adminNav = new AdminNavModel();
        
        foreach ($this->contentTypeRepository->getContentTypes('PageData')->getIterator() as $pageType) {
            $adminNav->pageTypes->add($pageType->name, $pageType->displayName);
        }
    
        return $this->view($adminNav);
    }
    
    public function taxonomyToolsNav($id = null)
    {
        $adminNav = new AdminNavModel();
    
        foreach ($this->contentTypeRepository->getContentTypes('PageData')->getIterator() as $pageType) {
            $adminNav->pageTypes->add($pageType->name, $pageType->displayName);
        }
    
        return $this->view($adminNav);
    }
    
    public function blockToolsNav($id = null)
    {
        $adminNav = new AdminNavModel();
    
        foreach ($this->contentTypeRepository->getContentTypes('PageData')->getIterator() as $pageType) {
            $adminNav->pageTypes->add($pageType->name, $pageType->displayName);
        }
    
        return $this->view($adminNav);
    }

    /**
     * [InRole(Role=Editor)]
     * @param string $active
     * @return \smll\framework\mvc\ViewResult
     */
    public function editContentTreeNav($active = null)
    {
        $adminNav = new AdminNavModel();
        
        $tree = new MenuTree();
        $rootPage = $this->pageDataRepository->getRootPage();
        
        foreach ($rootPage->getChildren()->getIterator() as $index => $page) {
            $item = new MenuItem();
            $item->setTitle($page->getTitle());
            $item->setLink('display/'.$page->getIdent());
            $item->setId($page->getIdent());
            
            if ($active == $page->getIdent()) {
                $item->isActive(true);
            }

            if ($page->hasChildren()) {
                $this->recursiveMenuTreeBuilder($page, $item);
            }

            $tree->addItem($item);
        }

        return $this->view($tree);
    }
    /**
     * [InRole(Role=Editor)]
     */
    public function post_editContentTreeNav(ContentNavTreeModel $menu = null)
    {
        $parent = 0;
        if (isset($menu)) {
            if ($menu->parent != "menu-root") {
                $parent = $this->pageDataRepository->getPageReference(
                        Guid::parse(str_replace('parent-', '', $menu->parent)))
                ->getId();
            }
             
            foreach ($menu->menu as $order => $pageId) {
                $pageId = Guid::parse($pageId);
                $this->pageDataRepository->setPageParent($pageId, $parent);
                $this->pageDataRepository->setPeerOrderWeight($pageId, $order);
            }
             
        }
        return "OK";
    }

    protected function recursiveMenuTreeBuilder(
            IPageReference $page,
            IMenuItem $item, 
            $active = null) 
    {
        foreach ($page->getChildren()->getIterator() as $index => $child) {
            $cItem = new MenuItem();
            $cItem->setTitle($child->getTitle());
            $cItem->setLink('display/'.$child->getIdent());
            $cItem->setId($child->getIdent());
             
            if ($active == $child->getId()) {
                $cItem->isActive(true);
                $item->activeTrail(true);
            }
            if ($child->hasChildren()) {
                $this->recursiveMenuTreeBuilder($child, $cItem);
            }
            $item->addChild($cItem);
        }
    }

    public function adminToolsNav()
    {
        $adminNav = new AdminNavModel();

        $adminNav->pageTypes->add('GamePage', 'Game');
        $adminNav->pageTypes->add('BasicPage', 'Basic page');

        return $this->view($adminNav);
    }

    public function adminTreeNav()
    {
        $adminNav = new AdminNavModel();

        $tree = new HashMap();

        return $this->view($tree);
    }

    public function adminNav()
    {
        return $this->view();
    }
    
    public function secondLevel(MenuItem $item) {
        
        return $this->view($item);
    }

}
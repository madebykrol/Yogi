<?php
namespace smll\cms\controllers;

use smll\framework\utils\Guid;

use smll\framework\utils\JsonConverter;
use smll\cms\models\ContentNavTreeModel;
use smll\cms\framework\content\interfaces\IPageReference;
use smll\cms\framework\ui\interfaces\IMenuItem;
use smll\cms\framework\ui\MenuItem;
use smll\cms\framework\ui\MenuTree;
use smll\cms\framework\interfaces\IPageTypeBuilder;
use smll\cms\framework\content\utils\interfaces\IContentRepository;
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

    private $contentRepository;

    public function __construct(IContentRepository $repo)
    {
        $this->contentRepository = $repo;
    }

    public function topNav()
    {
        return $this->view();
    }

    public function editToolsNav($id = null)
    {
        $adminNav = new AdminNavModel();

        $editTool = new EditorTool("edit", "Content", new HashMap(array('id' => $id)), 'icon-edit', true, false);
        if (!isset($id) && $id == "") {
            $editTool->active = false;
        }
        $adminNav->tools->add('edit', $editTool);

        $removeTool = new EditorTool("remove", "Content", new HashMap(array('id' => $id)), 'icon-trash', true, false);
        if (!isset($id) && $id == "") {
            $removeTool->active = false;
        }
        $adminNav->tools->add('remove', $removeTool);

        $publishTool = new EditorTool("publish", "Content", new HashMap(array('id' => $id)), 'icon-thumbs-up', true, false);
        if (!isset($id) && $id == "") {
            $publishTool->active = false;
        }
        $adminNav->tools->add('publish', $publishTool);


        foreach ($this->contentRepository->getPageTypes()->getIterator() as $pageType) {
            $adminNav->contentTypes->add($pageType->name, $pageType->displayName);
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


        $rootPage = $this->contentRepository->getRootPage();
        foreach ($rootPage->getChildren()->getIterator() as $id => $page) {
            $item = new MenuItem();
            $item->setTitle($page->getTitle());
            $item->setLink('display/'.$page->getId());
            $item->setId($page->getIdent());

            if ($active == $page->getId()) {
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
                $parent = $this->contentRepository->getPageReference(
                        Guid::parse(str_replace('parent-', '', $menu->parent)))
                ->getId();
            }
             
            foreach ($menu->menu as $order => $pageId) {
                $pageId = Guid::parse($pageId);
                $this->contentRepository->setPageParent($pageId, $parent);
                $this->contentRepository->setPeerOrderWeight($pageId, $order);
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
            $cItem->setLink('display/'.$child->getId());
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

        $adminNav->contentTypes->add('GamePage', 'Game');
        $adminNav->contentTypes->add('BasicPage', 'Basic page');

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

}
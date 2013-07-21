<?php
namespace smll\cms\controllers;

use smll\framework\utils\Guid;

use smll\framework\security\interfaces\IRoleProvider;

use smll\framework\security\interfaces\IMembershipProvider;

use smll\framework\utils\Boolean;

use smll\framework\utils\HashMap;

use smll\cms\models\CmsEditUserModel;

use smll\framework\mvc\Controller;

class UserController extends Controller
{

    /**
     *
     * @var IRoleProvider
     */
    private $roleProvider = null;

    public function __construct(IRoleProvider $roleProvider)
    {
        $this->roleProvider = $roleProvider;
    }

    public function index()
    {

        return $this->view();
    }

    /**
     * [InRoles(Roles=Administrator)]
     * @param unknown $id
     * @return \smll\framework\mvc\ViewResult
     */
    public function edit($id)
    {

        $user = $this->membership->getUser(Guid::parse($id));

        $roles = $this->roleProvider->getRoles();

        $userModel = new CmsEditUserModel();

        $userModel->userid = $id;
        $userModel->username = $user->getProviderName();
        $userRoles = $this->roleProvider->getRolesForUser($user->getProviderName());
        $userModel->roles = new HashMap();
        foreach ($roles as $id => $role) {
            $userModel->roles->add($id.'|'.$role, in_array($role, $userRoles));
        }

        return $this->view($userModel);
    }

    /**
     * [InRoles(Roles=Administrator)]
     * @param CmsEditUserModel $user
     * @return \smll\framework\mvc\ViewResult
     */
    public function post_edit(CmsEditUserModel $user)
    {


        $userRoles = $user->roles;
        $roles = $this->roleProvider->getRoles();


        if ($this->modelState->isValid()) {
             
        }


        $user->roles = new HashMap();
        $rolesToUser = array();
        foreach ($roles as $id => $role) {
            if (isset($userRoles[$id])) {
                $user->roles->add($id.'|'.$role, Boolean::parseValue($userRoles[$id]));
                if($this->modelState->isValid()) {
                    $this->roleProvider->addUserInRole($id, $user->userid);
                }
            } else {
                $user->roles->add($id.'|'.$role, false);
                if ($this->modelState->isValid()) {
                    $this->roleProvider->removeUserFromRole($id, $user->userid);
                }
            }
        }

        return $this->view($user);
    }

}
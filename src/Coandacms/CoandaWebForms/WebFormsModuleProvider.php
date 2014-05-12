<?php namespace CoandaCMS\CoandaWebForms;

use Route, App, Config;

class WebFormsModuleProvider implements \CoandaCMS\Coanda\CoandaModuleProvider {

    /**
     * @var string
     */
    public $name = 'webforms';

    /**
     * @param \CoandaCMS\Coanda\Coanda $coanda
     */
    public function boot(\CoandaCMS\Coanda\Coanda $coanda)
	{
		// Add the permissions
        $permissions = [];

		$coanda->addModulePermissions('webforms', 'Web forms', $permissions);
	}

    /**
     *
     */
    public function adminRoutes()
	{
		// Load the media controller
		Route::controller('forms', 'CoandaCMS\CoandaWebForms\Controllers\Admin\WebFormsAdminController');
	}

    /**
     *
     */
    public function userRoutes()
	{
	}

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return mixed
     */
    public function bindings(\Illuminate\Foundation\Application $app)
	{
	}

    public function checkAccess($permission, $parameters, $user_permissions)
    {
        if (in_array('*', $user_permissions))
        {
            return true;
        }

        // If we anything in pages, we allow view
        if ($permission == 'view')
        {
            return;
        }

        // If we don't have this permission in the array, the throw right away
        if (!in_array($permission, $user_permissions))
        {
            throw new PermissionDenied('Access denied by media module: ' . $permission);
        }

        return;
    }

    public function buildAdminMenu($coanda)
    {
        if ($coanda->canViewModule('webforms'))
        {
            $coanda->addMenuItem('forms', 'Forms');
        }
    }

}
<?php namespace CoandaCMS\CoandaWebForms;

use Route, App, Config, Input, Coanda, Redirect;

use CoandaCMS\Coanda\Exceptions\ValidationException;

class WebFormsModuleProvider implements \CoandaCMS\Coanda\CoandaModuleProvider {

    /**
     * @var string
     */
    public $name = 'webforms';

    private $webformRepo;

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
        Route::post('_webformhandler', ['before' => 'csrf', function () {

            $location_id = Input::has('location_id') ? Input::get('location_id') : false;

            if (!$location_id)
            {
                App::abort('404');
            }

            $location = Coanda::module('pages')->getLocation($location_id);

            if (!$location)
            {
                App::abort('404');
            }

            try
            {
                Coanda::module('webforms')->storeFormSubmission(Input::all());

                return Redirect::to(url($location->slug))->with('submission_stored', true);
            }
            catch (ValidationException $exception)
            {
                return Redirect::to(url($location->slug))->with('invalid_fields', $exception->getInvalidFields())->withInput();
            }

        }]);
	}

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return mixed
     */
    public function bindings(\Illuminate\Foundation\Application $app)
	{
        $app->bind('CoandaCMS\CoandaWebForms\Repositories\WebFormsRepositoryInterface', 'CoandaCMS\CoandaWebForms\Repositories\Eloquent\EloquentWebFormsRepository');
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

    private function getWebFormRepo()
    {
        if (!$this->webformRepo)
        {
            $this->webformRepo = App::make('CoandaCMS\CoandaWebForms\Repositories\WebFormsRepositoryInterface');
        }

        return $this->webformRepo;
    }

    public function formFields($page_id, $version_number)
    {
        return $this->getWebFormRepo()->formFields($page_id, $version_number);
    }

    public function addFormField($type, $page_id, $version_number)
    {
        return $this->getWebFormRepo()->addFormField($type, $page_id, $version_number);
    }

    public function removeFormField($form_field_id)
    {
        $this->getWebFormRepo()->removeFormField($form_field_id);
    }

    public function storeFormSubmission($data)
    {
        $this->getWebFormRepo()->storeSubmission($data);    
    }
}
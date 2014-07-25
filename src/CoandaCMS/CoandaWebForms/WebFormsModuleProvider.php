<?php namespace CoandaCMS\CoandaWebForms;

use Route, App, Config, Input, Coanda, Redirect;

use CoandaCMS\Coanda\Exceptions\ValidationException;
use CoandaCMS\CoandaWebForms\Exceptions\WebFormNotFoundException;

/**
 * Class WebFormsModuleProvider
 * @package CoandaCMS\CoandaWebForms
 */
class WebFormsModuleProvider implements \CoandaCMS\Coanda\CoandaModuleProvider {

    /**
     * @var string
     */
    public $name = 'webforms';

    /**
     * @var
     */
    private $webformRepo;

    private $field_types = [];

    private $post_submit_handlers = [];

    /**
     * @param \CoandaCMS\Coanda\Coanda $coanda
     */
    public function boot(\CoandaCMS\Coanda\Coanda $coanda)
	{
		// Add the permissions
        $permissions = [
            'view' => [
                'name' => 'View',
                'options' => []
            ],
            'edit' => [
                'name' => 'Edit',
                'options' => []
            ],
            'download' => [
                'name' => 'Download',
                'options' => []
            ],
        ];

		$coanda->addModulePermissions('webforms', 'Web forms', $permissions);

        $field_types = Config::get('coanda-web-forms::field_types');

        foreach ($field_types as $field_type)
        {
            if (class_exists($field_type))
            {
                $type = new $field_type;

                $this->field_types[$type->identifier()] = $type;
            }
        }

        $post_submit_handlers = Config::get('coanda-web-forms::post_submit_handlers');

        foreach ($post_submit_handlers as $post_submit_handler)
        {
            if (class_exists($post_submit_handler))
            {
                $handler = new $post_submit_handler;

                $this->post_submit_handlers[$handler->identifier()] = $handler;
            }
        }
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
                Coanda::webforms()->storeSubmission(Input::all(), $location_id);

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

    /**
     * @param $permission
     * @param $parameters
     * @param $user_permissions
     * @return bool
     * @throws PermissionDenied
     */
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

    /**
     * @param $coanda
     */
    public function buildAdminMenu($coanda)
    {
        if ($coanda->canViewModule('webforms'))
        {
            $coanda->addMenuItem('forms', 'Forms');
        }
    }

    public function fieldTypes()
    {
        return $this->field_types;
    }

    public function fieldType($identifier)
    {
        return isset($this->field_types[$identifier]) ? $this->field_types[$identifier] : false;
    }

    /**
     * @return mixed
     */
    private function getWebFormRepo()
    {
        if (!$this->webformRepo)
        {
            $this->webformRepo = App::make('CoandaCMS\CoandaWebForms\Repositories\WebFormsRepositoryInterface');
        }

        return $this->webformRepo;
    }

    public function availableForms()
    {
        return $this->getWebFormRepo()->formlist();
    }

    public function getForm($id)
    {
        try
        {
            return $this->getWebFormRepo()->getForm($id);
        }
        catch (WebFormNotFoundException $exception)
        {
            return false;
        }
    }

    /**
     * @param $data
     */
    public function storeSubmission($data, $location_id)
    {
        $this->getWebFormRepo()->storeSubmission($data, $location_id);    
    }

    public function postSubmitHandlers()
    {
        return $this->post_submit_handlers;
    }

    public function postSubmitHandler($identifier)
    {
        return isset($this->post_submit_handlers[$identifier]) ? $this->post_submit_handlers[$identifier] : false;
    }
}
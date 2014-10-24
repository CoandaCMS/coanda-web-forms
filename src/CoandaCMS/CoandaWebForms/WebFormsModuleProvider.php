<?php namespace CoandaCMS\CoandaWebForms;

use Route, App, Config, Input, Coanda, Redirect;

use CoandaCMS\Coanda\Exceptions\ValidationException;
use CoandaCMS\CoandaWebForms\Exceptions\WebFormNotFoundException;

class WebFormsModuleProvider implements \CoandaCMS\Coanda\CoandaModuleProvider {

    /**
     * @var string
     */
    public $name = 'webforms';

    /**
     * @var
     */
    private $webformRepo;

    /**
     * @var array
     */
    private $field_types = [];

    /**
     * @var array
     */
    private $post_submit_handlers = [];

    /**
     * @param \CoandaCMS\Coanda\Coanda $coanda
     * @return mixed|void
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

            $page = Coanda::module('pages')->getPage(Input::get('page_id'));

            if (!$page)
            {
                App::abort('404');
            }

            try
            {
                Coanda::webforms()->storeSubmission(Input::all(), $page->id);

                return Redirect::to(url($page->slug))->with('submission_stored', true);
            }
            catch (ValidationException $exception)
            {
                return Redirect::to(url($page->slug))->with('invalid_fields', $exception->getInvalidFields())->withInput();
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
     * @return mixed|void
     */
    public function buildAdminMenu($coanda)
    {
        if ($coanda->canViewModule('webforms'))
        {
            $coanda->addMenuItem('forms', 'Forms');
        }
    }

    /**
     * @return array
     */
    public function fieldTypes()
    {
        return $this->field_types;
    }

    /**
     * @param $identifier
     * @return bool
     */
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

    /**
     * @return mixed
     */
    public function availableForms()
    {
        return $this->getWebFormRepo()->formlist();
    }

    /**
     * @param $id
     * @return bool
     */
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
    public function storeSubmission($data, $page_id)
    {
        $this->getWebFormRepo()->storeSubmission($data, $page_id);
    }

    /**
     * @return array
     */
    public function postSubmitHandlers()
    {
        return $this->post_submit_handlers;
    }

    /**
     * @param $identifier
     * @return bool
     */
    public function postSubmitHandler($identifier)
    {
        return isset($this->post_submit_handlers[$identifier]) ? $this->post_submit_handlers[$identifier] : false;
    }
}
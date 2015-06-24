<?php namespace CoandaCMS\CoandaWebForms\Controllers\Admin;

use View, App, Coanda, Redirect, Input, Session;
use CoandaCMS\Coanda\Controllers\BaseController;
use CoandaCMS\Coanda\Exceptions\ValidationException;
use CoandaCMS\CoandaWebForms\Exceptions\WebFormNotFoundException;
use CoandaCMS\CoandaWebForms\Exceptions\SubmissionNotFoundException;

class WebFormsAdminController extends BaseController {

    /**
     * @var \CoandaCMS\CoandaWebForms\Repositories\WebFormsRepositoryInterface
     */
    private $webFormsRepository;

	/**
	 * @var \CoandaCMS\CoandaWebForms\Exporters\CsvExporter
     */
	private $exporter;

	/**
	 * @param \CoandaCMS\CoandaWebForms\Repositories\WebFormsRepositoryInterface $webFormsRepository
	 * @param \CoandaCMS\CoandaWebForms\Exporters\CsvExporter $exporter
	 */
    public function __construct(\CoandaCMS\CoandaWebForms\Repositories\WebFormsRepositoryInterface $webFormsRepository, \CoandaCMS\CoandaWebForms\Exporters\CsvExporter $exporter)
	{
		$this->beforeFilter('csrf', array('on' => 'post'));

		$this->webFormsRepository = $webFormsRepository;
		$this->exporter = $exporter;
	}

    /**
     * @return mixed
     */
    public function getIndex()
	{
		Coanda::checkAccess('webforms', 'view');

		$forms = $this->webFormsRepository->forms(10);

		return View::make('coanda-web-forms::admin.index', [ 'forms' => $forms ]);
	}

    /**
     * @param $form_id
     */
    public function getView($form_id)
	{
		Coanda::checkAccess('webforms', 'view');

		try
		{
			$form = $this->webFormsRepository->getForm($form_id);
			$field_headings = $this->webFormsRepository->dataHeadings($form, 5);

			return View::make('coanda-web-forms::admin.view', ['form' => $form, 'field_headings' => $field_headings]);
		}
		catch (WebFormNotFoundException $exception)
		{
			App::abort('404');
		}
	}

	/**
	 * @param $form_id
	 */
	public function getDownload($form_id)
	{
		Coanda::checkAccess('webforms', 'view');

		try
		{
			$form = $this->webFormsRepository->getForm($form_id);

            if($form->download() && $form->download()->available()) {
                return \Response::download($form->download()->filename);
            }

			$file_name = $form->name . '-Submissions-' . date('d-m-Y-H-i-s', time());

			$this->exporter->exportToBrowser($form, $file_name);
			exit;
		}
		catch (WebFormNotFoundException $exception)
		{
			App::abort('404');
		}
	}

    /**
     * @param $submission_id
     */
    public function getSubmission($submission_id)
	{
		Coanda::checkAccess('webforms', 'view');

		try
		{
			$submission = $this->webFormsRepository->getSubmission($submission_id);
			$form = $this->webFormsRepository->getForm($submission->form_id);

			return View::make('coanda-web-forms::admin.submission', ['form' => $form, 'submission' => $submission]);
		}
		catch (SubmissionNotFoundException $exception)
		{
			App::abort('404');
		}
		catch (WebFormNotFoundException $exception)
		{
			App::abort('404');
		}
	}

    /**
     * @return mixed
     */
    public function getAdd()
	{
		Coanda::checkAccess('webforms', 'edit');

		$invalid_fields = Session::has('invalid_fields') ? Session::get('invalid_fields') : [];

		return View::make('coanda-web-forms::admin.add', ['invalid_fields' => $invalid_fields]);
	}

    /**
     * @return mixed
     */
    public function postAdd()
	{
		Coanda::checkAccess('webforms', 'edit');

		if (Input::has('cancel') && Input::get('cancel') == 'true')
		{
			return Redirect::to(Coanda::adminUrl('forms'));
		}

		try
		{
			$form = $this->webFormsRepository->createForm(Input::all());

			return Redirect::to(Coanda::adminUrl('forms/edit/' . $form->id));
		}
		catch (ValidationException $exception)
		{
			return Redirect::to(Coanda::adminUrl('forms/add'))->with('error', true)->with('invalid_fields', $exception->getInvalidFields())->withInput();
		}
	}

    /**
     * @param $form_id
     */
    public function getEdit($form_id)
	{
		Coanda::checkAccess('webforms', 'edit');

		try
		{
			$form = $this->webFormsRepository->getForm($form_id);

			$invalid_fields = Session::has('invalid_fields') ? Session::get('invalid_fields') : [];

			$field_types = Coanda::webforms()->fieldTypes();
			$available_post_submit_handlers = Coanda::webforms()->postSubmitHandlers();
			$enabled_post_submit_handlers = $form->enabledPostSubmitHandlers();
			$enabled_post_submit_handler_data = $form->postSubmitHandlerData();

			return View::make('coanda-web-forms::admin.edit', ['form' => $form, 'invalid_fields' => $invalid_fields, 'field_types' => $field_types, 'available_post_submit_handlers' => $available_post_submit_handlers, 'enabled_post_submit_handlers' => $enabled_post_submit_handlers, 'enabled_post_submit_handler_data' => $enabled_post_submit_handler_data]);
		}
		catch (WebFormNotFoundException $exception)
		{
			App::abort('404');
		}
	}

    /**
     * @param $form_id
     */
    public function postEdit($form_id)
	{
		Coanda::checkAccess('webforms', 'edit');

		try
		{
			$form = $this->webFormsRepository->getForm($form_id);

			$has_added = false;
			$has_removed = false;

			if (Input::has('add_field') && Input::get('add_field') == 'true')
			{
				$field_type = Input::has('new_field_type') ? Input::get('new_field_type') : false;

				if (!$field_type)
				{
					return Redirect::to(Coanda::adminUrl('forms/edit/' . $form->id));
				}

				$this->webFormsRepository->addField($form_id, $field_type);

				$has_added = true;
			}

			if (Input::has('remove_selected') && Input::get('remove_selected') == 'true')
			{
				$fields = Input::has('remove_fields') ? Input::get('remove_fields') : [];

				if (is_array($fields) && count($fields) > 0)
				{
					foreach ($fields as $field_id)
					{
						$this->webFormsRepository->removeField($form_id, $field_id);
					}
				}

				$has_removed = true;
			}

			try
			{
				$this->webFormsRepository->storeForm($form_id, Input::all());

				if ($has_added)
				{
					return Redirect::to(Coanda::adminUrl('forms/edit/' . $form->id))->with('field_added', true);
				}

				if ($has_removed)
				{
					return Redirect::to(Coanda::adminUrl('forms/edit/' . $form->id))->with('fields_removed', true);				
				}

				return Redirect::to(Coanda::adminUrl('forms/view/' . $form->id));
			}
			catch (ValidationException $exception)
			{
				if ($has_added)
				{
					return Redirect::to(Coanda::adminUrl('forms/edit/' . $form->id))->with('field_added', true);
				}

				if ($has_removed)
				{
					return Redirect::to(Coanda::adminUrl('forms/edit/' . $form->id))->with('fields_removed', true);				
				}

				return Redirect::to(Coanda::adminUrl('forms/edit/' . $form->id))->with('error', true)->with('invalid_fields', $exception->getInvalidFields())->withInput();
			}

		}
		catch (WebFormNotFoundException $exception)
		{
			App::abort('404');
		}
	}

    /**
     * @param $form_id
     */
    public function getDelete($form_id)
    {
        Coanda::checkAccess('webforms', 'edit');

        try
        {
            $form = $this->webFormsRepository->getForm($form_id);

            return View::make('coanda-web-forms::admin.delete', ['form' => $form]);
        }
        catch (WebFormNotFoundException $exception)
        {
            App::abort('404');
        }
    }

    /**
     * @param $form_id
     */
    public function postDelete($form_id)
    {
        Coanda::checkAccess('webforms', 'edit');

        try
        {
            $form = $this->webFormsRepository->getForm($form_id);

            $form->delete();

            return Redirect::to(Coanda::adminUrl('forms'))->with('form_removed', true);
        }
        catch (WebFormNotFoundException $exception)
        {
            App::abort('404');
        }
    }


    /**
     * @param $form_id
     */
    public function getQueue($form_id)
    {
        Coanda::checkAccess('webforms', 'view');

        try
        {
            $form = $this->webFormsRepository->getForm($form_id);

            if ($form) {
                $this->webFormsRepository->createDownload( ['webform_id' => $form_id] )->queue();
            }

            return Redirect::to(Coanda::adminUrl('forms/view/' . $form_id));
        }
        catch (WebFormNotFoundException $exception)
        {
            App::abort('404');
        }
    }

    /**
     * Get Progress as Json 
     * 
     * @param int $form_id 
     * 
     * @return           
     */
    public function getProgress($form_id)
    {
        Coanda::checkAccess('webforms', 'view');

        try
        {
            $form = $this->webFormsRepository->getForm($form_id);

            return $form->download();
        }
        catch (WebFormNotFoundException $exception)
        {
            $returnData = array(
                'status' => 'error',
                'message' => 'Web Form or Download not found!'
            );
            return \Response::json($returnData, 404);
        }
    }

}
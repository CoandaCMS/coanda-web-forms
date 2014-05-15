<?php namespace CoandaCMS\CoandaWebForms\Controllers\Admin;

use View, App, Coanda, Redirect, Input, Session;

use CoandaCMS\Coanda\Controllers\BaseController;

class WebFormsAdminController extends BaseController {

	private $webFormsRepository;

    public function __construct(\CoandaCMS\CoandaWebForms\Repositories\WebFormsRepositoryInterface $webFormsRepository)
	{
		$this->beforeFilter('csrf', array('on' => 'post'));

		$this->webFormsRepository = $webFormsRepository;
	}

    public function getIndex()
	{
		Coanda::checkAccess('webforms', 'view');

		$formpages = $this->webFormsRepository->formpages(10, Input::has('page') ? Input::get('page') : 1);

		return View::make('coanda-web-forms::admin.index', [ 'formpages' => $formpages ]);
	}

    public function getSubmissions($page_id)
	{
		Coanda::checkAccess('webforms', 'view');

		$page = Coanda::module('pages')->getPage($page_id);
		$submissions = $this->webFormsRepository->submissions($page_id, 10);

		return View::make('coanda-web-forms::admin.submissions', [ 'page' => $page, 'submissions' => $submissions ]);
	}

    public function getSubmission($submission_id)
	{
		Coanda::checkAccess('webforms', 'view');

		$submission = $this->webFormsRepository->submission($submission_id);
		$page = Coanda::module('pages')->getPage($submission->page_id);

		return View::make('coanda-web-forms::admin.submission', [ 'page' => $page, 'submission' => $submission ]);
	}
}
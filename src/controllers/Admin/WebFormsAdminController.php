<?php namespace CoandaCMS\CoandaWebForms\Controllers\Admin;

use View, App, Coanda, Redirect, Input, Session;

use CoandaCMS\Coanda\Controllers\BaseController;

class WebFormsAdminController extends BaseController {

    public function __construct()
	{
		$this->beforeFilter('csrf', array('on' => 'post'));
	}

    public function getIndex()
	{
		dd('hmmmmm web forms then hey!');
		// Coanda::checkAccess('layout', 'edit');
		// $layouts = Coanda::module('layout')->layouts();

		// $block_list = $this->layoutBlockRepository->getBlockList(10);

		// return View::make('coanda::admin.modules.layout.index', [ 'layouts' => $layouts, 'block_list' => $block_list ]);
	}
}
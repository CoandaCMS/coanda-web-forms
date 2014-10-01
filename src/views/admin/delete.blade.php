@extends('coanda::admin.layout.main')

@section('page_title', 'Confirm delete form ' . $form->name)

@section('content')

<div class="row">

	<div class="breadcrumb-nav">
		<ul class="breadcrumb">
			<li><a href="{{ Coanda::adminUrl('forms') }}">Forms</a></li>
			<li>Delete {{ $form->name }}</li>
		</ul>
	</div>
</div>

<div class="row">
	<div class="page-name col-md-12">
		<h1 class="pull-left">Delete {{ $form->name }}</h1>
	</div>
</div>

<div class="row">
	<div class="page-options col-md-12"></div>
</div>

<div class="row">
	<div class="col-md-12">

		<div class="page-tabs">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#confirm" data-toggle="tab">Confirm</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="confirm">

					<div class="alert alert-danger">
						<i class="fa fa-exclamation-triangle"></i> Are you sure you want to permanently delete this form?
					</div>

                    @set('submission_count', $form->submissions->count())
					<p>Deleting this form will also remove {{ $submission_count }} submission{{ ($submission_count != 1) ? 's' : '' }}</p>

					{{ Form::open(['url' => Coanda::adminUrl('forms/delete/' . $form->id )]) }}
						{{ Form::button('I understand, please delete', ['name' => 'permanent_remove', 'value' => 'true', 'type' => 'submit', 'class' => 'btn btn-danger']) }}
                    {{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
</div>

@stop
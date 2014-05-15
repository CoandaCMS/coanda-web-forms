@extends('coanda::admin.layout.main')

@section('page_title', 'Submission #' . $submission->id)

@section('content')

<div class="row">

	<div class="breadcrumb-nav">
		<ul class="breadcrumb">
			<li><a href="{{ Coanda::adminUrl('forms') }}">Forms</a></li>
			<li><a href="{{ Coanda::adminUrl('forms/submissions/' . $page->id) }}">{{ $page->present()->name }}</a></li>
			<li>Submission #{{ $submission->id }}</li>
		</ul>
	</div>
</div>

<div class="row">
	<div class="page-name col-md-12">
		<h1 class="pull-left">Submission #{{ $submission->id }}</h1>
	</div>
</div>

<div class="row">
	<div class="page-options col-md-12"></div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="page-tabs">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#submission" data-toggle="tab">Submission</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="submissions">

					@foreach ($submission->fields as $field)
						<p>Field {{ $field->id }}</p>
					@endforeach

				</div>
			</div>
		</div>
	</div>
</div>

@stop
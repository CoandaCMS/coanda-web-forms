@extends('coanda::admin.layout.main')

@section('page_title', 'View form ' . $form->name)

@section('content')

<div class="row">

	<div class="breadcrumb-nav">
		<ul class="breadcrumb">
			<li><a href="{{ Coanda::adminUrl('forms') }}">Forms</a></li>
			<li><a href="{{ Coanda::adminUrl('forms/view/' . $form->id) }}">{{ $form->name }}</a></li>
			<li>Submission</li>
		</ul>
	</div>
</div>

<div class="row">
	<div class="page-name col-md-12">
		<h1 class="pull-left">{{ $form->name }}, submission</h1>
	</div>
</div>

<div class="row">
	<div class="page-options col-md-12"></div>
</div>

<div class="row">
	<div class="col-md-12">

		<div class="page-tabs">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#data" data-toggle="tab">Submitted data</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="data">

					<table class="table table-striped">
						<tr>
							<th>Submitted</th>
							<td>{{ $submission->created_at->format('d/m/Y H:i:s') }}</td>
						</tr>

						@foreach ($submission->fields as $field)
							<tr>
								<th>{{ $field->label }}</th>
								<td>{{ $field->display_full }}</td>
							</tr>
						@endforeach

					</table>

				</div>
			</div>
		</div>
	</div>
</div>

@stop
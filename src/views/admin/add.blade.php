@extends('coanda::admin.layout.main')

@section('page_title', 'Add new form')

@section('content')

<div class="row">

	<div class="breadcrumb-nav">
		<ul class="breadcrumb">
			<li><a href="{{ Coanda::adminUrl('forms') }}">Forms</a></li>
			<li>Add new form</li>
		</ul>
	</div>
</div>

<div class="row">
	<div class="page-name col-md-12">
		<h1 class="pull-left">Add new form</h1>
	</div>
</div>

<div class="row">
	<div class="page-options col-md-12"></div>
</div>

<div class="row">
	<div class="col-md-12">

		<div class="edit-container">
			{{ Form::open(['url' => Coanda::adminUrl('forms/add')]) }}

				<div class="form-group @if (isset($invalid_fields['name'])) has-error @endif">
					<label class="control-label" for="name">Name</label>

					{{ Form::text('name', Input::old('name'), [ 'class' => 'form-control' ]) }}

				    @if (isset($invalid_fields['name']))
				    	<span class="help-block">{{ $invalid_fields['name'] }}</span>
				    @endif
				</div>

				{{ Form::button('Create', ['name' => 'save', 'value' => 'true', 'type' => 'submit', 'class' => 'btn btn-primary']) }}
				{{ Form::button('Cancel', ['name' => 'cancel', 'value' => 'true', 'type' => 'submit', 'class' => 'btn btn-default']) }}

			{{ Form::close() }}
		</div>

	</div>
</div>

@stop
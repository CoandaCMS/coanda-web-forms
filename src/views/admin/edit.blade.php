@extends('coanda::admin.layout.main')

@section('page_title', 'Edit form ' . $form->name)

@section('content')

<div class="row">

	<div class="breadcrumb-nav">
		<ul class="breadcrumb">
			<li><a href="{{ Coanda::adminUrl('forms') }}">Forms</a></li>
			<li><a href="{{ Coanda::adminUrl('forms/view/' . $form->id) }}">{{ $form->name }}</a></li>
			<li>Edit</li>
		</ul>
	</div>
</div>

<div class="row">
	<div class="page-name col-md-12">
		<h1 class="pull-left">Edit {{ $form->name }}</h1>
	</div>
</div>

<div class="row">
	<div class="page-options col-md-12"></div>
</div>

<div class="row">
	<div class="col-md-12">

		<div class="edit-container">

			@if (Session::has('field_added'))
				<div class="alert alert-success">
					Field added
				</div>
			@endif

			{{ Form::open(['url' => Coanda::adminUrl('forms/edit/' . $form->id)]) }}

				<div class="form-group @if (isset($invalid_fields['name'])) has-error @endif">
					<label class="control-label" for="name">Name</label>

					{{ Form::text('name', Input::old('name', $form->name), [ 'class' => 'form-control' ]) }}

				    @if (isset($invalid_fields['name']))
				    	<span class="help-block">{{ $invalid_fields['name'] }}</span>
				    @endif
				</div>

				<h2>Fields</h2>

				@if ($form->fields->count() > 0)
					@foreach ($form->fields as $field)
						<div class="form-group">
							<div class="panel panel-default">
								<div class="panel-heading">
									<input type="checkbox" name="remove_fields[]" value="{{ $field->id }}" class="pull-right">
									{{ ucfirst(str_replace('_', ' ', $field->type)) }}
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-2">
											<div class="form-group">
												<label>Order</label>
												{{ Form::text('field_' . $field->id . '_order', Input::old('field_' . $field->id . '_order', $field->order), [ 'class' => 'form-control' ]) }}
											</div>
											<div class="form-group">
												<label>Columns</label>
												{{ Form::text('field_' . $field->id . '_columns', Input::old('field_' . $field->id . '_columns', $field->columns), [ 'class' => 'form-control' ]) }}
											</div>
											<div class="form-group">
												<div class="checkbox">
													<label>
														<input type="checkbox" name="{{ 'field_' . $field->id . '_required' }}" value="true" @if ($field->required) checked="checked" @endif @if (in_array($field->type, ['content_header', 'content_text'])) disabled="disabled" @endif>
														Required
													</label>
												</div>
											</div>
										</div>

										<div class="col-sm-10">
											<div class="form-group @if (isset($invalid_fields['field_' . $field->id]['label'])) has-error @endif">
												<label>Label / Text</label>
												{{ Form::text('field_' . $field->id . '_label', Input::old('field_' . $field->id . '_label', $field->label), [ 'class' => 'form-control' ]) }}

												@if (isset($invalid_fields['field_' . $field->id]['label']))
													<span class="help-block">{{ $invalid_fields['field_' . $field->id]['label'] }}</span>
												@endif
											</div>

											@include($field->type()->editTemplate(), [ 'field' => $field, 'has_error' => isset($invalid_fields['field_' . $field->id]['label']) ])
										</div>
									</div>
								</div>
							</div>
						</div>
					@endforeach
				@else
					<p>This form has no fields.</p>
				@endif

				<hr>

				{{ Form::button('Remove selected', ['name' => 'remove_selected', 'value' => 'true', 'type' => 'submit', 'class' => 'pull-right btn btn-default']) }}

				<div class="form-group">
					<div class="row">
						<div class="col-xs-3">
							<select name="new_field_type" class="form-control">
								@foreach ($field_types as $field_type)
									<option value="{{ $field_type->identifier() }}">{{ $field_type->name() }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-xs-1">
							{{ Form::button('Add', ['name' => 'add_field', 'value' => 'true', 'type' => 'submit', 'class' => 'btn btn-default']) }}
						</div>
					</div>
				</div>

                <div class="form-group">
                    <label for="submitted_message" class="control-label">Submitted text e.g. Thanks for your submission.</label>
                    <textarea id="submitted_message" name="submitted_message" class="form-control" rows="5">{{ $form->submitted_message }}</textarea>
                </div>

				@if (count($available_post_submit_handlers) > 0)
					<h2>Post submit handlers</h2>

					@foreach ($available_post_submit_handlers as $post_submit_handler)
						<div class="form-group">
							<div class="row">
								<div class="col-xs-2">
									<div class="checkbox">
										<label>
											<input type="checkbox" name="post_submit_handlers[]" value="{{ $post_submit_handler->identifier() }}" @if (in_array($post_submit_handler->identifier(), $enabled_post_submit_handlers)) checked="checked" @endif>
											{{ $post_submit_handler->name() }}
										</label>
									</div>
								</div>
								<div class="col-xs-10">
									@include($post_submit_handler->adminTemplate(), ['hander_data' => isset($enabled_post_submit_handler_data[$post_submit_handler->identifier()]) ? $enabled_post_submit_handler_data[$post_submit_handler->identifier()] : []])
								</div>
							</div>
						</div>
					@endforeach
				@endif

				{{ Form::button('OK', ['name' => 'ok', 'value' => 'true', 'type' => 'submit', 'class' => 'btn btn-primary']) }}

			{{ Form::close() }}
		</div>

	</div>
</div>

@stop
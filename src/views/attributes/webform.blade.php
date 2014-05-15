{{ Form::open(['url' => url('_webformhandler')]) }}

	<input type="hidden" name="page_id" value="{{ $parameters['page']->id }}">
	<input type="hidden" name="version" value="{{ $parameters['page']->current_version }}">
	<input type="hidden" name="location_id" value="{{ $parameters['pagelocation']->id }}">

	@if ($has_error)
		<div class="alert alert-danger">
			Error, please complete all the required fields
		</div>
	@endif

	@foreach ($data['fields'] as $field)
		<div class="form-group @if (isset($invalid_fields['field_' . $field->id])) has-error @endif">
			<label class="control-label">{{ $field->label }} @if ($field->required) * @endif</label>
			@include('coanda-web-forms::attributes.fieldtypes.' . $field->type, [ 'field' => $field, 'parameters' => $field->typeData(), 'invalid_fields' => Session::has('invalid_fields') ? Session::get('invalid_fields') : [] ])
		</div>
	@endforeach

	<div class="form-group">
		<button class="btn btn-default" name="submit_form">Submit</button>
	</div>

{{ Form::close() }}
{{ Form::open(['url' => ($parameters['pagelocation'] ? url($parameters['pagelocation']->slug) : '')]) }}

	@foreach ($data['fields'] as $field)
		<div class="form-group">
			<label class="form-control">{{ $field->label }}</label>

			@include('coanda-web-forms::attributes.fieldtypes.' . $field->type, [ 'field' => $field ])

		</div>
	@endforeach

	<button name="submit_form">Submit</button>

{{ Form::close() }}
{{ Form::open(['url' => ($parameters['pagelocation'] ? url($parameters['pagelocation']->slug) : '')]) }}

	@foreach ($data['fields'] as $field)
		<div class="form-group">
			<label class="form-control">{{ $field->label }}</label>

			@if ($field->type == 'textline')
				<input type="text" class="form-control" name="field_{{ $field->id }}">
			@endif
		</div>
	@endforeach

	<button name="submit_form">Submit</button>

{{ Form::close() }}
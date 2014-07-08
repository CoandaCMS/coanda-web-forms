<div class="form-group @if (isset($invalid_fields['field_' . $field->id])) has-error @endif">
	<label class="control-label" for="field_{{ $field->id }}">{{ $field->label }} @if ($field->required) * @endif</label>
	<select class="form-control" name="field_{{ $field->id }}" id="field_{{ $field->id }}">
		@foreach ((isset($field->typeData()['options']) ? $field->typeData()['options'] : []) as $option_index => $option)
			<option value="{{ $option }}" @if (Input::old('field_' . $field->id, '') == $option) selected="selected" @endif>{{ $option }}</option>
		@endforeach
	</select>

	@if (isset($invalid_fields['field_' . $field->id]))
		<span class="help-block">{{ $invalid_fields['field_' . $field->id] }}</span>
	@endif
</div>
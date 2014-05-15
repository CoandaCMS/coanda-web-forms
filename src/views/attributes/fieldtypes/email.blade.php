<input type="text" class="form-control" name="field_{{ $field->id }}" value="{{ Input::old('field_' . $field->id) }}">

@if (isset($invalid_fields['field_' . $field->id]))
	<span class="help-block">{{ $invalid_fields['field_' . $field->id] }}</span>
@endif

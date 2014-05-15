<textarea rows="{{ isset($field->typeData()['rows']) ? $field->typeData()['rows'] : 5 }}" class="form-control" name="field_{{ $field->id }}"></textarea>

@if (isset($invalid_fields['field_' . $field->id]))
	<span class="help-block">{{ $invalid_fields['field_' . $field->id] }}</span>
@endif

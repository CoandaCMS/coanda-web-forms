<div class="form-group @if (isset($invalid_fields['field_' . $field->id])) has-error @endif">
	<label class="control-label" for="field_{{ $field->id }}">{{ $field->label }} @if ($field->required) * @endif</label>
	<input type="text" class="form-control" name="field_{{ $field->id }}" id="field_{{ $field->id }}" value="{{ Input::old('field_' . $field->id) }}">

	@if (isset($invalid_fields['field_' . $field->id]))
		<span class="help-block">{{ $invalid_fields['field_' . $field->id] }}</span>
	@endif
</div>

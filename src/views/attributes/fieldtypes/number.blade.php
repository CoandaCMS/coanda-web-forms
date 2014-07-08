<div class="form-group @if (isset($invalid_fields['field_' . $field->id])) has-error @endif">
	<label class="control-label" for="field_{{ $field->id }}">{{ $field->label }} @if ($field->required) * @endif</label>

	<input type="text" class="form-control" name="field_{{ $field->id }}" id="field_{{ $field->id }}" value="{{ Input::old('field_' . $field->id) }}">

	@set('min', isset($field->typeData()['min']) ? $field->typeData()['min'] : false)
	@set('max', isset($field->typeData()['min']) ? $field->typeData()['max'] : false)

	@if (($min && $min) > 0 && ($max && $max > 0))
		<span class="help-block">Between {{ $min }} and {{ $max }}</span>
	@elseif ($min && $min > 0)
		<span class="help-block">Greater than {{ $min }}</span>
	@elseif ($max && $max > 0)
		<span class="help-block">Less than {{ $max }}</span>
	@endif

	@if (isset($invalid_fields['field_' . $field->id]))
		<span class="help-block">{{ $invalid_fields['field_' . $field->id] }}</span>
	@endif
</div>
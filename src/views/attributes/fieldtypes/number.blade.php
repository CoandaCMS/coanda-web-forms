<input type="text" class="form-control" name="field_{{ $field->id }}" value="{{ Input::old('field_' . $field->id) }}">

@if ((isset($parameters['number_min']) && $parameters['number_min'] > 0) && (isset($parameters['number_max']) && $parameters['number_max'] > 0))
	<span class="help-block">Between {{ $parameters['number_min'] }} and {{ $parameters['number_max'] }}</span>
@elseif ((isset($parameters['number_min']) && $parameters['number_min'] > 0))
	<span class="help-block">Greater than {{ $parameters['number_min'] }}</span>
@elseif ((isset($parameters['number_max']) && $parameters['number_max'] > 0))
	<span class="help-block">Less than {{ $parameters['number_max'] }}</span>
@endif

@if (isset($invalid_fields['field_' . $field->id]))
	<span class="help-block">{{ $invalid_fields['field_' . $field->id] }}</span>
@endif

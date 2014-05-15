@foreach ((isset($field->typeData()['options']) ? $field->typeData()['options'] : []) as $option_index => $option)
	<div class="checkbox">
		<input type="checkbox" name="field_{{ $field->id }}[]" value="{{ $option }}" @if (in_array($option, Input::old('field_' . $field->id, []))) checked="checked" @endif>
		{{ $option }}
	</div>
@endforeach

@if (isset($invalid_fields['field_' . $field->id]))
	<span class="help-block">{{ $invalid_fields['field_' . $field->id] }}</span>
@endif

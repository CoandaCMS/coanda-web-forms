@foreach ((isset($field->typeData()['options']) ? $field->typeData()['options'] : []) as $option_index => $option)
	<div class="radio">
		<input type="radio" name="field_{{ $field->id }}" value="{{ $option }}" @if (Input::old('field_' . $field->id, '') == $option) checked="checked" @endif>
		{{ $option }}
	</div>
@endforeach

@if (isset($invalid_fields['field_' . $field->id]))
	<span class="help-block">{{ $invalid_fields['field_' . $field->id] }}</span>
@endif

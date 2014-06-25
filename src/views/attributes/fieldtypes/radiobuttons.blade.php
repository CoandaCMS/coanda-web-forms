<div class="form-group @if (isset($invalid_fields['field_' . $field->id])) has-error @endif">
	<label class="control-label">{{ $field->label }} @if ($field->required) * @endif</label>

	@foreach ((isset($field->typeData()['options']) ? $field->typeData()['options'] : []) as $option_index => $option)
		<div class="radio">
			<label>
				<input type="radio" name="field_{{ $field->id }}" value="{{ $option }}" @if (Input::old('field_' . $field->id, '') == $option) checked="checked" @endif>
				{{ $option }}
			</label>
		</div>
	@endforeach

	@if (isset($invalid_fields['field_' . $field->id]))
		<span class="help-block">{{ $invalid_fields['field_' . $field->id] }}</span>
	@endif
</div>
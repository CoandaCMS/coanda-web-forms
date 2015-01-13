@set('default_ticked', isset($field->typeData()['default_ticked']) && $field->typeData()['default_ticked'])
@set('old_input', Input::old('field_' . $field->id, 'false') == 'true')
@set('has_submitted', Input::old('field_' . $field->id . '_has_submitted', 'false') == 'true')
@set('is_ticked', $has_submitted ? $old_input : $default_ticked)

<div class="form-group @if (isset($invalid_fields['field_' . $field->id])) has-error @endif">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="field_{{ $field->id }}" value="true" {{ $is_ticked ? 'checked="checked"' : '' }}>
            {{ $field->label }} @if ($field->required) * @endif
        </label>
        <input type="hidden" name="field_{{ $field->id }}_has_submitted" value="true">
    </div>

	@if (isset($invalid_fields['field_' . $field->id]))
		<span class="help-block">{{ $invalid_fields['field_' . $field->id] }}</span>
	@endif
</div>

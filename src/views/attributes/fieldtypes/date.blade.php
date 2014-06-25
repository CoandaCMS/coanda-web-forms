<div class="form-group @if (isset($invalid_fields['field_' . $field->id])) has-error @endif">
	<label class="control-label">{{ $field->label }} @if ($field->required) * @endif</label>
	<div class="input-group">
		<input type="text" class="form-control" name="field_{{ $field->id }}" value="{{ Input::old('field_' . $field->id) }}">
		<span class="input-group-addon">
			<span class="glyphicon glyphicon-calendar"></span>
		</span>
	</div>

	@if (isset($invalid_fields['field_' . $field->id]))
		<span class="help-block">{{ $invalid_fields['field_' . $field->id] }}</span>
	@endif
</div>
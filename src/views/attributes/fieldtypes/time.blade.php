<div class="form-group @if (isset($invalid_fields['field_' . $field->id])) has-error @endif">
	<label class="control-label" for="field_{{ $field->id }}">{{ $field->label }} @if ($field->required) * @endif</label>

	<div class="time-input {{ (isset($field->typeData()['class']) ? $field->typeData()['class'] : '') }}">
	    <input style="width: 50px; float: left;" type="text" class="form-control" id="field_{{ $field->id }}_hour" name="field_{{ $field->id }}[hour]" value="{{ isset(Input::old('field_' . $field->id)['hour']) ? Input::old('field_' . $field->id)['hour'] : '' }}">
	    <span style="float: left; font-size: 20px; padding: 0 5px;" class="divider">:</span>
	    <input style="width: 50px; float: left;" type="text" class="form-control" id="field_{{ $field->id }}_minute" name="field_{{ $field->id }}[minute]" value="{{ isset(Input::old('field_' . $field->id)['minute']) ? Input::old('field_' . $field->id)['minute'] : '' }}">

	    <div class="clearfix"></div>
	</div>

	@if (isset($invalid_fields['field_' . $field->id]))
		<span class="help-block">{{ $invalid_fields['field_' . $field->id] }}</span>
	@endif
</div>
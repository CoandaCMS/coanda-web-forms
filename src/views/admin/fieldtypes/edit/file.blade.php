<label for="field_{{ $field->id }}_required_if_logic">Required logic</label>
<input type="text" class="form-control" id="field_{{ $field->id }}_required_if_logic" name="field_{{ $field->id }}_custom[required_if_logic]" value="{{ isset($field->typeData()['required_if_logic']) ? $field->typeData()['required_if_logic'] : '' }}">
<div class="row">
	<div class="col-xs-6">
		<label for="attribute_{{ $attribute->id }}_field_{{ $field->id }}_number_min">Min</label>
		<input class="form-control" type="text" id="attribute_{{ $attribute->id }}_field_{{ $field->id }}_number_min" name="attribute_{{ $attribute->id }}[fields][field_{{ $field->id }}][custom][number_min]" value="{{ isset($field->typeData()['number_min']) ? $field->typeData()['number_min'] : '' }}">
	</div>
	<div class="col-xs-6">
		<label for="attribute_{{ $attribute->id }}_field_{{ $field->id }}_number_max">Max</label>
		<input class="form-control" type="text" id="attribute_{{ $attribute->id }}_field_{{ $field->id }}_number_max" name="attribute_{{ $attribute->id }}[fields][field_{{ $field->id }}][custom][number_max]" value="{{ isset($field->typeData()['number_max']) ? $field->typeData()['number_max'] : '' }}">
	</div>
</div>
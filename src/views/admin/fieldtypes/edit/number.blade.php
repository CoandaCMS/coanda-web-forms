<div class="row">
	<div class="col-xs-6">
		<label for="field_{{ $field->id }}_min">Min</label>
		<input class="form-control" type="text" id="field_{{ $field->id }}_min" name="field_{{ $field->id }}_custom[min]" value="{{ isset($field->typeData()['min']) ? $field->typeData()['min'] : '' }}">
	</div>
	<div class="col-xs-6">
		<label for="field_{{ $field->id }}_max">Max</label>
		<input class="form-control" type="text" id="field_{{ $field->id }}_max" name="field_{{ $field->id }}_custom[max]" value="{{ isset($field->typeData()['max']) ? $field->typeData()['max'] : '' }}">
	</div>
</div>
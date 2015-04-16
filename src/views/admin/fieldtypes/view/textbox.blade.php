<div class="form-group">
	<label>{{ $field->label }} @if ($field->required) * @endif</label>
	<textarea class="form-control" rows="{{ (isset($field->typeData()['rows']) ? $field->typeData()['rows'] : 5) }}"></textarea>
</div>
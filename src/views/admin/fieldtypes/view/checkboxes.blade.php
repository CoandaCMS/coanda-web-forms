<div class="form-group">
	<label>{{ $field->label }} @if ($field->required) * @endif</label>

	@foreach ((isset($field->typeData()['options']) ? $field->typeData()['options'] : []) as $option_index => $option)
		<div class="checkbox">
			<label>
				<input type="checkbox">
				{{ $option }}
			</label>
		</div>
	@endforeach
</div>

<div class="form-group">
	<label>{{ $field->label }}</label>

	@foreach ((isset($field->typeData()['options']) ? $field->typeData()['options'] : []) as $option_index => $option)
		<div class="radio">
			<label>
				<input type="radio">
				{{ $option }}
			</label>
		</div>
	@endforeach
</div>

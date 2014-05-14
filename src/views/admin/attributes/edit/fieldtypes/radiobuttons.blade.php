<label>Options</label>

<div class="option-group">
	<div class="options">
		@foreach ((isset($field->typeData()['options']) ? $field->typeData()['options'] : []) as $option_index => $option)
			<div class="input-group">
				<span class="input-group-addon">&bull;</span>
				<input class="form-control" type="text" name="attribute_{{ $attribute->id }}[fields][field_{{ $field->id }}][custom][options][]" value="{{ $option }}">
			</div>
		@endforeach

		<div class="input-group">
			<span class="input-group-addon">&bull;</span>
			<input class="form-control" type="text" name="attribute_{{ $attribute->id }}[fields][field_{{ $field->id }}][custom][options][]" value="">
		</div>
	</div>

	<button class="btn btn-default btn-add-option pull-right" type="button"><span class="glyphicon glyphicon-plus"></span> Add another option</button>
</div>
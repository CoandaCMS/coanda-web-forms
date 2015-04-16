<div class="form-group">
	<label>{{ $field->label }} @if ($field->required) * @endif</label>
	<select class="form-control">
        @if (isset($field->typeData()['include_blank_option']) && $field->typeData()['include_blank_option'])
            <option></option>
        @endif

		@foreach ((isset($field->typeData()['options']) ? $field->typeData()['options'] : []) as $option_index => $option)
			<option value="{{ $option }}">{{ $option }}</option>
		@endforeach
	</select>
</div>

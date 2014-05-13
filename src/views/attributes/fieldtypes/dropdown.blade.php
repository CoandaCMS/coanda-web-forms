<select>
@foreach ((isset($field->typeData()['options']) ? $field->typeData()['options'] : []) as $option_index => $option)

	<option value="{{ $option }}">{{ $option }}</option>

@endforeach
</select>

@foreach ((isset($field->typeData()['options']) ? $field->typeData()['options'] : []) as $option_index => $option)

	<input type="checkbox" value="{{ $option }}">
	{{ $option }}

@endforeach

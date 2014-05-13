@foreach ((isset($field->typeData()['options']) ? $field->typeData()['options'] : []) as $option_index => $option)

	<input type="radio" value="{{ $option }}">
	{{ $option }}

@endforeach
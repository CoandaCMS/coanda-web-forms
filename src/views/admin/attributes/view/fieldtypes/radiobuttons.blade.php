Options:

<ul>
	@foreach ((isset($field->typeData()['options']) ? $field->typeData()['options'] : []) as $option_index => $option)
		<li>{{ $option }}</li>
	@endforeach
</ul>
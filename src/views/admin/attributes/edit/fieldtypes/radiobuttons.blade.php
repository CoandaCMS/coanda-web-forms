@foreach ((isset($field->typeData()['options']) ? $field->typeData()['options'] : []) as $option_index => $option)

	<input class="form-control" type="text" name="attribute_{{ $attribute->id }}[fields][field_{{ $field->id }}][custom][options][]" value="{{ $option }}">

@endforeach
New option: <input class="form-control" type="text" name="attribute_{{ $attribute->id }}[fields][field_{{ $field->id }}][custom][options][]" value="">

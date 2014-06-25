{{--
$attribute_name
$attribute_identifier
$invalid_fields
$is_required
$prefill_data
--}}
<div class="form-group @if (isset($invalid_fields[$attribute_identifier])) has-error @endif">
	<label>{{ $attribute_name }}</label>

	@set('forms', Coanda::webforms()->availableForms())
	
	<select name="attributes[{{ $attribute_identifier }}]" class="form-control">
		@foreach ($forms as $form)
			<option value="{{ $form->id }}" @if ($form->id == $prefill_data) selected="selected" @endif>{{ $form->name }}</option>
		@endforeach
	</select>

    @if (isset($invalid_fields['attribute_' . $attribute_identifier]))
    	<span class="help-block">{{ $invalid_fields[$attribute_identifier] }}</span>
    @else
    	<span class="help-block">Please choose a form from the list, or create a new one first.</span>
    @endif
</div>
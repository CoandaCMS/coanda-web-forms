{{--
$attribute_name
$attribute_identifier
$invalid_fields
$is_required
$prefill_data
--}}
<fieldset>
	<legend>{{ $attribute_name }} @if ($is_required) * @endif</legend>

	<div class="form-group @if (isset($invalid_fields['attribute_' . $attribute_identifier])) has-error @endif">
		<label class="control-label" for="attribute_{{ $attribute->id }}_notification_email">Notification email address</label>
		<input class="form-control" type="text" id="attribute_{{ $attribute->id }}_notification_email" name="attribute_{{ $attribute->id }}[notification_email]" value="{{ $prefill_data['notification_email'] }}">
	</div>

	@foreach ($prefill_data['fields'] as $field)
		<div class="form-group @if (isset($invalid_fields['attribute_' . $attribute_identifier]['validation_data']['field_' . $field->id])) has-error @endif">
			<div class="panel panel-default">
				<div class="panel-heading">
					<input type="checkbox" name="attribute_{{ $attribute->id }}[remove_field_list][]" value="{{ $field->id }}">
					{{ $field->type }}
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-xs-8">
							<label for="attribute_{{ $attribute->id }}_field_{{ $field->id }}_label">Label</label>
							<input class="form-control" type="text" id="attribute_{{ $attribute->id }}_field_{{ $field->id }}_label" name="attribute_{{ $attribute->id }}[fields][field_{{ $field->id }}][label]" value="{{ $field->label }}">
						</div>
						<div class="col-xs-2">
							<label for="attribute_{{ $attribute->id }}_field_{{ $field->id }}_order">Order</label>
							<input class="form-control" type="text" id="attribute_{{ $attribute->id }}_field_{{ $field->id }}_order" name="attribute_{{ $attribute->id }}[fields][field_{{ $field->id }}][order]" value="{{ $field->order }}">
						</div>
						<div class="col-xs-2">
							<label for="attribute_{{ $attribute->id }}_field_{{ $field->id }}_order">Required</label>
							<div class="checkbox">
								<label>
									<input type="checkbox" id="attribute_{{ $attribute->id }}_field_{{ $field->id }}_required" name="attribute_{{ $attribute->id }}[fields][field_{{ $field->id }}][required]" value="true" @if ($field->required) checked="checked" @endif>
									Yes
								</label>
							</div>
						</div>
					</div>

					@include('coanda-web-forms::admin.attributes.edit.fieldtypes.' . $field->type)
				</div>
			</div>

		    @if (isset($invalid_fields['attribute_' . $attribute_identifier]['validation_data']['field_' . $field->id]))
		    	<span class="help-block">{{ $invalid_fields['attribute_' . $attribute_identifier]['validation_data']['field_' . $field->id] }}</span>
		    @endif
		</div>
	@endforeach

	<div class="form-group">
		<div class="row">
			<div class="col-xs-4">
				{{ Form::button('Remove selected', ['name' => 'attribute_action[attribute_' . $attribute_identifier . ']', 'value' => 'remove_fields', 'type' => 'submit', 'class' => 'btn btn-default']) }}
			</div>
			<div class="col-xs-4 pull-right">
				<select name="attribute_{{ $attribute->id }}_new_field_type" class="form-control">
					<option value="textline">Text line</option>
					<option value="textbox">Text box</option>
					<option value="email">Email address</option>
					<option value="number">Number</option>
					<option value="dropdown">Dropdown box</option>
					<option value="checkboxes">Check boxes (multiple select)</option>
					<option value="radiobuttons">Radio buttons (single select)</option>
					<option value="date">Date field</option>
				</select>
				{{ Form::button('Add', ['name' => 'attribute_action[attribute_' . $attribute_identifier . ']', 'value' => 'add_field', 'type' => 'submit', 'class' => 'btn btn-default pull-right']) }}
			</div>
		</div>
	</div>

</fieldset>	
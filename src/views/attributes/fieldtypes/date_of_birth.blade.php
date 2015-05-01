<div class="row">
	<div class="col-sm-8">
		<div class="form-group @if (isset($invalid_fields['date_of_birth'])) has-error @endif">
			<label for="date_of_birth">Date of birth *</label>

			<div class="row">
				<div class="col-xs-4 col-sm-3">
					@set('selected_day', isset(Input::old('field_' . $field->id, [])['day']) ? Input::old('field_' . $field->id, [])['day'] : '')
					<select class="form-control" name="field_{{ $field->id }}[day]">
						<option value="">Day</option>
						@foreach(range(1, 31) as $day)
							<option value="{{ $day }}" @if ($day == $selected_day) selected="selected" @endif>{{ $day }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-xs-4 col-sm-3">
					@set('selected_month', isset(Input::old('field_' . $field->id, [])['month']) ? Input::old('field_' . $field->id, [])['month'] : '')
					<select class="form-control" name="field_{{ $field->id }}[month]">
						<option value="">Month</option>
						@foreach(range(1, 12) as $month)
							<option value="{{ $month }}" @if ($month == $selected_month) selected="selected" @endif>{{ $month }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-xs-4 col-sm-5">
					@set('selected_year', isset(Input::old('field_' . $field->id, [])['year']) ? Input::old('field_' . $field->id, [])['year'] : '')
					<select class="form-control" name="field_{{ $field->id }}[year]">
						<option value="">Year</option>
						@foreach(range(1914, date('Y', time())) as $year)
							<option value="{{ $year }}" @if ($year == $selected_year) selected="selected" @endif>{{ $year }}</option>
						@endforeach
					</select>
				</div>
			</div>

			@if (isset($invalid_fields['field_' . $field->id]))
				<span class="help-block">{{ $invalid_fields['field_' . $field->id] }}</span>
			@endif
		</div>
	</div>
</div>

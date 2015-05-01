<div class="row">
	<div class="col-sm-8">
		<div class="form-group">
			<label>{{ $field->label }} @if ($field->required) * @endif</label>

			<div class="row">
				<div class="col-xs-4 col-sm-3">
					<select class="form-control" name="date_of_birth[day]">
						<option value="">Day</option>
						@foreach(range(1, 31) as $day)
							<option value="{{ $day }}">{{ $day }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-xs-4 col-sm-3">
					<select class="form-control" name="date_of_birth[month]">
						<option value="">Month</option>
						@foreach(range(1, 12) as $month)
							<option value="{{ $month }}">{{ $month }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-xs-4 col-sm-5">
					<select class="form-control" name="date_of_birth[year]">
						<option value="">Year</option>
						@foreach(range(1914, date('Y', time()) as $year)
							<option value="{{ $year }}">{{ $year }}</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>
	</div>
</div>

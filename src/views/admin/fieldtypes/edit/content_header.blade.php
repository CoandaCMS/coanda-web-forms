<div class="row">
	<div class="col-sm-2">
		<div class="form-group">
			<label>Heading level</label>

			@set('existing_level', isset($field->typeData()['level']) ? $field->typeData()['level'] : 1)

			<select name="field_{{ $field->id }}_custom[level]" class="form-control">
				@foreach (range(1, 6) as $level)
					<option @if ($existing_level == $level) selected="selected" @endif value="{{ $level }}">{{ $level }}</option>
				@endforeach
			</select>
		</div>
	</div>
</div>
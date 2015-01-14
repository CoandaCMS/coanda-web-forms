<div class="row">
    <div class="col-xs-6">
        <div class="form-group @if ($has_error) has-error @endif">
            <label class="control-label" for="field_{{ $field->id }}_min">From</label>
            <input class="form-control" type="text" id="field_{{ $field->id }}_from" name="field_{{ $field->id }}_custom[from]" value="{{ isset($field->typeData()['from']) ? $field->typeData()['from'] : '' }}">
        </div>
    </div>
    <div class="col-xs-6">
        <div class="form-group @if ($has_error) has-error @endif">
            <label class="control-label" for="field_{{ $field->id }}_max">To</label>
            <input class="form-control" type="text" id="field_{{ $field->id }}_to" name="field_{{ $field->id }}_custom[to]" value="{{ isset($field->typeData()['to']) ? $field->typeData()['to'] : '' }}">
        </div>
    </div>
</div>
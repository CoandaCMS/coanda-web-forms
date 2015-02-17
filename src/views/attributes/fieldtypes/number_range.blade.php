@set('render_as_radios', isset($field->typeData()['render_as_radios']) && $field->typeData()['render_as_radios'])

<div class="form-group @if (isset($invalid_fields['field_' . $field->id])) has-error @endif">
    <label class="control-label" for="field_{{ $field->id }}">{{ $field->label }} @if ($field->required) * @endif</label>

    @if ($render_as_radios)
        <div class="radio-buttons">
            @foreach (range($field->typeData()['from'], $field->typeData()['to']) as $number)
                <label class="radio-inline">
                    <input type="radio" name="field_{{ $field->id }}" value="{{ $number }}" @if (Input::old('field_' . $field->id, '') == $number) checked="checked" @endif>
                    {{ $number }}
                </label>
            @endforeach
        </div>
    @else
        <select class="form-control" name="field_{{ $field->id }}" id="field_{{ $field->id }}">
            <option value=""></option>
            @foreach (range($field->typeData()['from'], $field->typeData()['to']) as $number)
                <option value="{{ $number }}" @if (Input::old('field_' . $field->id, '') == $number) selected="selected" @endif>{{ $number }}</option>
            @endforeach
        </select>
    @endif

    @if (isset($invalid_fields['field_' . $field->id]))
        <span class="help-block">{{ $invalid_fields['field_' . $field->id] }}</span>
    @endif
</div>
<div class="form-group @if (isset($invalid_fields['field_' . $field->id])) has-error @endif">
    <label class="control-label">{{ $field->label }} @if ($field->required) * @endif</label>

    <table class="table table-striped">
        <thead>
        <tr>
            <td></td>
            @foreach ((isset($field->typeData()['options']) ? $field->typeData()['options'] : []) as $option_index => $option)
                <th>{{ $option }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach ((isset($field->typeData()['questions']) ? $field->typeData()['questions'] : []) as $question_index => $question)
            <tr>
                <td>{{ $question }}</td>
                @foreach ((isset($field->typeData()['options']) ? $field->typeData()['options'] : []) as $option_index => $option)
                    <td>
                        <input type="radio" name="field_{{ $field->id }}[{{ $question }}]" value="{{ $option }}" @if ($option == (isset(Input::old('field_' . $field->id, [])[$question]) ? Input::old('field_' . $field->id, [])[$question] : ''))) checked="checked" @endif>
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>

</div>

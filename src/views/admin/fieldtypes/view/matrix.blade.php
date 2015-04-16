<div class="form-group">
    <label>{{ $field->label }} @if ($field->required) * @endif</label>

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
                        <td><input type="radio"></td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

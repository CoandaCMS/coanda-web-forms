<div class="form-group">
    <label>{{ $field->label }} @if ($field->required) * @endif</label>
    <select class="form-control">
        <option></option>
        @foreach (range($field->typeData()['from'], $field->typeData()['to']) as $number)
            <option>{{ $number }}</option>
        @endforeach
    </select>
</div>

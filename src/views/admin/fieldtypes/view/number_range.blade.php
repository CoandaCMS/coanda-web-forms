<div class="form-group">
    <label>{{ $field->label }}</label>
    <select class="form-control">
        <option></option>
        @foreach (range($field->typeData()['from'], $field->typeData()['to']) as $number)
            <option>{{ $number }}</option>
        @endforeach
    </select>
</div>

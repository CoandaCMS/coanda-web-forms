<label>Questions</label>

<div class="option-group">
    <div class="options">
        @foreach ((isset($field->typeData()['questions']) ? $field->typeData()['questions'] : []) as $question_index => $question)
            <div class="input-group">
                <span class="input-group-addon">&bull;</span>
                <input class="form-control" type="text" name="field_{{ $field->id }}_custom[questions][]" value="{{ $question }}">
            </div>
        @endforeach

        <div class="input-group">
            <span class="input-group-addon">&bull;</span>
            <input class="form-control" type="text" name="field_{{ $field->id }}_custom[questions][]" value="">
        </div>
    </div>

    <button class="btn btn-default btn-add-option pull-right" type="button"><span class="glyphicon glyphicon-plus"></span> Add another question</button>
</div>

<div class="clearfix"></div>

<label>Options</label>

<div class="option-group">
    <div class="options">
        @foreach ((isset($field->typeData()['options']) ? $field->typeData()['options'] : []) as $option_index => $option)
            <div class="input-group">
                <span class="input-group-addon">&bull;</span>
                <input class="form-control" type="text" name="field_{{ $field->id }}_custom[options][]" value="{{ $option }}">
            </div>
        @endforeach

        <div class="input-group">
            <span class="input-group-addon">&bull;</span>
            <input class="form-control" type="text" name="field_{{ $field->id }}_custom[options][]" value="">
        </div>
    </div>

    <button class="btn btn-default btn-add-option pull-right" type="button"><span class="glyphicon glyphicon-plus"></span> Add another option</button>
</div>
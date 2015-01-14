<div class="row">
    <div class="col-md-12">
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
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="checkbox">
            <input type="checkbox" id="field_{{ $field->id }}_include_blank_option" name="field_{{ $field->id }}_custom[include_blank_option]" value="true" {{ isset($field->typeData()['include_blank_option']) && $field->typeData()['include_blank_option'] ? ' checked="checked"' : '' }}">
            <label for="field_{{ $field->id }}_include_blank_option">Include a blank first option</label>
        </div>
    </div>
</div>
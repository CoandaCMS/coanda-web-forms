<div class="row">
    <div class="col-md-12">
        <div class="checkbox">
            <input type="checkbox" id="field_{{ $field->id }}_render_as_dropdowns" name="field_{{ $field->id }}_custom[render_as_dropdowns]" value="true" {{ isset($field->typeData()['render_as_dropdowns']) && $field->typeData()['render_as_dropdowns'] ? ' checked="checked"' : '' }}">
            <label for="field_{{ $field->id }}_render_as_dropdowns">Render as drop downs</label>
        </div>
    </div>
</div>
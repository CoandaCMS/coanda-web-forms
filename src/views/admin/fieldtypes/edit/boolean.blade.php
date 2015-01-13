<div class="checkbox">
    <input type="checkbox" id="field_{{ $field->id }}_default_ticked" name="field_{{ $field->id }}_custom[default_ticked]" value="true" {{ isset($field->typeData()['default_ticked']) && $field->typeData()['default_ticked'] ? ' checked="checked"' : '' }}">
    <label for="field_{{ $field->id }}_default_ticked">Default ticked</label>
</div>
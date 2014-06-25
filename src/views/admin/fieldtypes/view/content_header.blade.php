@set('heading_level', 'h' . (isset($field->typeData()['level']) ? $field->typeData()['level'] : 1))
<{{ $heading_level }}>{{ $field->label }}</{{ $heading_level }}>
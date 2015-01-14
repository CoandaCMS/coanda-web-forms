@set('country_list', Config::get('coanda-web-forms::country_list'))

<div class="form-group">
    <label>{{ $field->label }}</label>
    <select class="form-control">
        @foreach ($country_list as $country)
            <option>{{ $country }}</option>
        @endforeach
    </select>
</div>
@set('form', Coanda::webforms()->getForm($data))

@if ($form)
	{{ Form::open(['url' => url('_webformhandler'), 'files' => true]) }}

		<input type="hidden" name="form_id" value="{{ $form->id }}">
		<input type="hidden" name="page_id" value="{{ $parameters['page']->id }}">

		@if ($has_error)
			<div class="alert alert-danger">
				Error, please complete all the required fields
			</div>
		@endif

		@set('columncounter', 0)
		@set('field_count', 1)

		<div class="row">
			@foreach ($form->fields as $field)

				@set('columncounter', $columncounter + ($field->columns == 0 ? 12 : $field->columns))

				<div class="col-md-{{ $field->columns == 0 ? 12 : $field->columns }}">
					@include($field->type()->viewTemplate(), [ 'field' => $field ])
				</div>

				@if ($columncounter >= 12 && $field_count < $form->fields->count())
						@set('columncounter', 0)
					</div>
					<div class="row">
				@endif

				@set('field_count', $field_count = $field_count + 1)

			@endforeach
		</div>

		@if ($form->enable_recaptcha)
			<div class="form-group @if (isset($invalid_fields['recaptcha'])) has-error @endif">		
				<script src='https://www.google.com/recaptcha/api.js'></script>
				<p><div class="g-recaptcha" data-sitekey="{{ Config::get('coanda-web-forms::recaptcha_site_key') }}"></div></p>

				@if (isset($invalid_fields['recaptcha']))
					<span class="help-block">{{ $invalid_fields['recaptcha'] }}</span>
				@endif
			</div>
		@endif

		<button class="btn btn-primary" name="submit_form">Submit</button>

	{{ Form::close() }}
@else
	<p>This form is no longer available.</p>
@endif
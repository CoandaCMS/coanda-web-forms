@set('form', Coanda::webforms()->getForm($content))

@if ($form)
	<a href="{{ Coanda::adminUrl('forms/view/' . $form->id) }}">{{ $form->name }}</a>
@else
	<em>Form not found</em>
@endif
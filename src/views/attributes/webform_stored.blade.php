@set('form', Coanda::webforms()->getForm($data))

<div class="alert alert-success">
    @if ($form->submitted_message)
        {{ $form->submitted_message }}
    @else
	    <strong>Thanks!</strong> your submission has been stored.
    @endif
</div>
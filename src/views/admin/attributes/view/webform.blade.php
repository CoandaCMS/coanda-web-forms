<p>Notification email: {{ $data['notification_email'] }}</p>

<table class="table table-striped">
	@foreach ($data['fields'] as $field)
		<tr>
			<td>{{ $field->type }}</td>
			<td>{{ $field->label }}@if ($field->required) * @endif</td>
			<td>Further options</td>
		</tr>
@endforeach
</table>

@if ($data['notification_email'] != '')
	<p><i class="fa fa-envelope-o"></i> Notification email: {{ $data['notification_email'] }}</p>
@endif

<table class="table table-striped">
	@foreach ($data['fields'] as $field)
		<tr>
			<td>{{ $field->type }}</td>
			<td>{{ $field->label }}@if ($field->required) * @endif</td>
			<td>Further options</td>
		</tr>
@endforeach
</table>

@extends('coanda::admin.layout.main')

@section('page_title', 'View form ' . $form->name)

@section('content')

<div class="row">

	<div class="breadcrumb-nav">
		<ul class="breadcrumb">
			<li><a href="{{ Coanda::adminUrl('forms') }}">Forms</a></li>
			<li>{{ $form->name }}</li>
		</ul>
	</div>
</div>

<div class="row">
	<div class="page-name col-md-12">
		<h1 class="pull-left">{{ $form->name }}</h1>
	</div>
</div>

<div class="row">
	<div class="page-options col-md-12">
		<a href="{{ Coanda::adminUrl('forms/edit/' . $form->id) }}" class="btn btn-primary">Edit</a>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                More
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="{{ Coanda::adminUrl('forms/delete/' . $form->id) }}">Delete</a></li>
            </ul>
        </div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">

		<div class="page-tabs">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#submissions" data-toggle="tab">Submissions</a></li>
				<li><a href="#preview" data-toggle="tab">Preview</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="submissions">

					@if ($form->submissions->count() > 0)

						<table class="table table-striped">
							<thead>
								<tr>
									<th>Submitted</th>
									@foreach ($field_headings as $heading)
										<th>{{ $heading }}</th>
									@endforeach
								</tr>
							</thead>
							<tbody>
								@foreach ($form->submissions()->paginate(20) as $submission)
								<tr>
									<td><a href="{{ Coanda::adminUrl('forms/submission/' . $submission->id) }}">{{ $submission->created_at->format('d/m/Y H:i:s') }}</a></td>
									
									@foreach ($submission->fieldsForHeadings($field_headings) as $submission_field)
										<td>{{ $submission_field ? $submission_field->display_line : '' }}</td>
									@endforeach
								</tr>
								@endforeach
							</tbody>
						</table>

						{{ $form->submissions()->paginate(20)->links() }}						
					@else
						<p>This form has not had any submissions yet.</p>
					@endif

				</div>
				<div class="tab-pane" id="preview">

					@set('columncounter', 0)
					@set('field_count', 1)

					<div class="row">
						@foreach ($form->fields as $field)

							<div class="col-md-{{ $field->columns == 0 ? 12 : $field->columns }}">
								@include('coanda-web-forms::admin.fieldtypes.view.' . $field->type, [ 'field' => $field ])
							</div>

							@set('columncounter', $columncounter + ($field->columns == 0 ? 12 : $field->columns))

							@if ($columncounter >= 12 && $field_count < $form->fields->count())
									@set('columncounter', 0)
								</div>
								<div class="row">
							@endif

							@set('field_count', $field_count = $field_count + 1)

						@endforeach
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

@stop
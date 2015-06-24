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
		<a href="{{ Coanda::adminUrl('forms/edit/' . $form->id) }}" class="btn btn-primary">
			Edit
		</a>

		@if($form->submissions->count() < 1000 || \Config::get('queue.default') == 'sync')
		<a href	="{{ Coanda::adminUrl('forms/download/' . $form->id) }}" class="btn btn-success">
			Download
		</a>
		@else
			@set('form_download', $form->download())
			
			@if($form_download && $form_download->available())
				<a href="{{ Coanda::adminUrl('forms/download/' . $form->id) }}" class="btn btn-success">
					Download 
						<span class="badge">Created {{ $form_download->age() }}</span>
				</a>
			@endif

			@if(!$form_download)
				<a href="{{ Coanda::adminUrl('forms/queue/' . $form->id) }}" class="btn btn-default">
					Request Download
				</a>
			@elseif( $form_download->status == 0 )
				<a href="#" class="btn btn-info requested" id="web-form-ajax-btn" data-id="{{ $form->id }}">
					<i id="fmn-search" class="fa fa-spinner fa-spin"></i>
					Download Requested
				</a>
			@elseif( $form_download->status == 1 )
				<a href="#" class="btn btn-info preparing" id="web-form-ajax-btn" data-id="{{ $form->id }}">
					<i id="fmn-search" class="fa fa-spinner fa-spin"></i>
					Preparing Download
						<span class="badge status-percent">{{ $form_download->status_percentage }}</span>
				</a>
			@else
			<a href="{{ Coanda::adminUrl('forms/queue/' . $form->id) }}" class="btn btn-default">
				Re-request Download
			</a>
			@endif
		@endif

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
								@include($field->type()->adminViewTemplate(), [ 'field' => $field ])
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

@section('custom-js')
<script type="text/javascript">
jQuery(document).ready(function($) {

    if ($('#web-form-ajax-btn').length !== 0) {

    	downloadDone = 0;
    	$ajaxBtn = $('#web-form-ajax-btn');

    	function ajaxRequest() {
    		(function request() {
    			if(!downloadDone) {
					$.ajax({
						url: '/admin/forms/progress/' + $ajaxBtn.attr('data-id'), 	
						success: function(data) {

							if (data.filename) {
								downloadReady();
							} else {
								updateDownloadButton(data);
							}

						},
						complete: function(data) {

							if (!data.filename) {
								setTimeout(request, 2500);
							} 

						}
					});
				}
			})();
    	}

    	function updateDownloadButton(data) {
    		var fullContent = '<i id="fmn-search" class="fa fa-spinner fa-spin"></i> Preparing Download <span class="badge status-percent"></span>';
    		if ($ajaxBtn.hasClass('requested')) {
    			$ajaxBtn.html(fullContent);
    		} 

    		$('.status-percent').html(data.status_percentage);
    	}

    	function downloadReady() {
    		$ajaxBtn.attr('href', '/admin/forms/download/' + $ajaxBtn.attr('data-id')).html('Download Ready!');
    		$ajaxBtn.addClass('btn-success').removeClass('btn-info');

    		downloadDone = 1;
    	}

    	ajaxRequest();

    }
    
});

</script>
@append

@stop
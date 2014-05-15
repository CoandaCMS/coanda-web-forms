@extends('coanda::admin.layout.main')

@section('page_title', 'Form submissions: ' . $page->present()->name)

@section('content')

<div class="row">

	<div class="breadcrumb-nav">
		<ul class="breadcrumb">
			<li><a href="{{ Coanda::adminUrl('forms') }}">Forms</a></li>
			<li>{{ $page->present()->name }}</li>
		</ul>
	</div>
</div>

<div class="row">
	<div class="page-name col-md-12">
		<h1 class="pull-left">Submissions for "{{ $page->present()->name }}"</h1>
		<div class="page-status pull-right">
			<span class="label label-default">Total {{ $submissions->getTotal() }}</span>
		</div>
	</div>
</div>

<div class="row">
	<div class="page-options col-md-12"></div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="page-tabs">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#submissions" data-toggle="tab">Submissions</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="submissions">
					@if ($submissions->count() > 0)
						<table class="table table-striped">
							@foreach ($submissions as $submission)
							<tr>
								<td><a href="{{ Coanda::adminUrl('forms/submission/' . $submission->id) }}">{{ $submission->id }}</a></td>
								<td class="tight">hmmmmmmmm</td>
							</tr>
							@endforeach
						</table>

						{{ $submissions->links() }}
					@else
						<p>No submissions yet!</p>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

@stop
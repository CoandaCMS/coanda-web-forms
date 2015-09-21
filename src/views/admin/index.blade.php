@extends('coanda::admin.layout.main')

@section('page_title', 'Forms')

@section('content')

<div class="row">

	<div class="breadcrumb-nav">
		<ul class="breadcrumb">
			<li><a href="{{ Coanda::adminUrl('forms') }}">Forms</a></li>
		</ul>
	</div>
</div>

<div class="row">
	<div class="page-name col-md-12">
		<h1 class="pull-left">Forms</h1>
		<div class="page-status pull-right">
			<span class="label label-default">Total {{ $forms->getTotal() }}</span>
		</div>
	</div>
</div>

<div class="row">
	<div class="page-options col-md-12">
		<a href="{{ Coanda::adminUrl('forms/add') }}" class="btn btn-primary">Add new form</a>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="page-tabs">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#forms" data-toggle="tab">Forms</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="forms">

                    @if (Session::has('form_removed'))
                        <div class="alert alert-success">
                            Form removed
                        </div>
                    @endif

					@if ($forms->count() > 0)
						<table class="table table-striped">
							@foreach ($forms as $form)
							<tr>
								<td><a href="{{ Coanda::adminUrl('forms/view/' . $form->id) }}">{{ $form->name }}</a></td>
								@set('submission_count', $form->submissions()->count())
								<td>{{ $submission_count }} @if ($submission_count == 1) submission @else submissions @endif</td>
								<td class="tight"><a href="{{ Coanda::adminUrl('forms/edit/' . $form->id) }}"><i class="fa fa-pencil-square-o"></i></a></td>
							</tr>
							@endforeach
						</table>

						{{ $forms->links() }}
					@else
						<p>No forms have been created yet, be the first!</p>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

@stop

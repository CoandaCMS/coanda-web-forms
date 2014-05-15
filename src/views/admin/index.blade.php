@extends('coanda::admin.layout.main')

@section('page_title', 'Web form submissions')

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
			<span class="label label-default">Total {{ $formpages->getTotal() }}</span>
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
				<li class="active"><a href="#forms" data-toggle="tab">Forms</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="forms">
					@if ($formpages->count() > 0)
						<table class="table table-striped">
							@foreach ($formpages as $formpage)
							<tr>
								<td><a href="{{ Coanda::adminUrl('forms/submissions/' . $formpage['page']->id) }}">{{ $formpage['page']->present()->name }}</a></td>
								<td class="tight">{{ $formpage['submissions'] }}</td>
							</tr>
							@endforeach
						</table>

						{{ $formpages->links() }}
					@else
						<p>No forms have had any submissions yet, when they do they will appear here!</p>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

@stop
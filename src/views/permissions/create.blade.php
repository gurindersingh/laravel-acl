@extends('acl::acl-master')

@section('acl::content')
	
	<div class="container mt-5">
		
		<div class="row">
			
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
						<h1>Create Permission</h1>
					</div>
					<div class="card-body">
						
						@if ($errors->any())
							<div class="alert alert-danger">
								<ul class="mb-0">
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif
						
						<form action="{{ route(config('acl.route_as') . 'permissions.store') }}" method="POST" class="">
							@include('acl::permissions._crud', ['buttonText' => 'Create'])
						</form>
					</div>
				</div>
			</div>
		
		
		</div>
	
	</div>

@endsection
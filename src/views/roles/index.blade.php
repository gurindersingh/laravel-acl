@extends('acl::acl-master')

@section('acl::content')
	
	<div class="container mt-5">
		
		<div class="row">
			
			<div class="col-lg-12">
				
				<h1>Roles</h1>
				
				<div class="{{ $roles->total() > 1 ? 'accordion' : '' }}" id="roles-list">
					@foreach($roles->items() as $role)
						<div class="card">
							<div class="card-header p-0" id="{{ $role->id }}-role">
								<h6 class="mb-0 p-3" data-toggle="collapse"
								    data-target="#{{ $role->id }}-role-body">
									{{ ucwords($role->name) }}
									<small>({{ $role->slug }})</small>
								</h6>
							</div>
							
							<div id="{{ $role->id }}-role-body"
							     class="collapse {{ $roles->total() > 1 ? '' : 'show' }}"
							     data-parent="#roles-list">
								<div class="card-body">
									
									@if(!$role->permissions->isEmpty())
										<h6 class="">Permissions attached to the role</h6>
										<hr>
										<div class="row">
											@foreach($role->permissions->chunk(5) as $chunk)
												
												<div class="col-lg-3">
													<ul class="mb-0">
														@foreach($chunk as $permission)
															<li>{{ $permission->name }}</li>
														@endforeach
													</ul>
												</div>
											
											@endforeach
										
										</div>
									
									@else
										<p class="mb-0">No Permission is attached to this role yet.</p>
									@endif
								
								</div>
								
								<div class="card-footer bg-white d-flex justify-content-between">
									<a href="{{ route(config('acl.route_as') . 'roles.edit', $role->id) }}"
									   class="btn btn-outline-primary btn-sm">Edit</a>
								</div>
							
							</div>
						</div>
					@endforeach
				</div>
				
				{{ $roles->links() }}
			
			
			</div>
		
		</div>
	
	</div>

@endsection
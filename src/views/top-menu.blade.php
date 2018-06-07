<header class="bg-primary">
	<div class="container">
		<div class="row">
			<nav class="navbar navbar-expand-lg navbar-dark w-100">
				<a class="navbar-brand" href="#">ACL</a>
				
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
					<span class="navbar-toggler-icon"></span>
				</button>
				
				<div class="collapse navbar-collapse " id="navbarSupportedContent">
					<ul class="navbar-nav ml-auto">
						<li class="nav-item">
							<a class="nav-link text-white"
							   href="{{ url(config('acl.back_link.url')) }}"
							>{{ config('acl.back_link.label') }}</a>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle text-white"
							   href="#"
							   data-toggle="dropdown">
								Roles
							</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a href="{{ route(config('acl.route_as') . 'roles.index') }}" class="dropdown-item ">All Roles</a>
								<a href="{{ route(config('acl.route_as') . 'roles.create') }}" class="dropdown-item">Add Role</a>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
								Permission
							</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a href="{{ route(config('acl.route_as') . 'permissions.index') }}" class="dropdown-item">All Permissions</a>
								<a href="{{ route(config('acl.route_as') . 'permissions.create') }}" class="dropdown-item">Add Permission</a>
							</div>
						</li>
						<li class="nav-item">
							<a href="{{ route(config('acl.route_as') . 'users.index') }}" class="nav-link text-white">Users</a>
						</li>
						@auth()
							<li class="nav-item">
								<a class="nav-link text-white"
								   href="#"
								   onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
									<svg
											xmlns="http://www.w3.org/2000/svg"
											width="24"
											height="24"
											viewBox="0 0 24 24"
											fill="none"
											stroke="currentColor"
											stroke-width="2"
											stroke-linecap="round"
											stroke-linejoin="round"
									>
										<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
										<polyline points="16 17 21 12 16 7" />
										<line x1="21" y1="12" x2="9" y2="12" />
									</svg>
									<form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
										{{ csrf_field() }}
									</form>
								</a>
							</li>
						@endauth
					</ul>
				</div>
			
			</nav>
		</div>
	</div>
</header>

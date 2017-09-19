<nav class="navbar navbar-expand-md navbar-dark bg-dark justify-content-start">


		<a class="navbar-brand" href="#">Coldreader</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#site-navbar" aria-controls="site-navbar" aria-expanded="false">
			<span class="navbar-toggler-icon"></span>
		</button>


	<div class="collapse navbar-collapse" id="site-navbar">

			<ul class="navbar-nav mr-auto">
				<li class="nav-item"> <a href="/home"  class="nav-link">Home</a> </li>
				<li class="nav-item"> <a href="/subject/4"  class="nav-link">News</a> </li>
				<li class="nav-item"> <a href="/subject_type"  class="nav-link">Subject Types</a> </li>
				<li class="nav-item"> <a href="/aspect_type" class="nav-link">Aspect Types</a> </li>
				
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<img src="{!!  $user_info['avatar'] !!}" style="width:24px;" />
						{{ Auth::user()->name }}
					</a>

					<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="background-color:#000;">
						<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							{{ csrf_field() }}
						</form>
					</div>

				</li>
				
				<li class="nav-item">@include('search.search_form')</li>

			</ul>

	</div>

</nav>

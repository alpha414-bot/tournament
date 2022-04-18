<div data-animation="over-left" data-collapse="all" data-duration="400" data-easing="ease-in-out-expo" data-easing2="ease-in-out-quart" data-doc-height="1" role="banner" class="navigation-bar w-nav">
      <div class="navigation-container w-container">
        <a href="#" class="w-nav-brand">
          <h1>{{$sitename}}</h1>
        </a>
        <nav role="navigation" class="nav-menu w-nav-menu">
          <div class="profile-details">
            <p class="greetings">Welcome, {{ucfirst(Auth::user()->username)}}!</p>
          </div>
          {{--<a href="#" class="nav-link w-nav-link">About</a>
          <a href="#" class="nav-link w-nav-link">Contact Us</a>--}}
          <a href="{{route('logout')}}" class="nav-link w-nav-link">Logout</a>
        </nav>
        <div class="menu-button w-nav-button">
          <div class="icon w-icon-nav-menu"></div>
        </div>
      </div>
</div>
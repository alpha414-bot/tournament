<div data-animation="over-left" data-collapse="medium" data-duration="400" data-easing="ease-in-out-expo" data-easing2="ease-in-out-quart" data-doc-height="1" role="banner" class="navigation-bar w-nav">
  <div class="navigation-container w-container">
    <a href="#" class="w-nav-brand">
      <h1>{{$sitename}}</h1>
    </a>
    <nav role="navigation" class="nav-menu admin w-nav-menu">
      {{--<a href="#" class="nav-link admin w-nav-link">{{$sitename}}s</a>--}}
      <a href="{{Request::root()}}/setting" class="nav-link admin w-nav-link">App Settings</a>
      <a href="{{Request::root()}}/logout" class="nav-link admin w-nav-link">Logout</a>
      <div class="profile-details admin">
        <p class="greetings admin">Welcome, {{ucwords(Auth::user()->username)}}!</p>
      </div>
    </nav>
    <div class="menu-button w-nav-button">
      <div class="icon w-icon-nav-menu"></div>
    </div>
  </div>
</div>
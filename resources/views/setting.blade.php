<x-app-layout>
  @include('layouts.navigationB')
  <div class="container">
    <h3>App Settings</h3>
    <div class="major-settings w-form">
      <form method="POST" action="{{route('sitename')}}" class="w-clearfix">
        {{csrf_field()}}
        @if($errors->any())
          <div class="error-message">
          @foreach($errors->all() as $error)
            <div>{{ucfirst($error)}}.</div>
          @endforeach
          </div>
        @endif
        @if(Session::has('status'))
          <div class="success-message" style="padding:4px;">
            <div>{{Session::get('status')}}</div>
          </div>
        @endif
        <div class="field-holder"><label for="app_name" class="label-field">App Name</label><input type="text" class="field w-input" autofocus="true" maxlength="256" name="app_name" data-name="app_name" placeholder="" id="app_name" required="" value="{{$sitename}}"></div><input type="submit" value="Save" data-wait="Saving" class="cta form w-button">
        <div class="link-block"></div>
        <div class="form-footer"></div>
      </form>
    </div>
    <div class="divider"></div>
    <h3>Account Settings</h3>
    <div class="major-settings w-form">
      <form class="w-clearfix" method="POST" action="{{route('settings.passwordreset')}}">
        <p style="text-align: center;">Please enter your default password, if you wish to change only your email address. And enter your default email address, if you wish to change only your password. On successful operation, you would be loggged out.</p>
        {{csrf_field()}}
        @if($errors->any())
          <div class="error-message">
          @foreach($errors->all() as $error)
            <div>{{ucfirst($error)}}.</div>
          @endforeach
          </div>
        @endif
        <input type="hidden" name="token" value="{{$reset_token}}">
        <div class="field-holder"><label for="email" class="label-field">Email Address</label><input type="" class="field w-input" maxlength="256" name="email" data-name="email" placeholder="" id="email" value="{{Auth::user()->email}}" required></div>
        <div class="field-holder"><label for="password" class="label-field">New Password</label><input type="password" class="field w-input" maxlength="256" name="password" data-name="password" placeholder="" id="password" required></div>
        <div class="field-holder"><label for="confirm_password" class="label-field">Confirm Password</label><input type="password" class="field w-input" maxlength="256" name="password_confirmation" data-name="password_confirmation" placeholder="" id="confirm_password" required></div><input type="submit" value="Save" data-wait="Saving" class="cta form w-button">
      </form>
    </div>
    <div class="foote">
      <p class="copyrights-text">Copryrights © 2021. Powered By {{$sitename}}</p>
    </div>
  </div>
</x-app-layout>
<x-guest-layout>
  <div class="wrapper">
    <div class="login-form w-form">
      <form class="w-clearfix" method="POST" action="{{ route('password.update') }}">
        {{csrf_field()}}
        @if($errors->any())
          <div class="error-message">
          @foreach($errors->all() as $error)
            <div>{{ucfirst($error)}}.</div>
          @endforeach
          </div>
        @endif
        <input type="hidden" name="email" value="{{$request->email}}">
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="field-holder"><label for="email-2" class="label-field">New Password</label><input type="password" class="field w-input" autofocus="true" maxlength="256" name="password" data-name="Email 2" placeholder="" id="email-2" required=""></div>
        <div class="field-holder"><label for="email-3" class="label-field">Confirm Password</label><input type="password" class="field w-input" autofocus="true" maxlength="256" name="password_confirmation" data-name="Email 2" placeholder="" id="email-2" required=""></div><input type="submit" value="Reset Password" data-wait="Resetting..." class="cta form w-button">
        <div class="form-footer">
          <p class="copyrights-text">Copryrights © 2021. Powered By {{$sitename}}</p>
        </div>
      </form>
    </div>
  </div>
</x-guest-layout>
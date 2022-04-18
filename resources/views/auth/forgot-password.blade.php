<x-guest-layout>
  <div class="wrapper">
    <div class="login-form w-form">
      <form class="w-clearfix" method="POST" action="{{ route('password.email') }}" autocomplete="off">
        {{csrf_field()}} 
        <p class="form-intro-paragraph">Have you forgotten your password. No worries, enter your associate email address linked to your account to get a reset link, or enter your username to get a reset link.</p>
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
        <div class="field-holder"><label for="email" class="label-field">Email Address</label><input type="email" class="field w-input" autofocus="true" maxlength="256" name="email" data-name="Email" placeholder="" id="email" required=""></div><input type="submit" value="Reset Password" data-wait="Resetting..." class="cta form w-button">
        <div class="form-footer">
          <p class="copyrights-text">Copryrights © 2021. Powered By {{$sitename}}</p>
        </div>
      </form>
    </div>
  </div>
</x-guest-layout>

<x-guest-layout>
  @auth
    <script>window.location = "{{Request::root()}}/dashboard";
  @endauth
    <div class="wrapper">
        <div class="login-form w-form">
          <form method="POST" class="w-clearfix" action="{{route('login')}}">
            {{csrf_field()}}
            @if($errors->any())
              <div class="error-message">
              @foreach($errors->all() as $error)
                <div>{{ucfirst($error)}}</div>
              @endforeach
              </div>
            @endif

            @if(Session::has('status'))
              <div class="success-message" style="padding:4px;">
                <div>{{Session::get('status')}}</div>
              </div>
            @endif
            <div class="field-holder"><label for="login" class="label-field">Email/Username</label><input type="text" class="field w-input" autofocus="true" maxlength="256" name="login" data-name="email" placeholder="" id="login" required=""></div>
            <div class="field-holder"><label for="password" class="label-field">Password</label><input type="password" class="field w-input" maxlength="256" name="password" data-name="password" placeholder="" id="password" required=""></div><input type="submit" value="Login" data-wait="Login..." class="cta form w-button">
            <div class="link-block">
              {{--<a href="{{Request::root()}}/register" class="link-glittering">Don&#x27;t have an account?</a>--}}
              <a href="{{Request::root()}}/forgot-password" class="link-glittering">Forgotten Password?</a>
            </div>
            <div class="form-footer">
              <p class="copyrights-text">Copryrights © 2021. Powered By {{$sitename}}</p>
            </div>
          </form>
        </div>
    </div>
</x-guest-layout>
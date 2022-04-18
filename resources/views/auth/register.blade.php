<x-guest-layout>
  <div class="wrapper">
    <div class="login-form w-form">
      <form class="form-2 w-clearfix" method="POST" action="{{route('register')}}">
        {{csrf_field()}}
        @if($errors->any())
          <div class="error-message">
          @foreach($errors->all() as $error)
            <div>{{ucfirst($error)}}.</div>
          @endforeach
          </div>
        @endif
        <div class="field-holder"><label for="name" class="label-field">Full Name</label><input type="text" class="field w-input" autofocus="true" maxlength="256" name="name" data-name="name" placeholder="John Doe" id="name" required=""></div>
        <div class="field-holder"><label for="username" class="label-field">Username</label><input type="text" class="field w-input" maxlength="256" name="username" data-name="username" placeholder="Username0123" id="username" required></div>
        <div class="field-holder"><label for="email" class="label-field">Email Address</label><input type="email" class="field w-input" maxlength="256" name="email" data-name="email" placeholder="gmail@gmail.com" id="email" required=""></div>
        <div class="field-holder"><label for="password" class="label-field">Password</label><input type="password" class="field w-input" maxlength="256" name="password" data-name="password" placeholder="6-8 character long" id="password" required=""></div>
        <div class="field-holder"><label for="password_confirmation" class="label-field">Confirm Password</label><input type="password" class="field w-input" maxlength="256" name="password_confirmation" data-name="confirm_password" placeholder="Reenter Password" id="password_confirmation" required=""></div><input type="submit" value="Join Now" data-wait="Joining..." class="cta form w-button">
        <div class="link-block registration">
          <a href="{{Request::root()}}/login" class="link-glittering">Have an account?  Click here to login</a>
        </div>
        <div class="form-footer">
          <p class="copyrights-text">Copryrights © 2021. Powered By {{$sitename}}</p>
        </div>
      </form>
    </div>
  </div>    
</x-guest-layout>

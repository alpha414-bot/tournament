@auth
@if(Auth::user()->role == 1)
	<script>window.location.href="{{Request::root()}}/admin/dashboard";</script>
@endif
@endauth
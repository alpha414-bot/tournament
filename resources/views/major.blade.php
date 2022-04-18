<x-app-layout>
@if(Session::has('done'))
    <script>window.relocation.reload()</script>
@endif
  <div data-w-id="ad940300-02ba-df9c-3b1b-d1d5697e4ab9" style="opacity:1" class="wrapper-dominator">
    @include('layouts.navigationB')
    <div class="chain-wrapper w-clearfix">
      <a data-w-id="bd4212b6-244f-dd4d-25da-7850692cad1f" href="#" class="cta add w-button">Add New Tournament</a>
      <div class="responsive-table">
        <div class="tablating">
          <div class="grid rectifier heading">
            <div class="table-heading">#</div>
            <div class="table-heading">Tournament Name</div>
            <div class="table-heading">Presenter</div>
            <div class="table-heading"></div>
          </div>
          @if(count($tournaments) > 0)
            @foreach($tournaments as $digit=>$tour)
              <div class="grid rectifier heading">
                <div class="table-primary">{{$digit+1}}</div>
                <div class="table-primary">{{$tour->tournament_name}}</div> @php $tournament_id = $tour->tournament_id @endphp
                <div class="table-primary">{{json_decode($tour->data)->tournaments->$tournament_id->full->presenter->name}}</div>
                <div class="table-primary" style="flex-direction:row;">
                    <a href="{{route('iframe.view', ['id'=>$tour->tournament_id])}}" class="table-link" style="display:inline-block;" target="_blank">View</a>&nbsp;
                  <a href="{{Request::root()}}/link/{{$tour->id}}/delete/{{$tour->tournament_id}}" class="table-link">Delete</a>
                </div>
              </div>
            @endforeach
          @endif
          <div class="grid rectifier heading">
            <div class="table-heading">#</div>
            <div class="table-heading">Tournament Name</div>
            <div class="table-heading">Presenter</div>
            <div class="table-heading"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div data-w-id="5f4db7fe-c177-af77-a514-6d428199adeb" style="display:none" class="new-tournaments">
    <div class="w-form">
      <form class="w-clearfix" method="POST" action="{{route('add tournament')}}" autocomplete="off">
        {{csrf_field()}}
        @if($errors->any())
          <div class="error-message" style="padding:4px;">
          @foreach($errors->all() as $error)
            <div>{{ucfirst($error)}}.</div>
          @endforeach
          </div>
        @endif
        <input type="text" class="url-field w-input" autofocus="true" maxlength="256" name="link" data-name="link" placeholder="Paste Tournament Link Here" id="link" required="">
        <a data-w-id="e3060412-37e8-48ec-20c9-1e341f654046" href="#" class="cta cancel w-button">Cancel</a><input type="submit" value="Save" data-wait="Saving" class="cta save w-button">
      </form>
    </div>
  </div>
</x-app-layout>
<x-app-layout>
    <x-balancing/>
{{-- @include('layouts.navigation')--}}
  <div class="block">
        <div style="margin-top: 68px; margin-bottom: 68px;">
          <a class="cta form w-button" href="{{route("login")}}">Login</a>
        </div>
        
        @if(count($tournaments)>0)
            @foreach($tournaments as $tour)
                <div class="grid">
                  <div class="tournament-div">
                    @php
                        $tournament_id = $tour->tournament_id;
                    @endphp
                    <h4 class="title">Tournament Detail</h4>
                    <div class="details-start">
                        <div class="detail-parameter">Tournament Name</div>
                        <div class="details-value">{{$tour->tournament_name}}</div>
                      <div class="detail-parameter">Presenter:</div>@php $url = json_decode($tour->data)->tournaments->$tournament_id->full @endphp
                      <div class="details-value"><a href="@if(isset($url->presenter->url)){{$url->presenter->url}}" target="_blank" @else #" @endif>{{isset(json_decode($tour->data)->tournaments->$tournament_id->full)?json_decode($tour->data)->tournaments->$tournament_id->full->presenter->name:'___'}}</a></div>
                      @if(isset(json_decode($tour->data)->tournaments->$tournament_id->full->location))
                        <div class="detail-parameter">Location:</div>
                        <div class="details-value">{{json_decode($tour->data)->tournaments->$tournament_id->full->location->name}}, {{json_decode($tour->data)->tournaments->$tournament_id->full->location->streetnum}}, {{json_decode($tour->data)->tournaments->$tournament_id->full->location->zip}} {{json_decode($tour->data)->tournaments->$tournament_id->full->location->city}}</div>
                      @endif
                      <div class="detail-parameter">Match Duration in Group Phase</div>
                      <div class="details-value">
                          @if(isset(json_decode($tour->data)->tournaments->$tournament_id->full->groupMatchDuration))
                            {{json_decode($tour->data)->tournaments->$tournament_id->full->groupMatchDuration}} minutes
                          @endif
                      </div>
                      <div class="detail-parameter">Match Duration in Final Phase</div>
                      <div class="details-value">
                          @if(isset(json_decode($tour->data)->tournaments->$tournament_id->full->finalMatchDuration))
                            {{json_decode($tour->data)->tournaments->$tournament_id->full->finalMatchDuration}} minutes
                          @endif                          
                      </div>
                      <div class="detail-parameter">Date and time:</div>
                      <div class="details-value">{{isset(json_decode($tour->data)->tournaments->$tournament_id->full)?Carbon::parse(json_decode($tour->data)->tournaments->$tournament_id->full->startDateAndTime)->toDayDateTimeString():'_____'}} - {{isset(json_decode($tour->data)->tournaments->$tournament_id->full)?Carbon::parse(json_decode($tour->data)->tournaments->$tournament_id->full->endDateAndTime)->format('h:i A'):'__:__'}}</div>
                    </div>
                  </div>
                  <a href="{{route('iframe.view', ['id'=>$tournament_id]) }}" class="main-linker" target="_blank">Click Here To View!</a>
                </div>
            @endforeach
        @else
            <div class="grid">
              <div class="tournament-div">
                <h4 class="title">Nothing to display yet!</h4>
              </div>
                <a href="javascript:voidd(0)" class="main-linker">Click Here To View!</a>
            </div>
        @endif
  </div>
</x-app-layout>

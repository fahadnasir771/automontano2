@foreach ($chats as $chat)
  
    {{-- Me --}}
    <div class="chat {{ ($chat->from === Auth::id() ? '' : 'chat-left' ) }} ">
        <div class="chat-avatar">
            <a class="avatar m-0" data-toggle="tooltip" href="#" data-placement="{{ ($chat->from === Auth::id() ? 'left' : 'right' ) }}" title=""
                data-original-title="">
                <img src="{{ ($chat->from === Auth::id() ?  Gravatar::src(Auth::user()->email) : Gravatar::src($to->email) ) }}" alt="avatar" height="40" width="40" />
            </a>
        </div>
        <div class="chat-body">
            <div class="chat-content">
                <div>
                    <p>{{$chat->message}}</p>
                    <small style="font-style: italic;float:right;font-weight: 600;margin-top: 3px">{{ date('d M y, h:i a', strtotime($chat->created_at)) }}</small>
                </div>
                
            </div>
            
        </div>
    </div>

@endforeach
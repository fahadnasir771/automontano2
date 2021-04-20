@extends('layouts/contentLayoutMaster')

@section('title', 'Chat')

@section('vendor-style')
        <!-- vendor css files -->
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.css')) }}">
@endsection
@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset(mix('css/pages/app-chat.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/plugins/extensions/toastr.css')) }}">
@endsection

@section('content-sidebar')
    <!-- Chat Sidebar area -->
    <div class="sidebar-content card">

        <div class="chat-fixed-search">
            <div class="d-flex align-items-center">

                {{-- Authenticated user avatar --}}
                <div class="position-relative d-inline-flex">
                    <div class="avatar">
                        {{-- {{ asset('images/portrait/small/avatar-s-11.jpg') }} --}}
                        <img src="{{ Gravatar::src(Auth::user()->email) }}" id="auth-avatar" alt="user_avatar" height="40" width="40">
                        <span class="avatar-status-online"></span>
                    </div>
                    <div class="bullet-success bullet-sm position-absolute"></div>
                </div>

                {{-- Search for contacts --}}
                <fieldset class="form-group position-relative has-icon-left mx-1 my-0 w-100">
                    <input type="text" class="form-control round" id="chat-search" placeholder="Search or start a new chat">
                    <div class="form-control-position">
                        <i class="feather icon-search"></i>
                    </div>
                </fieldset>
            </div>

        </div>

        <div id="users-list" class="chat-user-list list-group position-relative">
            <h3 class="primary p-1 mb-0">Chats</h3>
            <ul class="chat-users-list-wrapper media-list">

                {{-- Users | Chats --}}
                @foreach ($users as $user)
                    @php
                        if($user->role == 4){
                          continue;
                        }
                    @endphp
                    <li class="user" id="{{ $user->id }}">
                        <div class="pr-1">
                            <span class="avatar m-0 avatar-md"><img class="media-object rounded-circle profile-image" 
                                    src="{{ Gravatar::src($user->email) }}" height="42" width="42"
                                    alt="Generic placeholder image">
                                <i></i>
                            </span>
                        </div>

                        <div class="user-chat-info">
                            <div class="contact-info">
                                <h5 class="font-weight-bold mb-0 active-user-name">{{ $user->name }}</h5>
                                {{-- <p class="truncate">Click to Chat</p> --}}
                            </div>
                            <div class="contact-meta">
                                {{-- <span class="float-right mb-25">4:14 PM</span> --}}
                                @if ($user->unread)
                                    <span class="badge badge-primary badge-pill float-right pending">{{ $user->unread }}</span>
                                @endif
                                
                            </div>
                        </div>
                    </li>
                @endforeach

            </ul>

        </div>
    </div>
    <!--/ Chat Sidebar area -->

@endsection


@section('content')
    <div class="chat-overlay"></div>

    <form action="" id="csrf" method="POST">
      @csrf
    </form>
    
    <section class="chat-app-window">
        <div class="start-chat-area">
            <span class="mb-1 start-chat-icon feather icon-message-square"></span>
            <h4 class="py-50 px-1 sidebar-toggle start-chat-text">Start Conversation</h4>
        </div>
        <div class="active-chat d-none">

            <div class="chat_navbar">
                <header class="chat_header d-flex justify-content-between align-items-center p-1">
                    <div class="vs-con-items d-flex align-items-center">
                        <div class="sidebar-toggle d-block d-lg-none mr-1"><i class="feather icon-menu font-large-1"></i>
                        </div>
                        <div class="avatar m-0 m-0 mr-1">
                            <img src="" id="profile-image" alt="" height="40" width="40" />
                            <span class="avatar-status-busy" id="chat_status"></span>
                        </div>
                        <h4 class="mb-0" id="receiver-name"></h4>
                    </div>
                    {{-- <span class="favorite"><i class="feather icon-star font-medium-5"></i></span> --}}
                </header>
            </div>

            <div class="user-chats">
                <div class="chats" id="chats">
                  
                </div>
            </div>

            {{-- Write Message --}}
            <div class="chat-app-form row">
                {{-- <form class="chat-app-input d-flex" action="javascript:void(0);"> --}}
                  <div class="col-10">
                    <input type="text" class="form-control message ml-50" id="chat-text"
                        placeholder="Type your message">
                  </div>
                  <div class="col-2" >
                    <button type="button" class="btn btn-primary send" style="width: 100%"><i
                      class="fa fa-paper-plane-o d-lg-none"></i> <span class="d-none d-lg-block">Send</span></button>
                  </div>
                    
                    
                {{-- </form> --}}
            </div>

        </div>
    </section>

@endsection

@section('page-script')
    <!-- Page js files -->
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="{{ asset(mix('js/scripts/pages/app-chat.js')) }}"></script>
    <script>

      var receiver_id = '';
      var my_id = "{{ Auth::id() }}";

      // Enable pusher logging - don't include this in production
      Pusher.logToConsole = true;

      var pusher = new Pusher('3a5e5ed0226f48310b94', {
        cluster: 'ap2'
      });
      var channel = pusher.subscribe('my-channel');

      // Pusher chat post
      channel.bind('chat', function(data) {
        if(my_id == data.from){ //user who sends hte message
          $('#' + data.to).click();
        }else if(my_id == data.to){ //user who receives the message

          if(receiver_id == data.from){ // chatbox of the user is opened who sent the msg
            $('#' + data.from).click();
          }else{
            
            var pending = parseInt($('#'+ data.from).find('.pending').text());

            if(pending){
              $('#' + data.from).find('.pending').html(pending + 1);
            }else{
              $('#' + data.from).find('.contact-meta').append('<span class="badge badge-primary badge-pill float-right pending">1</span>');
            }

          }
        }
      });

      // Pusher online status
      channel.bind('chat_status', function(data){
        if(data.reciever_id == receiver_id){
          if(data.online == 1){
              $('#chat_status').removeClass('avatar-status-busy');
              $('#chat_status').addClass('avatar-status-online');
              toastr.success('', $('#' + receiver_id).find('.active-user-name').text() + ' is Online' )
            }else{
              $('#chat_status').removeClass('avatar-status-online');
              $('#chat_status').addClass('avatar-status-busy');
              toastr.error('', $('#' + receiver_id).find('.active-user-name').text() + ' is Offline' )
            }
        }
      });

      //getting chats of user
      $(".user").on('click', function() {
        receiver_id = $(this).attr('id');
        
        $('#profile-image').attr('src', $(this).find('.profile-image').attr('src'))
        $('#receiver-name').html($(this).find('.active-user-name').text());

        if ($('.chat-user-list ul li').hasClass('active')) {
            $('.chat-user-list ul li').removeClass('active');
        }
        $(this).addClass("active");
        $(this).find(".badge").remove();

        if ($('.chat-user-list ul li').hasClass('active')) {
            $('.start-chat-area').addClass('d-none');
            $('.active-chat').removeClass('d-none');
        } else {
            $('.start-chat-area').removeClass('d-none');
            $('.active-chat').addClass('d-none');
        }
        
        $.ajax({
          'type': 'GET',
          'url': 'chat/' + receiver_id,
          'data': "",
          'cache': false,
          success: function(data){
            $('#chats').html(data);
            $(".user-chats").animate({ scrollTop: $(".user-chats > .chats").height() }, 300);

            //chatbox avatars
            $('.chat-right').find('.chatbox-avatar').attr('src', $('#auth-avatar').attr('src'));
            $('.chat-left').find('.chatbox-avatar').attr('src', $('#profile-image').attr('src'))
          }
        });
        
        chat_status();

      });

      //chat status funciton
      function chat_status() {
        $.ajax({
          'type': 'GET',
          'url': 'chat_status/' + receiver_id,
          'data': "",
          'cache': false,
          success: function(data){
            if(data.chat_status == 1){
              $('#chat_status').removeClass('avatar-status-busy');
              $('#chat_status').addClass('avatar-status-online');
            }else{
              $('#chat_status').removeClass('avatar-status-online');
              $('#chat_status').addClass('avatar-status-busy');
            }
          }
        })
      }

      //send
      $('.send').on('click', function(){
        var message = $('#chat-text').val();
        sendMessage(message);
      });

      $('#chat-text').on('keyup', function(e){
        var message = $('#chat-text').val();
        if(e.which == 13){
          sendMessage(message);
        }
        
      });

      function sendMessage(message){
        if(message != '' && receiver_id != ''){
          $(".user-chats").animate({ scrollTop: $(".user-chats > .chats").height() }, 300);
          $('#chat-text').val('');
          $.ajax({
            'type': 'POST',
            'url': 'chat',
            'cache': false,
            'data': {
              '_token': $('[name="_token"]').val(),
              'receiver_id': receiver_id,
              'message': message
            },
            success: function() {

            },
            complete: function() {
              
            }
          }); 
        }
      }

    </script>
@endsection
@section('vendor-script')
        <!-- vendor files -->
        <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection
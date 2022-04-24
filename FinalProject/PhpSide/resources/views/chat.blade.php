@extends('layouts.basic')
@extends('layouts.sidebarElements')
@section('title', 'Directs')
@section('content')
<link rel="stylesheet" href="{{$home}}{{$nodePort}}/stylesheets/directs.css"/>
<div class="container">
    <div class="ks-page-content">
        <div class="ks-page-content-body">
            <div class="ks-messenger">
                <div class="ks-discussions">
                    <div class="ks-search">
                        <div class="input-icon icon-right icon icon-lg icon-color-primary">
                            <input id="search-username" type="text" class="form-control" placeholder="Enter a username">
                            <span class="icon-addon">
                                <span class="la la-search"></span>
                            </span>
                        </div>
                    </div>
                    <div class="ks-body ks-scrollable jspScrollable" data-auto-height="" style="height: 85vh; overflow-y: auto; padding: 0; overflow-x: hidden" tabindex="0">
                        <div class="jspContainer" style="width: 339px; height: 550px;">
                            <div class="jspPane" style="padding: 0; top: 0; width: 329px;">
                                <ul class="ks-items" id="current-chats">
{{--                                    <li class="ks-item" name="chat" id=1 onclick="">--}}
{{--                                        <a>--}}
{{--                                            <span class="ks-group-amount"><img style="border-radius: 20px" src=""/></span>--}}
{{--                                            <div class="ks-body">--}}
{{--                                                <div class="ks-name">--}}
{{--                                                    <p>TestChat</p>--}}
{{--                                                    <span class="ks-datetime">just now</span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
                                </ul>
                                <ul class="ks-items" id="searched-usernames" style="display: none"></ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ks-messages ks-messenger__messages" style="height: 90vh;">
                    <div class="ks-header">
                        <div class="ks-description">
                            <h2 class="ks-name" id="chat-name"></h2>
                            <div class="ks-amount" id="chat-members"></div>
                        </div>
                        <div class="ks-controls">
                            <div class="dropdown">
                                <button class="btn btn-primary-outline ks-light ks-no-text ks-no-arrow" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="la la-ellipsis-h ks-icon"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="ks-body ks-scrollable jspScrollable" data-auto-height="" data-reduce-height=".ks-footer" data-fix-height="32" style="overflow-y: scroll; overflow-x: hidden; padding: 0; width: 701px;" tabindex="0" id="messagesDiv">
                        <div class="jspContainer" style="width: 701px; height: 481px;">
                            <div class="jspPane" style="padding: 0; top: 0; width: 691px;">
                                <ul class="ks-items" id="messages"></ul>
                            </div>
                        </div>
                    </div>
                    <div class="ks-footer">
                        <input type="text"  style="width: 200%" id="text-message" class="form-control shadow-none" placeholder="Type something...">
                        <div class="ks-controls">
                            <button class="btn btn-primary" id="send">Send</button>
                            <a href="#" class="la la-paperclip ks-attachment"></a>
                            <div class="dropdown dropup">
                                <button class="btn btn-link ks-smile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="la la-smile-o"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
<script defer>
    (()=>{
        let currentChat = undefined;
        let oldHeight = $('#messages').height();
        let running = false;
        let chats = {};
        startApp();
        pusher.subscribe('private-user.' + {{Auth::id()}}).bind('App\\Events\\NewChat', async (data)=>{
            const chat = await getChatById(data.chatting.chat_id);
            console.log(chat);
            chat['chat_id'] = chat.id;
            await createChat(chat);
        })

        async function startApp(){
            chats = await getChats();
            for (let i = 0; i < chats.length; i++) {
                await createChat(chats[i]);
            }
        }
        document.getElementById('text-message').addEventListener('keyup', async function(e) {
            if(e.keyCode === 13) {
                await sendMessage(document.getElementById('text-message').value);
            }
        });
        document.getElementById('send').addEventListener('click', async function(e) {
            await sendMessage(document.getElementById('text-message').value);
        });

        document.getElementById('search-username').addEventListener('input', async function() {
            if(running) return;
            await removeChild(document.getElementById('searched-usernames'));
            const usersDiv = document.getElementById('current-chats');
            if(document.getElementById('search-username').value === '' ) {
                document.getElementById('searched-usernames').style.display = 'none';
                document.getElementById('current-chats').style.display = 'block';
                await removeChild(usersDiv);
                await startApp();
                return;
            }
            running = true;
            document.getElementById('searched-usernames').style.display = 'block';
            document.getElementById('current-chats').style.display = 'none';
            const users = await searchForUsers(document.getElementById('search-username').value);
            await createSearchChat(users);
            await removeChild(usersDiv);
            running = false;
        });
        $(document).on('click', 'li[name="chat"]', async function (){
            if(currentChat !== undefined)
                document.getElementById(currentChat).classList.remove('ks-active');
            currentChat = $(this).attr('id');
            await connect();
            document.getElementById(currentChat).classList.add('ks-active');
            await removeMessages();
            const messages = await getMessages(currentChat);
            createMessages(messages);

            $('#messagesDiv').animate({
                scrollTop: $('#messages').height()
            }, 0);
        })
        $(document).on('click', 'li[name="search-chat"]', async function (){
            const selectedUser = $(this).attr('id');
            document.getElementById('searched-usernames').style.display = 'none';
            document.getElementById('search-username').value = '';

            await removeChild(document.getElementById('current-chats'));
            document.getElementById('current-chats').style.display = 'block';
            const response = await createPrivateChat(selectedUser);
            await startApp();
            document.getElementById(response[1].id).click();
            await removeChild(document.getElementById('searched-usernames'));
        })

        async function removeMessages(){
            const messages = document.getElementById('messages');
            await removeChild(messages);
        }
        async function removeChild(element){
            while(element.firstChild) {
                element.removeChild(element.firstChild);
            }
        }
        async function sendMessage(message){
            const response = await fetch('{{$home . $phpPort}}/chat/'+ currentChat + '/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    'message': message
                })
            });
            if (response.status === 201){
                document.getElementById('text-message').value = '';
                const data = await response.json();
                oldHeight = $('#messages').height();
                await createMessage(data);
                let distance = $('#messagesDiv').scrollTop() + $('#messagesDiv').height() - $('#messages').height();
                if($('#messages').height() !== oldHeight && distance > -300){
                    $('#messagesDiv').animate({
                        scrollTop: $('#messages')[0].scrollHeight
                    }, 500);
                }
            }
        }

        function createMessage(message){
            const messagesContainer = document.getElementById('messages');
            const user_id = {{Auth::id()}};

            $(messagesContainer).append(
                `<li class="ks-item ${user_id === message.owner_id ? 'ks-from' : 'ks-self'}" id="${message.id}">
                    <div class="ks-body">
                        <div class="ks-header">
                            <span class="ks-name">${message.user.username}</span>
                            <span class="ks-datetime">${message.created_at}</span>
                        </div>
                        <div class="ks-message">${message.message}</div>
                    </div>`
            );
        }
        function createMessages(messages){
            for (let i = 0; i < messages.length; i++) {
                createMessage(messages[i]);
            }
        }
        async function createChat(chat){
            if(!chat.is_group){
                const otherMember = await getOtherMemberInfo(await getChatMembersByChatId(chat.chat_id));
                $('#current-chats').append(
                    `<li class="ks-item" name="chat" id=${chat.chat_id}>
                    <a>
                        <span class="ks-group-amount"><img onerror="setDefaultImage(this)" style="border-radius: 20px" src="images/${otherMember['id']}.${otherMember['image_format']}"/></span>
                        <div class="ks-body">
                            <div class="ks-name">
                                <p>${otherMember['username']}</p>
                            </div>
                    </div>
                    </a>
                </li>`
                )
            }
        }
        async function createSearchChat(users){
            for (let i = 0; i < users.length; i++) {
                const user = users[i];
                $('#searched-usernames').append(
                    `<li class="ks-item" name="search-chat" id=${user.id}>
                    <a>
                        <span class="ks-group-amount"><img onerror="setDefaultImage(this)" style="border-radius: 20px" src="images/${user.id}.${user.image_format}"/></span>
                        <div class="ks-body">
                            <div class="ks-name">
                                <p>${user.username}</p>
                            </div>
                    </div>
                    </a>`);
            }
        }


        async function getOtherMemberInfo(members){
            const output = {};
            for (let i = 0; i < members.length; i++) {
                if(members[i].user_id !== {{Auth::id()}}){
                    output['username'] = members[i].user.username;
                    output['id'] = members[i].user.id;
                    output['image_format'] = members[i].user.image_format;
                    return output;
                }
            }

        }
        async function getChatMembersByChatId(id){
            const response = await fetch('{{$home . $phpPort}}/chat/'+id+'/members')
            return await response.json();
        }
        async function getChats(){
            const response = await fetch('{{$home . $phpPort}}/chats');
            return await response.json();
        }
        async function getChatById(id){
            const response = await fetch('{{$home . $phpPort}}/chat/'+id);
            return await response.json();
        }
        async function createPrivateChat(member){
            const response = await fetch('{{$home . $phpPort}}/chat/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                body: JSON.stringify({
                    'user_id': member,
                    'is_group': false
                })
            });
            return await response.json();
        }

        async function getMessages(id){
            const response = await fetch('{{$home . $phpPort}}/chat/'+id + '/messages');
            return await response.json();
        }
        async function searchForUsers(username){
            const response = await axios.get(
                '{{$home . $phpPort}}/user/search/'+username
            );
            return response.data;
        }
        async function connect(){
            if (currentChat !== undefined) {
                window.Echo.private('chat.' + currentChat)
                    .listen('newChatMessage', function (data) {
                        console.log(data);
                        if(data['message'].owner_id !== {{Auth::id()}}){
                            oldHeight = $('#messages').height();
                            createMessage(data['message']);
                            let distance = $('#messagesDiv').scrollTop() + $('#messagesDiv').height() - $('#messages').height();
                            if($('#messages').height() !== oldHeight && distance > -300){
                                $('#messagesDiv').animate({
                                    scrollTop: $('#messages')[0].scrollHeight
                                }, 500);
                            }
                        }
                    });

            }
        }
    })();
</script>

@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/app.js"></script>
@endpush
@stop


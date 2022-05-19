@extends('layouts.basic')
@section('title', 'Create Group')
@extends('layouts.sidebarElements')
@section('content')
    <link rel="stylesheet" href="{{$home}}{{$nodePort}}/stylesheets/directs.css"/>
    <link rel="stylesheet" href="{{$home}}{{$nodePort}}/stylesheets/createGroup.css"/>
        <div class="container">
            <div class="lds-ring" id="loading-all" style="display: none; top: 50%;left: 50%; position: absolute;"><div></div><div></div><div></div><div></div></div>
            <div class="ks-page-content">
                <div class="ks-page-content-body">
                    <div class="ks-messenger">
                        <div class="ks-discussions">
                            <div class="ks-search">
                                <div class="input-icon icon-right icon icon-lg icon-color-primary d-flex" >
                                    <input id="search-username" type="text" class="form-control" placeholder="Enter a username to search">
                                    <span class="icon-addon">
                                        <span class="la la-search"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="ks-body ks-scrollable jspScrollable" data-auto-height="" style="height: 77vh; overflow-y: auto; padding: 0; overflow-x: hidden" tabindex="0">
                                <div class="jspContainer" style="padding-top: 10px">
                                    <div class="jspPane" style="padding: 0; top: 0;">
                                        <ul class="ks-items" id="current-chats">
                                            <li><h3>Select the users</h3></li>
                                        </ul>
                                        <ul class="ks-items" id="searched-usernames" style="display: none">
                                        </ul>
                                    </div>
                                </div>
                                <ul class="ks-items justify-content-center" id="loading-chats" style="display: flex">
                                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                </ul>
                            </div>
                            <div class="d-flex" style="justify-content: center">
                                <button type="button" style="background: var(--bs-light); color: black; width: 80%" id="goBack" class="btn btn-primary btn-block shadow-none border-0">
                                    Return to chats
                                </button>
                            </div>
                        </div>
                        <div class="ks-messages ks-messenger__messages" style="height: 90vh; width: 50%;">
                            <div class="ks-header">
                                <div class="ks-description">
                                    <h2 class="ks-name" id="chat-name"></h2>
                                    <div class="ks-amount" id="chat-members"></div>
                                </div>
                            </div>
                            <div class="form-body">
                                <div class="ks-body ks-scrollable jspScrollable" data-auto-height="" data-reduce-height=".ks-footer" data-fix-height="32" style="overflow-x: hidden; padding: 0; width: 100%;" tabindex="0" id="messagesDiv">
                                    <div class="jspContainer">
                                        <div class="form-content">
                                            <div class="form-items">
                                                <h3>Create your group</h3>
                                                <div class="col-md-12">
                                                    <input class="form-control shadow-none" type="text" id="name" name="name" placeholder="Group name" required>
                                                </div>
                                                <div class="form-button mt-3">
                                                    <button id="submit" class="btn btn-primary">Create group</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <script defer>
        document.getElementById('goBack').addEventListener('click', () => {
            window.location.replace('{{$home}}{{$phpPort}}/chat')
        });
        (()=>{
            let running = false;
            let lastText = '';
            const selectedUsersIds = [];
            startApp();
            document.getElementById('search-username').addEventListener('input', async () =>{
                if(running) return;
                const text = document.getElementById('search-username').value;
                const searchedUsers = document.getElementById('searched-usernames')
                await removeChild(searchedUsers);
                const usersDiv = document.getElementById('current-chats');
                if(text === '' || text.trim() === '') {
                    if (lastText === '') return;
                    lastText = '';
                    await removeChild(usersDiv);
                    document.getElementById('searched-usernames').style.display = 'none';
                    document.getElementById('current-chats').style.display = 'block';
                    startApp();
                    return;
                }

                lastText = text;
                running = true;
                const loading = document.getElementById('loading-chats');
                await removeChild(document.getElementById('current-chats'));

                loading.style.display = 'flex';
                document.getElementById('current-chats').style.display = 'none';

                const searched = await searchForUsers(text);
                await createSearchedUsers(searched);

                loading.style.display = 'none';
                // document.getElementById('current-chats').style.display = 'block';
                document.getElementById('searched-usernames').style.display = 'block';
                running = false;
            })

            document.getElementById('submit').addEventListener('click', ()=>{
                const groupName = document.getElementById('name').value;

                if(groupName === ''){
                    return;
                }
                document.getElementsByClassName('ks-page-content')[0].style.display = 'none';
                document.getElementById('loading-all').style.display = 'block';
                createGroup(selectedUsersIds, groupName).then(data=>{
                    window.location.replace('{{$home}}{{$phpPort}}/chat');
                });
            });

            function startApp(){
                getUsers(50).then(() =>{
                    const users = document.getElementsByName('userSelect');
                    for (const user of users) {
                        user.addEventListener('click', ()=>{
                            user.classList.toggle('ks-active');
                            if(user.classList.contains('ks-active')){
                                selectedUsersIds.push(parseInt(user.id));
                            }
                            else{
                                selectedUsersIds.splice(selectedUsersIds.indexOf(parseInt(user.id)), 1);
                            }
                        })
                    }
                })
            }
            async function removeChild(element){
                while(element.firstChild) {
                    element.removeChild(element.firstChild);
                }
            }
            async function createSearchedUsers(searched){
                for (const user of searched) {
                    $('#searched-usernames').append(
                        `<li class="ks-item ${selectedUsersIds.includes(user.id) ? 'ks-active' : ''}" name="chat" id=${user.id}>
                        <a>
                            <span class="ks-group-amount">
                                <img loading="lazy" alt="" onerror="setDefaultImage(this)" style="width: 36px; height: 36px;" class="rounded-circle" src="{{$home}}{{$phpPort}}/images/${user.id}.${user.image_format}"/>
                            </span>
                            <div class="ks-body">
                                <div class="ks-name">
                                    <p>${user.username}</p>
                                </div>
                        </div>
                        </a>
                    </li>`)
                    $(`#${user.id}`).click(()=>{
                        const element = $(`#${user.id}`)[0];
                        element.classList.toggle('ks-active');
                        if(element.classList.contains('ks-active')){
                            selectedUsersIds.push(user.id);
                        }
                        else{
                            selectedUsersIds.splice(selectedUsersIds.indexOf(user.id), 1);
                        }
                    })
                }
            }

            /* Api integrations */

            function getUsers(limit){
                return new Promise((resolve) =>{
                    fetch('{{$home}}{{$phpPort}}/group/get-users', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                        },
                        body: JSON.stringify({
                            limit: limit
                        })
                    }).then(data => data.json()).then(data => {
                        data.forEach(user => {
                            const html = `<li class="ks-item ${selectedUsersIds.includes(user.id) ? 'ks-active' : ''}" name="userSelect" id=${user.id}>
                                        <a>
                                            <span class="ks-group-amount"><img loading="lazy" alt="" onerror="setDefaultImage(this)" style="width: 36px; height: 36px;" class="rounded-circle" src="{{$home}}{{$phpPort}}/images/${user.id}.${user.image_format}"/></span>
                                            <div class="ks-body">
                                                <div class="ks-name">
                                                    <p>${user.username}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>`;
                            $('#current-chats').append(html);
                        });
                        $('#loading-chats').hide();
                        resolve();
                    });
                })

            }
            function createGroup(users, name){
                return new Promise((resolve) =>{
                    fetch('{{$home}}{{$phpPort}}/group/create', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            'users': users,
                            'name': name,
                            'is_group': true
                        })
                    }).then(data => data.json()).then(data => {
                        resolve(data);
                    });
                })
            }
            async function searchForUsers(username){
                if(username === "") return;
                const response = await fetch(
                    '{{$home . $phpPort}}/user/search/'+username
                );
                return response.json();
            }

        })();
    </script>
@endsection

@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
@endpush


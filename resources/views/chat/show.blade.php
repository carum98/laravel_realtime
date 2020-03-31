@extends('layouts.app')
@push('styles')
    <style type="text/css">
        #users > li {
            cursor: pointer;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">Chat</div>
                    <div class="card-body">
                        <div class="row p-2">
                            <div class="col-10">
                                <div class="row">
                                    <div class="col-12 border rounded-lg p-3">
                                        <ul
                                            class="list-unstyled overflow-auto"
                                            style="height: 45vh"
                                            id="messages"
                                        >
                                        </ul>
                                    </div>
                                </div>
                                <form class="row py-3">
                                    <div class="col-10">
                                         <input id="message" type="text" class="form-control">
                                    </div>
                                    <div class="col-2">
                                        <button type="submit" id="send" class="btn btn-primary btn-block">Enviar</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-2">
                                <p><strong>Online</strong></p>
                                <ul id="users" class="list-unstyled overflow-auto text-info" style="height: 45vh">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const usersElement = document.getElementById('users');
        const messagesElement = document.getElementById('messages');

        Echo.join('chat')
            .here((users) => {
                users.forEach((user, index) => {
                    let element = document.createElement('li');
                    element.setAttribute('id', user.id);
                    element.setAttribute('onclick', 'greetUser("' + user.id + '")');
                    element.innerText = user.name;
                    usersElement.appendChild(element);
                });
            })
            .joining((user) => {
                let element = document.createElement('li');
                element.setAttribute('id', user.id);
                element.setAttribute('onclick', 'greetUser("' + user.id + '")');
                element.innerText = user.name;
                usersElement.appendChild(element);
            })
            .leaving((user) => {
                let element = document.getElementById(user.id);
                element.parentNode.removeChild(element);
            })
            .listen('MessageSend', (e) => {
                let element = document.createElement('li');
                element.innerText = e.user.name + ': ' + e.message;
                messagesElement.appendChild(element);
            })
    </script>

    <script>
        const sendElement = document.getElementById('send');
        const messageElement = document.getElementById('message');
        sendElement.addEventListener('click', (e) => {
            e.preventDefault();
            window.axios.post('/chat/message', {
                message: messageElement.value
            });
            messageElement.value = '';
        });
    </script>
    <script>
        function greetUser(id)
        {
            window.axios.post('/chat/greet/' + id);
        }
    </script>
    <script>
        Echo.private('chat.greet.{{ auth()->user()->id }}')
            .listen('GreatingSent', (e) => {
                let element = document.createElement('li');
                element.innerText = e.message;
                element.classList.add('text-success');
                messagesElement.appendChild(element);
            });
    </script>
@endpush
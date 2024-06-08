@extends('index')

@section('content')
  <div class="d-flex justify-content-between">
    <div class="dropdown d-flex">
      <div>
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
          Arrange by
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
          <li><button class="dropdown-item" onclick="arrangeBy('Date', 1)" type="button">Date</button></li>
          <li><button class="dropdown-item" onclick="arrangeBy('A-Z', 1)" type="button">Alphabetical order</button></li>
          <li><button class="dropdown-item" onclick="arrangeBy('Size', 1)" type="button">Size</button></li>
        </ul>
      </div>
      <div id="arrangByIcon" style="margin-left: 20px; margin-top: 5px; cursor: pointer;">
      </div>
    </div>
    <button data-bs-toggle="modal" data-bs-target="#modal" class="btn btn-outline-primary" style="width: 100px">See Pairs</button>
  </div>

  <div class="modal" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="input-group">
                    <input type="search" id="emailSearch" class="form-control" onkeyup="if (this.value.trim() !== '') search(); else cancel()">
                    <button type="button" onclick="search()" class="btn btn-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <nav class="nav nav-pills nav-fill mt-3">
                    <a class="nav-link active" id="usersTab" onclick="seeUsers()" style="height: 23px; padding: 0; font-size: 14px; cursor: pointer">Send Request</a>
                    <a class="nav-link" id="friendsTab" onclick="seeFriends()" style="height: 23px; padding: 0; font-size: 14px; cursor: pointer">Friends</a>
                    <a class="nav-link" id="requestsTab" onclick="seeRequests()" style="height: 23px; padding: 0; font-size: 14px; cursor: pointer">See Requests</a>
                </nav>
                <div class="mt-3" id="users" style="padding: 0 30px;">
                </div>
                <div class="mt-3" id="friends" style="padding: 0 30px; display: none">
                </div>
                <div class="mt-3" id="requests" style="padding: 0 30px; display: none">
                </div>
            </div>
        </div>
    </div>
  </div>
  
  <div class="container mt-5">
    <div class="row mb-5" id="row">
    </div>
  </div>

@endsection

@section('scripts')
  <script>

    $(document).ready(function(){
        arrangeBy('Date', 1);
        load();
    });

    function arrangeBy(a, b){
        arrange = a;
        order = b;
        if(b){
        $('#arrangByIcon').html(`
            <div onclick="arrangeBy('${arrange}', 0)">
            <i class="fa-solid fa-arrow-up" ></i> <b>${arrange}</b>
            </div>
        `);
        }
        else{
        $('#arrangByIcon').html(`
            <div onclick="arrangeBy('${arrange}', 1)">
            <i class="fa-solid fa-arrow-down" ></i> <b>${arrange}</b>
            </div>
        `);
        }
        $.get('arrangeSharedImages/' + arrange + '/' + order, function(data){
            $('#row').html('');
            for(var i=0; i<data.length; i++){
                var email, user_id;
                $.ajax({
                    url: 'getEmail/' + data[i].user_id + '/3',
                    type: 'GET',
                    async: false,
                    success: function(d){
                        email = d[0].email;
                        user_id = d[0].id;
                    }
                });
                $('#row').append(`
                    <div class="col-auto" style="margin-bottom: 15px;">
                        <div class="card">
                            <img class="card-img-top" height="320px" src="images/${data[i].image}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                ${data[i].name ? '<h5 class="card-title" style="margin-bottom: 0">' + data[i].name + '</h5>' : ''}
                                <a onclick="removeSharedImage(${user_id}, ${data[i].id})">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                                </div>
                                <div class="mt-3" style="font-size: 14px">
                                    <b>Shared By:</b> <span>${email}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
        })
    }

    var arr = [];
    var users;
    var friends;
    var requests;

    function load(){
        loadUsers();
        loadFriends();
        loadRequests();
    }

    function loadUsers(){
        $.get('getUsers', function(data){
            var requestsSent = [];
            // $.get('getRequestsSent', function(data){
            //     requestsSent = data;
            // });
            $.ajax({
                url: 'getRequestsSent',
                type: 'GET',
                async: false,
                success: function(data){
                    requestsSent = data;
                },
            });
            // requestsSent = requestsSent.map(function (x) { 
            //     return parseInt(x, 10); 
            // });
            output = '';
            for(var i=0; i<data.length; i++){
                output += `
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="">${data[i].email}</div>
                        <div>
                            <button class="btn" onclick=${requestsSent.includes(data[i].id) ? "deleteRequest(" + data[i].id + ")" : "sendRequest(" + data[i].id + ")"}><i class="fa-solid fa-${requestsSent.includes(data[i].id) ? 'xmark' : 'plus'}"></i></button>
                        </div>
                    </div>
                `
            }
            $('#users').html(output);
            users = output;
        });
    }

    function loadFriends(){
        $.get('getFriends', function(data){
            output = '';
            for(var i=0; i<data.length; i++){
                output += `
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="">${data[i].email}</div>
                        <div>
                            <button class="btn" onclick="removeFriend(${data[i].id})"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    </div>
                `
            }
            $('#friends').html(output);
            friends = output;
        });
    }

    function loadRequests(){
        $.get('getRequests', function(data){
            output = '';
            for(var i=0; i<data.length; i++){
                output += `
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="">${data[i].email}</div>
                        <div>
                            <button class="btn" onclick="rejectRequest(${data[i].id})"><i class="fa-solid fa-xmark"></i></button>
                            <button class="btn" onclick="acceptRequest(${data[i].id})"><i class="fa-solid fa-check"></i></button>
                        </div>
                    </div>
                `
            }
            $('#requests').html(output);
            requests = output;
        });
    }

    function search(){
        var email = $('#emailSearch').val();
        var mode;

        if($('#usersTab').hasClass('active')){
            mode = 0;
            arr = ['users', users]
        }
        else if($('#friendsTab').hasClass('active')){
            mode = 1;
            arr = ['friends', friends]
        }
        else{
            mode = 2;
            arr = ['requests', requests]
        }

        $.get('getEmail/' + email + '/' + mode, function(data){
            if(data.length > 0){
                if($('#usersTab').hasClass('active')){
                    $('#users').html(`
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="">${data[0].email}</div>
                            <div>
                                <button class="btn" onclick="sendRequest(${data[0].id})"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                    `);
                }
                else if($('#friendsTab').hasClass('active')){
                    $('#friends').html(`
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="">${data[0].email}</div>
                            <div>
                                <button class="btn" onclick="removeFriend(${data[0].id})"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        </div>
                    `);
                }
                else{
                    $('#requests').html(`
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="">${data[0].email}</div>
                            <div>
                                <button class="btn" onclick="rejectRequest(${data[0].id})"><i class="fa-solid fa-xmark"></i></button>
                                <button class="btn" onclick="acceptRequest(${data[0].id})"><i class="fa-solid fa-check"></i></button>
                            </div>
                        </div>
                    `);
                }
            }
            else{
                $("#"  + arr[0]).html(`
                    <div class="alert alert-danger" style="height: 25px; font-size: 14px; text-align: center; padding: 0;">
                        No Result Found. Enter an existing email.
                    </div>
                `);
            }
        })
    }

    function cancel(){
        $('#' + arr[0]).html(arr[1])
    }

    function seeUsers(){
        $('#users').show();
        $('#friends').hide();
        $('#requests').hide();
        $('#usersTab').addClass('active');
        $('#friendsTab').removeClass('active');
        $('#requestsTab').removeClass('active');
    }

    function seeFriends(){
        $('#users').hide();
        $('#friends').show();
        $('#requests').hide();
        $('#usersTab').removeClass('active');
        $('#friendsTab').addClass('active');
        $('#requestsTab').removeClass('active');
    }

    function seeRequests(){
        $('#users').hide();
        $('#friends').hide();
        $('#requests').show();
        $('#usersTab').removeClass('active');
        $('#friendsTab').removeClass('active');
        $('#requestsTab').addClass('active');console.log();
    }

    function sendRequest(id){
        $.ajax({
            url: 'sendRequest/' + id,
            type: 'POST',
            success: function(){
                toastr.success('Request Sent');
                load();
            },
            error: function(){
                toastr.error('Error');
            }
        })
    }

    function deleteRequest(id){
        $.ajax({
            url: 'deleteRequest/' + id,
            type: 'POST',
            success: function(){
                toastr.success('Request Deleted');
                load();
            },
            error: function(){
                toastr.error('Error');
            }
        })
    }

    function acceptRequest(id){
        $.ajax({
            url: 'acceptRequest/' + id,
            type: 'POST',
            success: function(){
                toastr.success('Request Accepted');
                load();
            },
            error: function(){
                toastr.error('Error');
            }
        })
    }

    function rejectRequest(id){
        $.ajax({
            url: 'rejectRequest/' + id,
            type: 'POST',
            success: function(){
                toastr.success('Request Rejected');
                load();
            },
            error: function(){
                toastr.error('Error');
            }
        })
    }

    function removeFriend(id){
        $.ajax({
            url: 'removeFriend/' + id,
            type: 'POST',
            success: function(){
                toastr.success('Friend Removed');
                load();
            },
            error: function(){
                toastr.error('Error');
            }
        })
    }

    function removeSharedImage(userId, imageId){
        $.ajax({
            url: 'removeSharedImage/' + userId + '/' + imageId,
            type: 'POST',
            success: function(){
                toastr.success('Shared Image Removed');
                arrangeBy(arrange, order);
            },
            error: function(){
                toastr.error('Error');
            },
        });
    }

  </script>
@endsection
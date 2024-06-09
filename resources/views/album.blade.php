@extends('index')

@section('content')
    <div class="d-flex justify-content-between">
        <div class="dropdown d-flex">
            <div>
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                    Arrange by
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu2" id="arrangeAlbums">
                    <li><button class="dropdown-item" onclick="arrangeBy('Date', 1)" type="button">Date</button></li>
                    <li><button class="dropdown-item" onclick="arrangeBy('A-Z', 1)" type="button">Alphabetical order</button></li>
                    <li><button class="dropdown-item" onclick="arrangeBy('Size', 1)" type="button">Size</button></li>
                </ul>
            </div>
            <div id="arrangByIcon" style="margin-left: 20px; margin-top: 5px; cursor: pointer;">
            </div>
        </div>
        <button id="albumBtn" data-bs-toggle="modal" data-bs-target="#modal" class="btn btn-outline-primary" style="width: 100px">+ Album</button>
        <button id="backBtn" class="btn btn-outline-success" style="width: 100px; display: none"><- Back</button>
    </div>

    <div class="modal" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: fit-content">
            <div class="modal-content" style="width: auto">
                <div class="modal-body">
                    <div>
                        <form id="form">
                            @csrf
                            <div class="form-group" style="">
                                <label for="">Name</label>
                                <input type="text" id="name" class="form-control" name="name" placeholder="Enter album name">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="createAlbum('')">Create</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="input-group">
                        <input type="search" id="emailSearch" class="form-control" onkeyup="search(this.value)">
                    </div>
                    <div class="mt-3" id="friends" style="padding: 0 30px;">
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
    var arrange;
    var order;

    $(document).ready(arrangeBy('Date', 1));

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
        $.get('arrangeAlbums/' + arrange + '/' + order, function(data){
            $('#row').html('');
            for(var i=0; i<data.length; i++){
                $('#row').append(`
                    <div class="col-auto" style="margin-bottom: 15px;">
                        <div class="card">
                            <div class="d-flex justify-content-between align-items-center" style="padding: 10px 15px 0 15px">
                                <div style="font-size: 12px">
                                    ${new Date(data[i].created_at).toLocaleString()}
                                </div>
                                <div class="">
                                    <button onclick="openFolder(${data[i].id}, 'Date', 1)" class="btn p-0" style="width: 30px">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </button>  
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <h3>${data[i].name}</h3>
                                    </div>
                                    <div class="d-flex">
                                        <a data-bs-toggle="modal" data-bs-target="#modal${data[i].id}">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <form id="deleteForm${data[i].id}">
                                            @csrf
                                            @method('DELETE')
                                            <a onclick="deleteAlbum(${data[i].id})">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal" id="modal${data[i].id}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: fit-content">
                            <div class="modal-content" style="width: auto">
                                <div class="modal-body">
                                    <div>
                                        <form id="form${data[i].id}">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group" style="">
                                            <label for="">Name</label>
                                            <input type="text" id="name${data[i].id}" class="form-control" name="name" value="${data[i].name}" placeholder="Enter a small description">
                                        </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" onclick="createAlbum(${data[i].id})">Edit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
        });
    }

    function deleteAlbum(id){
        var form = document.getElementById('deleteForm' + id);
        $.ajax({
            url: 'album/' + id,
            type: 'POST',
            processData: false,
            contentType: false,
            data: new FormData(form),
            success: function(){
                toastr.success('Album Deleted');
                arrangeBy(arrange, order);
            },
            error: function(){
                toastr.error('Error');
            }
        });
    }

    function createAlbum(id){
        var f = 1;
                
        if(!$('#name' + id).val()){
            f = 0;
            $('#nameError' + id).html('Name is required');console.log(1);
        }
        else{
            $('#nameError' + id).html('');
        }
        
        if(f){

            $('#modal' + id).modal('hide');

            var form = document.getElementById('form' + id);
            var method = 'POST';
            var u = 'album'
            if(id != ''){
                u = 'album/' + id;  
            }
            
            $.ajax({
                url: u,
                type: "POST",
                processData: false,
                contentType: false,
                data:  new FormData(form),
                success: function(){
                    $('#name' + id).val('');
                    if(id)
                        toastr.success('Album Edited');
                    else
                        toastr.success('Album Created');
                    arrangeBy(arrange, order);
                },
                error: function(){
                    toastr.error('Error');
                }
            });
        }
    }

    $('#backBtn').click(function(){
        $('#albumBtn').show();
        $('#backBtn').hide();
        $('#arrangeAlbums').html(`
            <li><button class="dropdown-item" onclick="arrangeBy('Date', 1)" type="button">Date</button></li>
            <li><button class="dropdown-item" onclick="arrangeBy('A-Z', 1)" type="button">Alphabetical order</button></li>
            <li><button class="dropdown-item" onclick="arrangeBy('Size', 1)" type="button">Size</button></li>
        `);
        arrangeBy(arrange, order);
    });

    var album, arrange2, order2;

    function arrangeAlbumImages(a, b){
        arrange2 = a;
        order2 = b;
        if(b){
            $('#arrangByIcon').html(`
                <div onclick="arrangeAlbumImages('${arrange2}', 0)">
                <i class="fa-solid fa-arrow-up" ></i> <b>${arrange2}</b>
                </div>
            `);
        }
        else{
            $('#arrangByIcon').html(`
                <div onclick="arrangeAlbumImages('${arrange2}', 1)">
                <i class="fa-solid fa-arrow-down" ></i> <b>${arrange2}</b>
                </div>
            `);
        }
        openFolder(album, arrange2, order2);
    }

    function openFolder(id, a, b){
        album = id;
        arrange2 = a;
        order2 = b;
        $('#albumBtn').hide();
        $('#backBtn').show();
        $('#arrangeAlbums').html(`
            <li><button class="dropdown-item" onclick="arrangeAlbumImages('Date', 1)" type="button">Date</button></li>
            <li><button class="dropdown-item" onclick="arrangeAlbumImages('A-Z', 1)" type="button">Alphabetical order</button></li>
            <li><button class="dropdown-item" onclick="arrangeAlbumImages('Size', 1)" type="button">Size</button></li>
        `);

        if(b){
            $('#arrangByIcon').html(`
                <div onclick="arrangeAlbumImages('${a}', 0)">
                <i class="fa-solid fa-arrow-up" ></i> <b>${a}</b>
                </div>
            `);
        }
        else{
            $('#arrangByIcon').html(`
                <div onclick="arrangeAlbumImages('${arrange2}', 1)">
                <i class="fa-solid fa-arrow-down" ></i> <b>${arrange2}</b>
                </div>
            `);
        }

        $.get('getAlbumImages/' + id + '/' + a + '/' + b, function(data){
            $('#row').html('');
            for(var i=0; i<data.length; i++){
                $('#row').append(`
                    <div class="col-auto" style="margin-bottom: 15px;">
                        <div class="card">
                            <img class="card-img-top" height="320px" src="images/${data[i].image}">
                            <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                ${data[i].name ? '<h5 class="card-title" style="margin-bottom: 0">' + data[i].name + '</h5>' : ''}
                                <a onclick="removeFromAlbum(${id}, ${data[i].id})">
                                    <i class="fa-solid fa-x"></i>
                                </a>
                                <a data-bs-toggle="modal" data-bs-target="#modal${data[i].id}">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                                <a onclick="moveToTrash(${data[i].id})">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                            <div class="d-flex justify-content-between span mt-3">
                                <a onclick="archive(${data[i].id})">
                                    <i class="fa-solid fa-box-archive"></i>
                                </a>
                                <a onclick=${data[i].isFavourite ? "unfavourite(" + data[i].id + ")" : "favourite(" + data[i].id + ")"}>
                                    <i class="fa-${data[i].isFavourite ? 'solid' : 'regular'} fa-star"></i>
                                </a>
                                <a onclick="share(${data[i].id})">
                                    <i class="fa-solid fa-share"></i>
                                </a>
                                <span style="font-size: 12px">${new Date(data[i].created_at).toLocaleString()}</span>
                            </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal" id="modal${data[i].id}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: fit-content">
                            <div class="modal-content" style="width: auto">
                                <div class="modal-body">
                                    <div>
                                        <form id="form${data[i].id}">
                                        @csrf
                                        @method('PUT')
                                        <div class="text-danger mb-1" id="imageError${data[i].id}"></div>
                                        <div class="form-group" style="margin-bottom: 15px; display:none;" id="chooseImg${data[i].id}">
                                            <label for="">Image</label>
                                            <input type="file" id="image${data[i].id}" class="form-control" name="image" onchange="displayImage(${data[i].id}, this)">
                                        </div>
                                        <div class="d-flex justify-content-between" id="imageDiv${data[i].id}">
                                            <img height="450" id="img${data[i].id}" src="images/${data[i].image}" alt="">
                                            <span id="close${data[i].id}" onclick="closeImage(${data[i].id})" style="cursor: pointer; height: 5px; margin-left: 30px">x</span>
                                        </div>
                                        <div class="form-group" style="">
                                            <label for="">Name</label>
                                            <input type="text" id="name${data[i].id}" class="form-control" name="name" value="${data[i].name ? data[i].name : ''}" placeholder="Enter a small description">
                                        </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" onclick="upload(${data[i].id})">Edit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
        });
    }

    var flag = 0;

    function displayImage(id, input) {
        flag = 0;
        if (input.files && input.files[0]) {
            $('#chooseImg' + id).hide();

            var reader = new FileReader();

            reader.onload = function (e) {
                $('#img' + id)
                    .attr('src', e.target.result)
                    .height(450);
            };
            
            reader.readAsDataURL(input.files[0]);
            
            $('#imageDiv' + id).css('margin-bottom', '1.5rem');
            $('#img' + id).show();
            $('#close' + id).show();
        }
    }

    function closeImage(id){
        flag = 1;
        $('#chooseImg' + id).show();
        $('#image' + id).val('');
        $('#imageDiv' + id).css('margin-bottom', '0px');
        $('#img' + id).hide();
        $('#close' + id).hide();
    }

    function upload(id){
      if(flag == 0 && id != ''){
          $('#chooseImg' + id).html('<input type="hidden" name="hiddenToken" value="1">');
      }
      var form = document.getElementById('form' + id);
      var method = 'POST';
      var u = 'image'
      if(id != ''){
        u = 'image/' + id;
      }
      $('#imageError' + id).html('');
        
      $.ajax({
        url: u,
        type: "POST",
        processData: false,
        contentType: false,
        data:  new FormData(form),
        success: function(){
            $('#modal' + id).modal('hide');
            closeImage(id);
            flag = 0;
            $('#name' + id).val('');
            if(id)
                toastr.success('Image Edited');
            else
                toastr.success('Image Uploaded');
            openFolder(album, arrange2, order2);
        },
        error: function(res){
          toastr.error('Error');
          if(res.responseJSON)
            $('#imageError' + id).html(res.responseJSON.message);
          else if(res.status == 413)
            $('#imageError' + id).html('Image size can not be above 1 MB');
        }
      });
    }

    function moveToTrash(id){
        $.ajax({
            url: 'image/' + id,
            type: 'POST',
            success: function(){
                toastr.success('Image moved to trash');
                openFolder(album, arrange2, order2);
            },
            error: function(){
                toastr.error('Error');
            }
        })
    }

    function archive(id){
        $.ajax({
            url: 'archive/' + id,
            type: 'POST',
            success: function(){
                toastr.success('Image Archived');
                openFolder(album, arrange2, order2);
            },
            error: function(){
                toastr.error('Error');
            }
        });
    }

    function favourite(id){
        $.ajax({
            url: 'favourite/' + id,
            type: 'POST',
            success: function(){
                toastr.success('Image added to favourites');
                openFolder(album, arrange2, order2);
            },
            error: function(){
                toastr.error('Error');
            }
        })
    }

    function unfavourite(id){
        $.ajax({
            url: 'unfavourite/' + id,
            type: 'POST',
            success: function(){
                toastr.success('Image removed from favourites');
                openFolder(album, arrange2, order2);
            },
            error: function(){
                toastr.error('Error');
            }
        })
    }

    var friends, imgId;

    function share(id){
      imgId = id;
      $('#friends').html('');
      $.get('getFriends', function(data){
        output = '';
        for(var i=0; i<data.length; i++){
          var c = ''
          $.ajax({
            url: 'ifImageShared/' + data[i].id + '/' + id,
            type: 'GET',
            async: false,
            success: function(res){
              if(res)
                c = 'checked'
            },
          });
          output += `
            <div class="d-flex justify-content-between align-items-center">
                <div class="">${data[i].email}</div>
                <input class="form-check-input" type="checkbox" onclick="shareWithFriend(this, ${data[i].id}, ${id})" ${c}>
            </div>
          `;
        }
        $('#friends').html(output);
        friends = output;
      });
      $('#shareModal').modal('show');
    }

    function shareWithFriend(checkbox, userId, imageId){
      if(checkbox.checked){
        $.ajax({
          url: 'share/' + userId + '/' + imageId,
          type: 'POST',
          success: function(user){
            toastr.success('Shared image with ' + user);
          },
          error: function(){
            toastr.error('Error');
          },
        });
      }
      else{
        $.ajax({
          url: 'unshare/' + userId + '/' + imageId,
          type: 'POST',
          success: function(user){
            toastr.success('Unshared image with ' + user);
          },
          error: function(){
            toastr.error('Error');
          },
        });
      }
    }

    function search(value){
      value = value.toLowerCase();
      $('#friends > div').each(function() {
        const user = $(this).text().trim().toLowerCase();
        // console.log(user);
        if (user.includes(value)) {
          $(this).show();  // Show the conversation if it matches the search
        } else {          
          $(this).attr('style', 'display: none !important');  // Hide the conversation if it doesn't match the search
          console.log(this);
        }
      });
    }

    function removeFromAlbum(albumId, imageId){
        // console.log(albumId);
        $.ajax({
            url: 'removeFromAlbum/' + albumId + '/' + imageId,
            type: 'POST',
            success: function(album){
                toastr.success('Removed image from ' + album + ' folder');
                openFolder(albumId, arrange2, order2);
            },
            error: function(){
                toastr.error('Error');
            },
        });
    }

  </script>
@endsection
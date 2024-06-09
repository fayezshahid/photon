@extends('index')

@section('content')
  <div class="d-flex align-items-center">
    <div style="margin-right: 25px">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
            Search by
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
            <li><button class="dropdown-item" onclick="searchBy('Name')" type="button">Name</button></li>
            <li><button class="dropdown-item" onclick="searchBy('Date')" type="button">Date</button></li>
        </ul>
    </div>
    <div id="searchByName" style="width: 300px; display: none;" class="input-group input-group-sm">
        <input type="text" id="name" class="form-control">
        <button onclick="searchImage('Name')" class="btn btn-sm btn-secondary" style="width: 75px">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </div>
    <div id="searchByDate" style="display: none;">
        <div class="d-flex align-items-center">
            <input type="text" id="datepicker1" style="width: 100px">
            <span style="padding: 0 10px">From</span>
            <input type="text" id="datepicker2" style="width: 100px">
            <button onclick="searchImage('Date')" class="btn btn-sm btn-secondary" style="margin-left: 25px; width: 75px">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
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

  <div class="modal" id="albumModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
              <div class="input-group">
                <input type="search" id="albumSearch" class="form-control" onkeyup="searchAlbum(this.value)">
              </div>
              <div class="mt-3" id="albums" style="padding: 0 30px;">
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
        $('#datepicker1').datepicker({ dateFormat: 'yy-mm-dd' });
        $('#datepicker2').datepicker({ dateFormat: 'yy-mm-dd' });
    });

    function displayImages(data){
        $('#row').html('');
        for(var i=0; i<data.length; i++){
            $('#row').append(`
                <div class="col-auto" style="margin-bottom: 15px;">
                    <div class="card">
                        <img class="card-img-top" height="320px" src="images/${data[i].image}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                ${data[i].name ? '<h5 class="card-title" style="margin-bottom: 0">' + data[i].name + '</h5>' : ''}
                                <a onclick="album(${data[i].id})">
                                    <i class="fa-solid fa-plus"></i>
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
                                    <div class="d-flex justify-content-between" id="imageDiv${data[i].id}" style="margin-bottom: 1.5rem;">
                                        <img height="450" id="img${data[i].id}" src="images/${data[i].image}" alt="">
                                        <span id="close${data[i].id}" onclick="closeImage(${data[i].id})" style="cursor: pointer; height: 5px; margin-left: 30px">x</span>
                                    </div>
                                    <div class="form-group" style="">
                                        <label for="">Name</label>
                                        <input type="text" id="name${data[i].id}" class="form-control" name="name" value="${data[i].name  ? data[i].name : ''}" placeholder="Enter a small description">
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
    }

    function searchBy(s){
        $('#dropdownMenu2').html(s);
        if(s == 'Name'){
            $('#searchByName').show();
            $('#searchByDate').hide()
        }
        else{
            $('#searchByName').hide();
            $('#searchByDate').show()
        }
    }

    var exploreBy;

    function searchImage(s){
        exploreBy = s;
        if(s == 'Name'){
            var name = $('#name').val();
            $.get('getImageByName/' + name, function(data){
                displayImages(data);
            });
        }
        else{
            var d1 = $('#datepicker1').val();
            var d2 = $('#datepicker2').val();
            $.get('getImageByDate/' + d1 + '/' + d2, function(data){
                displayImages(data);
            });
        }
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
          searchImage(exploreBy);
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
                searchImage(exploreBy);
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
                searchImage(exploreBy);
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
                searchImage(exploreBy);
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
          searchImage(exploreBy);
        },
        error: function(){
          toastr.error('Error');
        }
      })
    }

    var imgId;

    function share(id){
      imgId = id;
      $('#emailSearch').val('');
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

    var albumImgId;

    function album(id){
      albumImgId = id;
      $('#albumSearch').val('');
      $('#albums').html('');
      $.get('getAlbums', function(data){
        output = '';
        for(var i=0; i<data.length; i++){
          var c = ''
          $.ajax({
            url: 'ifInAlbum/' + data[i].id + '/' + id,
            type: 'GET',
            async: false,
            success: function(res){
              if(res)
                c = 'checked'
            },
          });
          output += `
            <div class="d-flex justify-content-between align-items-center">
                <div class="">${data[i].name}</div>
                <input class="form-check-input" type="checkbox" onclick="addToAlbum(this, ${data[i].id}, ${id})" ${c}>
            </div>
          `;
        }
        $('#albums').html(output);
      });
      $('#albumModal').modal('show');
    }

    function addToAlbum(checkbox, albumId, imageId){
      if(checkbox.checked){
        $.ajax({
          url: 'addToAlbum/' + albumId + '/' + imageId,
          type: 'POST',
          success: function(album){
            toastr.success('Added image to ' + album + ' folder');
          },
          error: function(){
            toastr.error('Error');
          },
        });
      }
      else{
        $.ajax({
          url: 'removeFromAlbum/' + albumId + '/' + imageId,
          type: 'POST',
          success: function(album){
            toastr.success('Removed image from ' + album + ' folder');
          },
          error: function(){
            toastr.error('Error');
          },
        });
      }
    }
    
    function searchAlbum(value){
      value = value.toLowerCase();
      $('#albums > div').each(function() {
        const album = $(this).text().trim().toLowerCase();
        // console.log(user);
        if (album.includes(value)) {
          $(this).show();  // Show the conversation if it matches the search
        } else {          
          $(this).attr('style', 'display: none !important');  // Hide the conversation if it doesn't match the search
          // console.log(this);
        }
      });
    }

  </script>
@endsection
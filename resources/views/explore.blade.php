@extends('index')

@section('content')
  <div class="d-flex align-items-center">
    <div style="margin-right: 25px">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
            Search by
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
            <li><button class="dropdown-item" onclick="searchBy('name')" type="button">Name</button></li>
            <li><button class="dropdown-item" onclick="searchBy('date')" type="button">Date</button></li>
        </ul>
    </div>
    <div id="searchByName" style="width: 300px;" class="input-group input-group-sm">
        <input type="text" id="name" class="form-control">
        <button onclick="searchImage('name')" class="btn btn-sm btn-secondary" style="width: 75px">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </div>
    <div id="searchByDate" style="display: none;">
        <div class="d-flex align-items-center">
            <input type="text" id="datepicker1" style="width: 100px">
            <span style="padding: 0 10px">From</span>
            <input type="text" id="datepicker2" style="width: 100px">
            <button onclick="searchImage('date')" class="btn btn-sm btn-secondary" style="margin-left: 25px; width: 75px">
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
                <input type="search" id="emailSearch" class="form-control" onsearch="cancel()">
                <button type="button" onclick="search()" class="btn btn-secondary">
                    <i class="fas fa-search"></i>
                </button>
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
                                    <div class="form-group" style="margin-bottom: 15px; display:none;" id="chooseImg${data[i].id}">
                                        <label for="">Image</label>
                                        <input type="file" id="image${data[i].id}" class="form-control" name="image" onchange="displayImage(${data[i].id}, this)">
                                        <div class="text-danger" id="imageError${data[i].id}"></div>
                                    </div>
                                    <div class="d-flex justify-content-between" id="imageDiv${data[i].id}">
                                        <img height="450" id="img${data[i].id}" src="images/${data[i].image}" alt="">
                                        <span id="close${data[i].id}" onclick="closeImage(${data[i].id})" style="cursor: pointer; height: 5px; margin-left: 30px">x</span>
                                    </div>
                                    <div class="form-group" style="">
                                        <label for="">Name</label>
                                        <input type="text" id="name${data[i].id}" class="form-control" name="name" value="${data[i].name}" placeholder="Enter a small description">
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
        if(s == 'name'){
            $('#searchByName').show();
            $('#searchByDate').hide()
        }
        else{
            $('#searchByName').hide();
            $('#searchByDate').show()
        }
    }

    function searchImage(s){
        if(s == 'name'){
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
        var f = 1;
                
        if(!$('#image' + id).val() || flag == 1){
            f = 0;
            $('#imageError' + id).html('Image is required');
        }
        else{
            $('#imageError' + id).html('');
        }
        
        if(f){
            if(flag == 0 && id != ''){
                $('#chooseImg' + id).html('<input type="hidden" name="hiddenToken" value="1">');
            }

            $('#modal' + id).modal('hide');

            var form = document.getElementById('form' + id);
            var method = 'POST';
            var u = 'image'
            if(id != ''){
                u = 'image/' + id;
            }
            
            $.ajax({
                url: u,
                type: "POST",
                processData: false,
                contentType: false,
                data:  new FormData(form),
                success: function(){
                    closeImage(id);
                    flag = 0;
                    $('#name' + id).val('');
                    if(id)
                        toastr.success('Image Edited');
                    else
                        toastr.success('Image Uploaded');
                    arrangeBy(arrange, order);
                },
                error: function(){
                    toastr.error('Error');
                }
            });
        }
    }

    function moveToTrash(id){
        $.ajax({
            url: 'image/' + id,
            type: 'POST',
            success: function(){
                toastr.success('Image moved to trash');
                arrangeBy(arrange, order);
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
                arrangeBy(arrange, order);
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
                arrangeBy(arrange, order);
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
          arrangeBy(arrange, order);
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

    function search(){
      var email = $('#emailSearch').val();

      $.get('getEmail/' + email + '/1', function(data){
        if(data.length > 0){
          share(imgId);
        }
        else{
          $("#friends").html(`
            <div class="alert alert-danger" style="height: 25px; font-size: 14px; text-align: center; padding: 0;">
              No Result Found. Enter an existing email.
            </div>
          `);
        }
      })
    }

    function cancel(){
        $('#friends').html(friends)
    }

  </script>
@endsection
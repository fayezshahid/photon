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
        <div onclick="arrangeBy('date', 1)">
          <i class="fa-solid fa-arrow-down" ></i> <b>date</b>
        </div>
      </div>
    </div>
    <button data-bs-toggle="modal" data-bs-target="#modal" class="btn btn-primary" style="width: 100px">Upload</button>
  </div>

  <div class="modal" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="height:290px">
            <div class="modal-body">
                <div>
                  <form id="form" style="width: 465px;">
                    @csrf
                    <div class="form-group" style="margin-bottom: 15px" id="chooseImg">
                        <label for="">Image</label>
                        <input type="file" id="image" class="form-control" name="image" onchange="displayImage('', this)">
                        <div class="text-danger" id="imageError"></div>
                    </div>
                    <div class="d-flex justify-content-between" id="imageDiv">
                        <img id="img" src="" alt="">
                        <span id="close" onclick="closeImage('')" style="cursor: pointer; height: 5px; display: none;">x</span>
                    </div>
                    <div class="form-group" style="">
                        <label for="">Name</label>
                        <input type="text" id="name" class="form-control" name="name" placeholder="Enter a small description">
                        <div class="text-danger" id="nameError"></div>
                    </div>
                  </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="upload('')">Upload</button>
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
      $.get('arrangeImages/' + arrange + '/' + order, function(data){
        $('#row').html('');
        for(var i=0; i<data.length; i++){
          $('#row').append(`
            <div class="col-md-4" style="margin-bottom: 15px;">
              <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="images/${data[i].image}" alt="Card image cap">
                <div class="card-body">
                  <div class="d-flex justify-content-between">
                    <h5 class="card-title">${data[i].name}</h5>
                    <a data-bs-toggle="modal" data-bs-target="#modal${data[i].id}">
                      <i class="fa-solid fa-pencil"></i>
                    </a>
                    <form id="deleteForm${data[i].id}">
                      @csrf
                      @method('DELETE')
                      <a onclick="deleteImage(${data[i].id})">
                        <i class="fa-solid fa-trash"></i>
                      </a>
                    </form>
                  </div>
                  <div class="d-flex justify-content-between mt-3">
                    <a onclick="archive(${data[i].id})">
                      <i class="fa-solid fa-box-archive"></i>
                    </a>
                    <a onclick=${data[i].isFavourite ? "unfavourite(" + data[i].id + ")" : "favourite(" + data[i].id + ")"}>
                      <i class="fa-${data[i].isFavourite ? 'solid' : 'regular'} fa-star"></i>
                    </a>
                    <a onclick="">
                      <i class="fa-solid fa-share"></i>
                    </a>
                    <p style="font-size: 12px">${new Date(data[i].created_at).toLocaleString()}</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal" id="modal${data[i].id}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                  <div class="modal-content" style="height:665px">
                      <div class="modal-body">
                          <div>
                            <form id="form${data[i].id}" style="width: 465px;">
                              @csrf
                              @method('PUT')
                              <div class="form-group" style="margin-bottom: 15px; display:none;" id="chooseImg${data[i].id}">
                                  <label for="">Image</label>
                                  <input type="file" id="image${data[i].id}" class="form-control" name="image" onchange="displayImage(${data[i].id}, this)">
                                  <div class="text-danger" id="imageError${data[i].id}"></div>
                              </div>
                              <div class="d-flex justify-content-between" id="imageDiv${data[i].id}">
                                  <img width="320" height="450" id="img${data[i].id}" src="images/${data[i].image}" alt="">
                                  <span id="close${data[i].id}" onclick="closeImage(${data[i].id})" style="cursor: pointer; height: 5px;">x</span>
                              </div>
                              <div class="form-group" style="">
                                  <label for="">Name</label>
                                  <input type="text" id="name${data[i].id}" class="form-control" name="name" value="${data[i].name}" placeholder="Enter a small description">
                                  <div class="text-danger" id="nameError${data[i].id}"></div>
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
      })
    }

    function displayImage(id, input) {
      if (input.files && input.files[0]) {

        $('#chooseImg' + id).hide();
        $('.modal-content').css('height', '665px')
        $('.modal-dialog').addClass("modal-lg");

        var reader = new FileReader();

        reader.onload = function (e) {
            $('#img' + id)
                .attr('src', e.target.result)
                .width(320)
                .height(450);
        };

        reader.readAsDataURL(input.files[0]);
        
        // $('#imageDiv').css('margin-top', '40px');
        $('#imageDiv' + id).css('margin-bottom', '1.5rem');
        $('#img' + id).show();
        $('#close' + id).show();
        
      }
    }

    var flag = 0;

    function closeImage(id){
      flag = 1;
      $('.modal-content').css('height', '290px')
      $('.modal-dialog').removeClass("modal-lg");
      $('#chooseImg' + id).show();
      $('#image' + id).val('');
      $('#imageDiv' + id).css('margin-top', '0px');
      $('#imageDiv' + id).css('margin-bottom', '0px');
      $('#img' + id).hide();
      $('#close' + id).hide();
    }

    function upload(id){
      var f = 1;
                
      if(!$('#image' + id).val() && flag == 1){
          f = 0;
          $('#imageError' + id).html('Image is required');
      }
      else{
          $('#imageError' + id).html('');
      }

      if(!$('#name' + id).val()){
          f = 0;
          $('#nameError' + id).html('Name is required');
      }
      else{
          $('#nameError' + id).html('');
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

    function deleteImage(id){
      // var form = document.getElementById('deleteForm' + id);
      $.ajax({
        url: 'image/' + id,
        type: 'POST',
        // processData: false,
        // contentType: false,
        data: id,
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
        data: id,
        success: function(){
          toastr.success('Image Archived');
          arrangeBy(arrange, order);
        },
        error: function(){
          toastr.error('Error');
        }
      })
    }

    function favourite(id){
      $.ajax({
        url: 'favourite/' + id,
        type: 'POST',
        data: id,
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
        data: id,
        success: function(){
          toastr.success('Image removed from favourites');
          arrangeBy(arrange, order);
        },
        error: function(){
          toastr.error('Error');
        }
      })
    }

  </script>
@endsection
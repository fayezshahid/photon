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
    <button onclick="clearTrash()" class="btn btn-danger" style="width: 100px">Clear</button>
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
      $.get('arrangeTrashImages/' + arrange + '/' + order, function(data){
        $('#row').html('');
        for(var i=0; i<data.length; i++){
          $('#row').append(`
            <div class="col-auto" style="margin-bottom: 15px;">
              <div class="card" style="height: 418px">
                <img class="card-img-top" height="320px" src="images/${data[i].image}">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    ${data[i].name ? '<h5 class="card-title" style="margin-bottom: 0">' + data[i].name + '</h5>' : ''}
                  </div>
                  <div class="d-flex justify-content-between align-items-center mt-3">
                    <a onclick="restoreImage(${data[i].id})">
                        <i class="fa-solid fa-window-restore"></i>
                    </a>
                    <form id="deleteForm${data[i].id}">
                      @csrf
                      @method('DELETE')
                      <a onclick="deleteImage(${data[i].id})">
                        <i class="fa-solid fa-trash"></i>
                      </a>
                    </form>
                    <span style="font-size: 12px">${new Date(data[i].created_at).toLocaleString()}</span>
                  </div>
                </div>
              </div>
            </div>
          `);
        }
      })
    }

    function restoreImage(id){
      $.ajax({
        url: 'restore/' + id,
        type: 'POST',
        data: id,
        success: function(){
          toastr.success('Image Restored');
          arrangeBy(arrange, order);
        },
        error: function(){
          toastr.error('Error');
        }
      })
    }

    function deleteImage(id){
      var form = document.getElementById('deleteForm' + id);
      $.ajax({
        url: 'delete/' + id,
        type: 'POST',
        processData: false,
        contentType: false,
        data: new FormData(form),
        success: function(){
          toastr.success('Image Deleted');
          arrangeBy(arrange, order);
        },
        error: function(){
          toastr.error('Error');
        }
      })
    }

    function clearTrash(){
      $.ajax({
        url: 'clear',
        type: 'POST',
        success: function(){
          toastr.success('Trash Cleared');
          $('#row').html('');
        },
        error: function(){
          toastr.error('Error');
        }
      })
    }

  </script>
@endsection
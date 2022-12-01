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
    {{-- <button data-bs-toggle="modal" data-bs-target="#modal" class="btn btn-primary" style="width: 100px">Upload</button> --}}
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
            <div class="col-md-4" style="margin-bottom: 15px;">
              <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="images/${data[i].image}" alt="Card image cap">
                <div class="card-body">
                  <div class="d-flex justify-content-between">
                    <h5 class="card-title">${data[i].name}</h5>
                  </div>
                  <div class="d-flex justify-content-between mt-3">
                    <a onclick="restoreImage(${data[i].id})">
                        <i class="fa-solid fa-window-restore"></i>
                    </a>
                    <a onclick="deleteImage(${data[i].id})">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                    <p style="font-size: 12px">${new Date(data[i].created_at).toLocaleString()}</p>
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
      $.ajax({
        url: 'delete/' + id,
        type: 'POST',
        data: id,
        success: function(){
          toastr.success('Image Deleted');
          arrangeBy(arrange, order);
        },
        error: function(){
          toastr.error('Error');
        }
      })
    }

  </script>
@endsection
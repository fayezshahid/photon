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
        <button onclick="search('name')" class="btn btn-sm btn-secondary" style="width: 75px">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </div>
    <div id="searchByDate" style="display: none;">
        <div class="d-flex align-items-center">
            <input type="text" id="datepicker1" style="width: 100px">
            <span style="padding: 0 10px">From</span>
            <input type="text" id="datepicker2" style="width: 100px">
            <button onclick="search('date')" class="btn btn-sm btn-secondary" style="margin-left: 25px; width: 75px">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
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

    function search(s){
        if(s == 'name'){
            var name = $('#name').val();
            $.get('getImageByName/' + name, function(data){
                console.log(data);
            });
        }
        else{
            var d1 = $('#datepicker1').val();
            var d2 = $('#datepicker2').val();
            // console.log(d1)
            $.get('getImageByDate/' + d1 + '/' + d2, function(data){
                console.log(data);
            });
        }
    } 


  </script>
@endsection
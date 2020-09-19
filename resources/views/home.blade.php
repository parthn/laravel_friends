@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                <input type="text" placeholder="search" name="search_user" id="search_user">
                <input type="text"  placeholder="skill search" name="search_user_skill" id="search_user_skill">
                <div class="container padding">
                    <table class="table table-bordered table-striped" id="user_datatable">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                </div>
            </div>
          
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                <div class="container padding">
                    <table class="table table-bordered table-striped" id="user_datatable">
                        <thead class="thead-dark">
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                            @foreach($data as $da)
                            <tr>
                                <td>{{$da->user->first_name}}</td>
                                <td>{{$da->user->last_name}}</td>
                                <td>{{$da->user->email}}</td>
                                <td><button class="btn btn-primary" onclick="AcceptRequest({{$da->id}})">Accept Request</button>
                                    <button class="btn btn-primary" onclick="rejectRequest({{$da->id}})">Reject Request</button></td>
                            </tr>
                            @endforeach
                        </thead>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
       var SITEURL = '{{URL::to('')}}';
       $(document).ready(function () {
        $('#user_datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: SITEURL + "/users",
                type: 'get',
                dataType: "json",
                data: function(d){    
                    d.search_user = $("#search_user").val();         
                    d.search_user_skill = $("#search_user_skill").val();         
                },
                async: true,
            },
            fnServerParams: function (data) {
                data['order'].forEach(function (items, index) {
                    data['order'][index]['column'] = data['columns'][items.column]['data'];
                });
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'first_name', name: 'first_name'},
                {data: 'last_name', name: 'last_name'},
                {data: 'email', name: 'email'},
                {render: function (data, type, row) {
                    if(row.friend_requests_nosent){
                        return '<button class="btn btn-primary" onclick="SendRequest(' + row.id + ')">Send Request</button>';
                    }else{
                        return 'Already Sent';
                    }
                       
                    }, "orderable": false
                }
            ],
            order: [[0, 'desc']]
        });


        $('#search_user, #search_user_skill').on('change', function (e) {
            $('#user_datatable').DataTable().draw();
        });
    });
    
    function SendRequest(user_id) {
        if (user_id) {
            $.ajax({
                url: SITEURL + "/send_request/"+user_id,
                type: "get",
                dataType: 'json',
                success: function (data) {
                    var oTable = $('#user_datatable').dataTable();
                    oTable.fnDraw(false);    
                    location.reload();   
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    }
    function AcceptRequest(request_id) {
        if (request_id) {
            $.ajax({
                url: SITEURL + "/accept_request/"+request_id,
                type: "get",
                dataType: 'json',
                success: function (data) {
                    var oTable = $('#user_datatable').dataTable();
                    oTable.fnDraw(false);       
                    location.reload();
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    }
    function rejectRequest(request_id) {
        if (request_id) {
            $.ajax({
                url: SITEURL + "/reject_request/"+request_id,
                type: "get",
                dataType: 'json',
                success: function (data) {
                    var oTable = $('#user_datatable').dataTable();
                    oTable.fnDraw(false);       
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    }

    </script>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Connection</div>
                <div class="card-body">
                <div class="container padding">
                    <table class="table table-bordered table-striped" id="user_datatable">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                            </tr>
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
                url: SITEURL + "/connection",
                type: 'get',
                dataType: "json",
                async: true,
            },
            fnServerParams: function (data) {
                data['order'].forEach(function (items, index) {
                    data['order'][index]['column'] = data['columns'][items.column]['data'];
                });
            },
            columns: [
                {data: 'friend.id', name: 'id'},
                {data: 'friend.first_name', name: 'first_name'},
                {data: 'friend.last_name', name: 'last_name'},
                {data: 'friend.email', name: 'email'}
            ],
            order: [[0, 'desc']]
        });


        $('#search_user, #search_user_skill').on('change', function (e) {
            $('#user_datatable').DataTable().draw();
        });
    });
    </script>
@endsection

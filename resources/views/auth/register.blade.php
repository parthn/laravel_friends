@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Register</div>

                <div class="card-body">
                    <form method="POST" id="register_form" name="register_form" action="{{ url('/user_register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="first_name" class="col-md-4 col-form-label text-md-right">First Name</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" autocomplete="first_name" autofocus>

                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="last_name" class="col-md-4 col-form-label text-md-right">Last Name</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}"  autocomplete="last_name" autofocus>

                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="phone_no" class="col-md-4 col-form-label text-md-right">Phone No.</label>

                            <div class="col-md-6">
                                <input id="phone_no" type="number" class="form-control @error('phone_no') is-invalid @enderror" name="phone_no" value="{{ old('phone_no') }}"  autocomplete="phone_no">

                                @error('phone_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                      
                        <div class="container">                        
                            <div class="form-group row element" id='div_1'>                        
                                <label for="skill" class="col-md-4 col-form-label text-md-right">Skill</label>
                                <div class='col-md-4'>
                                        <input type='text' id='skill_1' name="skill_name[]" class="form-control" >
                                </div>
                                <div class='col-md-2'>
                                    <span class='add btn btn-primary'>+</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
 $(document).ready(function () {
        $("form[name='register_form']").validate({
            rules: {
                'first_name': {required: true},  
                'last_name': {required: true},  
                'email': {required: true, email: true},  
                'phone_no': {required: true, minlength: 11},  
                'skill_name[]': {required: true},  
                'password': {required: true, minlength: 5},  
                'password_confirmation': {required: true, equalTo: "#password"},  
            },
            messages: {
                'first_name': "Please enter First Name",
                'last_name': "Please enter Last Name",
                'skill_name[]': "Please enter Skill Name",
                'email': {
                    required: "Please enter a email",
                    email: "Please enter a valid email address"
                },
                'phone_no': {
                    required: "Please enter a phone no",
                    minlength: "Your phone no is not valid"
                },
                'password': {
                    required: "Please enter a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                'password_confirmation': {
                    required: "Please enter a Confirm password",
                    equalTo: "Enter Confirm Password Same as Password"
                },
            },
            submitHandler: function(form) {
             form.submit();
            }
        });


        $(".add").click(function(){
            var total_element = $(".element").length;
            var lastid = $(".element:last").attr("id");
            var split_id = lastid.split("_");
            var nextindex = Number(split_id[1]) + 1;
            var max = 5;
            if(total_element < max ){
                $(".element:last").after("<div class='form-group row element' id='div_"+ nextindex +"'></div>");
                var html_data = '';
                 html_data +=   '<label for="skill" class="col-md-4 col-form-label text-md-right">Skill</label>';
                 html_data +=   '<div class="col-md-4">';
                 html_data +=   '<input type="text" id="skill_1" name="skill_name[]" class="form-control" >';
                 html_data +=   '</div>';
                 html_data +=   '<div class="col-md-2">';          
                 html_data +=   '<span class="remove btn btn-primary" id="remove_' + nextindex + '">-</span>';                   
                 html_data +=   '</div>';         
                $("#div_" + nextindex).append(html_data);
         }
        });
        $('.container').on('click','.remove',function(){
            var id = this.id;
            var split_id = id.split("_");
            var deleteindex = split_id[1];
            $("#div_" + deleteindex).remove();
        }); 
    });
</script>
@endsection

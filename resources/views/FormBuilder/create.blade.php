
@extends('lay.app')

@section('head')
    <style>
        /* Adjust the size of the form container */
        .formcontainer {
            width: 80%;
            height: 80%;
            margin: 0 auto; 
            left: 40%;
         
    
          
        }
        .cardujbf
        {
            color: #000;
        }
        .cardbody {
            width: 80%;
            height: 60%;
            justify-content: center;
            left: 10%;

        }
        /* Optional: Set a max height for the form editor */
        #fb1hedtor {
            width: 80%; 
            height: 80%;  
            color: #d1ff33;
        }

        /* Optional: Button styling */
        .btn-success {
            margin-top: 20px;
            color:rgb(0, 135, 34);
        }
    </style>
@endsection

@section('content')
    <div class="cardujbf ">
        <div class="cardbody">
            <label for="name">{{__('Name')}}</label>
            <input type="text" id="name" name="name" class="formcontainer" />
            <div id="fb1heditor"></div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <script src="{{ URL::asset('assets/form-builder/form-builder.min.js') }}"></script>
    <script>
        jQuery(function($) {
            $(document.getElementById('fb1heditor')).formBuilder({
                onSave: function(evt, formData) {
                    console.log(formData);
                    saveForm(formData);
                },
            });
        });

        function saveForm(form) {
            $.ajax({
    type: 'POST',
    url: '{{ URL('save-form-builder') }}',
    data: {
        'form': form,
        'name': $("#name").val(),
        "_token": $('meta[name="csrf-token"]').attr('content') 
    },
    success: function(data) {
        console.log('Success:', data);
        alert('Form saved successfully!');
        window.location.href = '{{ URL('form-builder') }}';

    },
    error: function(xhr) {
        console.log('Error:', xhr.responseText);
        alert('An error Please try again.');

    }
});

        }
    </script>
@endsection






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Student</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.3/jquery.validate.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

</head>

<body>

    <div class="container">
        <h2>Create Student</h2>
        <form id="studentForm">
            @csrf
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control">
                    <span class="text-danger" id="first_name_error"></span>
                </div>
                <div class="col-md-6 form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control">
                    <span class="text-danger" id="last_name_error"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                    <span class="text-danger" id="email_error"></span>
                </div>
                <div class="col-md-6 form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control">
                    <span class="text-danger" id="phone_error"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Address Line 1</label>
                    <input type="text" name="address_line_1" class="form-control">
                    <span class="text-danger" id="address_line_1_error"></span>
                </div>
                <div class="col-md-6 form-group">
                    <label>Address Line 2</label>
                    <input type="text" name="address_line_2" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Country</label>
                    <select name="country" class="form-control" id="countrySelect">
                        <option value="">Select Country</option>
                    </select>
                    <span class="text-danger" id="country_error"></span>
                </div>


                <div class="col-md-6 form-group">
                    <label>State</label>
                    <select name="state" class="form-control" id="stateSelect">
                        <option value="">Select State</option>
                    </select>
                    <span class="text-danger" id="state_error"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>City</label>
                    <select name="city" class="form-control" id="citySelect">
                        <option value="">Select City</option>
                    </select>
                    <span class="text-danger" id="city_error"></span>
                </div>
                <div class="col-md-6 form-group">
                    <label>Postal Code</label>
                    <input type="text" name="postal_code" class="form-control">
                    <span class="text-danger" id="postal_code_error"></span>
                </div>
            </div>
            <div class="form-group col-md-4">
                <button type="submit" class="btn btn-primary">Register</button>
                <button type="button" class="btn btn-secondary ml-2" onclick="window.history.back()">Back</button>
            </div>
        </form>
        <div id="studentsList"></div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: 'https://restcountries.com/v3.1/all',
                method: 'GET',
                success: function(data) {
                    data.forEach(function(country) {
                        $('#countrySelect').append(`<option value="${country.name.common}">${country.name.common}</option>`);
                    });
                }
            });

            $('#countrySelect').on('change', function() {
                const selectedCountry = $(this).val();

                $.ajax({
                    url: `/api/states/${selectedCountry}`,
                    method: 'GET',
                    success: function(states) {
                        console.log(states);
                        $('#stateSelect').empty().append('<option value="">Select State</option>');
                        states.forEach(function(state) {
                            $('#stateSelect').append(`<option value="${state.code}">${state.name}</option>`);
                        });
                    },
                    error: function() {
                        console.log('Error fetching states.');
                    }

                });
                $('#citySelect').empty().append('<option value="">Select City</option>');
            });

            $('#stateSelect').on('change', function() {
                const selectedState = $(this).val();

                if (selectedState) {
                    $.ajax({
                        url: `/api/cities/${selectedState}`,
                        method: 'GET',
                        success: function(cities) {
                            console.log(cities);
                            $('#citySelect').empty().append('<option value="">Select City</option>');

                            cities.forEach(function(city) {
                                $('#citySelect').append(`<option value="${city}">${city}</option>`);
                            });
                        },
                        error: function() {
                            console.log('Error fetching cities.');
                        }
                    });
                } else {
                    $('#citySelect').empty().append('<option value="">Select City</option>');
                }
            });


            $('#studentForm').validate({
                rules: {
                    first_name: {
                        required: true,
                        maxlength: 30
                    },
                    last_name: {
                        required: true,
                        maxlength: 30
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 50
                    },
                    phone: {
                        required: true,
                        maxlength: 20
                    },
                    address_line_1: {
                        required: true
                    },
                    city: {
                        required: true
                    },
                    postal_code: {
                        required: true,
                        digits: true,
                        maxlength: 10
                    },
                    country: {
                        required: true
                    },
                    state: {
                        required: true
                    }
                },
                messages: {
                    first_name: {
                        required: "Please enter the first name",
                        maxlength: "First name must not exceed 30 characters"
                    },
                    last_name: {
                        required: "Please enter the last name",
                        maxlength: "Last name must not exceed 30 characters"
                    },
                    email: {
                        required: "Please enter a valid email address",
                        email: "Please enter a valid email",
                        maxlength: "Email must not exceed 50 characters"
                    },
                    phone: {
                        required: "Please enter a valid phone number",
                        maxlength: "Phone number must not exceed 15 digits"
                    },
                    address_line_1: "Please enter address line 1",
                    city: "Please enter the city",
                    postal_code: {
                        required: "Please enter the postal code",
                        digits: "Postal code must be numbers only",
                        maxlength: "Postal code must not exceed 10 digits"
                    },
                    country: "Please select a country",
                    state: "Please select a state"
                },
                errorPlacement: function(error, element) {
                    const name = element.attr("name");
                    $(`#${name}_error`).html(error);
                },
                submitHandler: function(form) {
                    event.preventDefault();
                    $.ajax({
                        url: "/students",
                        method: 'POST',
                        data: $(form).serialize(),
                        success: function(response) {
                            setTimeout(function() {
                                window.location.replace("/students");
                            }, 500);
                        },
                        error: function(xhr) {
                            const errors = xhr.responseJSON.errors;
                            let errorMessage = '';
                            $.each(errors, function(key, value) {
                                errorMessage += value[0] + '<br>';
                            });
                            alert(errorMessage);
                        }
                    });
                    return false;
                }
            });
        });
    </script>
</body>
</html>
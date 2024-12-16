
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Management')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- @stack('styles')  -->
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Student Management</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/students">Students</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<div id="content">
    <div class="register-student text-end mb-4">
        <a href="{{ route('students.create') }}" class="btn btn-primary">Register Student</a>
    </div>
    <form id="filterForm" class="mb-4 p-3 border rounded bg-light">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="font-weight-bold">Filter by Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ request('name') }}" placeholder="Enter name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email" class="font-weight-bold">Filter by Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ request('email') }}" placeholder="Enter email">
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12 d-flex justify-content-end">
                <button type="button" id="resetBtn" class="btn btn-dark mr-2">
                    <i class="fas fa-redo"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </div>
    </form>

    <table class="table table-bordered mt-3" id="studentsTable">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address Line 1</th>
                <th>Address Line 2</th>
                <th>City</th>
                <th>State</th>
                <th>Postal Code</th>
                <th>Country</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="studentsBody">
            @foreach($students as $student)
            <tr>
                <td>{{ $student->first_name }}</td>
                <td>{{ $student->last_name }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->phone }}</td>
                <td>{{ $student->address_line_1 }}</td>
                <td>{{ $student->address_line_2 }}</td>
                <td>{{ $student->city }}</td>
                <td>{{ $student->state }}</td>
                <td>{{ $student->postal_code }}</td>
                <td>{{ $student->country }}</td>
                <td style="width: 100px;">
                    <a href="javascript:void(0)" class="btn btn-sm btn-warning edit-btn" data-id="{{ $student->id }}" data-student="{{ json_encode($student) }}" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display:inline-block;" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger delete-btn" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $students->links() }} 

    <!-- Edit Student Modal -->
    <div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStudentModalLabel">Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editStudentForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editStudentId" name="id">
                        <div class="form-group">
                            <label for="editFirstName">First Name</label>
                            <input type="text" id="editFirstName" name="first_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="editLastName">Last Name</label>
                            <input type="text" id="editLastName" name="last_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" id="editEmail" name="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="editPhone">Phone</label>
                            <input type="text" id="editPhone" name="phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="editAddressLine1">Address Line 1</label>
                            <input type="text" id="editAddressLine1" name="address_line_1" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="editAddressLine2">Address Line 2</label>
                            <input type="text" id="editAddressLine2" name="address_line_2" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="editCity">City</label>
                            <input type="text" id="editCity" name="city" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="editState">State</label>
                            <input type="text" id="editState" name="state" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="editPostalCode">Postal Code</label>
                            <input type="text" id="editPostalCode" name="postal_code" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="editCountry">Country</label>
                            <input type="text" id="editCountry" name="country" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

 <!-- jQuery -->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    

<script>
    $('#filterForm').on('submit', function(event) {
        event.preventDefault(); 
        $.ajax({
            url: "{{ route('students.index') }}",
            method: 'GET',
            data: $(this).serialize(),
            success: function(data) {
                console.log(data);
                $('#studentsBody').empty(); 
                data.students.forEach(student => {
                    $('#studentsBody').append(`
                        <tr>
                            <td>${student.first_name}</td>
                            <td>${student.last_name}</td>
                            <td>${student.email}</td>
                            <td>${student.phone}</td>
                            <td>${student.address_line_1}</td>
                            <td>${student.address_line_2}</td>
                            <td>${student.city}</td>
                            <td>${student.state}</td>
                            <td>${student.postal_code}</td>
                            <td>${student.country}</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-btn" data-id="${student.id}" data-student='${JSON.stringify(student)}'>Edit</button>
                                <form action="/students/${student.id}" method="POST" style="display:inline-block;" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    `);
                });
            }
        });
    });

    $('#resetBtn').on('click', function() {
        console.log(12);
        $('#filterForm')[0].reset();
        window.location.href = "{{ route('students.index') }}";
    });

    $(document).on('click', '.edit-btn', function() {
        const student = $(this).data('student');
        $('#editStudentId').val(student.id);
        $('#editFirstName').val(student.first_name);
        $('#editLastName').val(student.last_name);
        $('#editEmail').val(student.email);
        $('#editPhone').val(student.phone);
        $('#editAddressLine1').val(student.address_line_1);
        $('#editAddressLine2').val(student.address_line_2);
        $('#editCity').val(student.city);
        $('#editState').val(student.state);
        $('#editPostalCode').val(student.postal_code);
        $('#editCountry').val(student.country);
        $('#editStudentModal').modal('show');
    });

    $('#editStudentForm').on('submit', function(event) {
        event.preventDefault(); 

        const id = $('#editStudentId').val();
        $.ajax({
            url: `/students/${id}`,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(data) {
                $('#editStudentModal').modal('hide');
                location.reload(); 
            },
            error: function(xhr) {
                alert('Error updating student: ' + xhr.responseJSON.message);
            }
        });
    });

    $(document).on('click', '.delete-btn', function() {
        const form = $(this).closest('.delete-form');
        if (confirm('Are you sure you want to delete this student?')) {
            form.submit();
        }
    });
</script>
</body>
</html>
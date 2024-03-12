<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple CRUD</title>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' />
    <link rel='stylesheet'
          href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css' />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
</head>
<body>
<div class="container">
    <div class="row my-5">
        <div class="col-lg-12">
            <h2>CRUD - Task 1</h2>
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="text-light">Manage Clients</h3>
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addClientModal"><i
                            class="bi-plus-circle me-2"></i>Add New Client</button>
                </div>
                <div class="card-body" id="show_all_clients">
                    <h1 class="text-center text-secondary my-5">Loading...</h1>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- new client modal --}}
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="exampleModalLabel"
     data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="add_client_form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 bg-light">
                    <div class="row">
                        <div class="col-lg">
                            <label for="fname">First Name</label>
                            <input type="text" name="fname" class="form-control" placeholder="First Name" required>
                        </div>
                        <div class="col-lg">
                            <label for="lname">Last Name</label>
                            <input type="text" name="lname" class="form-control" placeholder="Last Name" required>
                        </div>
                    </div>
                    <div class="my-2">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" class="form-control" placeholder="E-mail" required>
                    </div>

                    <div class="my-2">
                        <label for="address">Address</label>
                        <input type="address" name="address" class="form-control" placeholder="Address" required>
                    </div>

                    <div class="my-2">
                        <label for="phone">Phone</label>
                        <input type="phone" name="phone" class="form-control" placeholder="(99) 99999-9999" required>
                    </div>
                    <div class="my-2">
                        <input type="file" name="photo" id="photo" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="add_client_btn" class="btn btn-primary">Add Client</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- edit client modal --}}
<div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="exampleModalLabel"
     data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="edit_client_form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="emp_id" id="emp_id">
                <input type="hidden" name="emp_photo" id="emp_photo">
                <div class="modal-body p-4 bg-light">
                    <div class="row">
                        <div class="col-lg">
                            <label for="fname">First Name</label>
                            <input type="text" name="fname" id="fname" class="form-control" placeholder="First Name" required>
                        </div>
                        <div class="col-lg">
                            <label for="lname">Last Name</label>
                            <input type="text" name="lname" id="lname" class="form-control" placeholder="Last Name" required>
                        </div>
                    </div>
                    <div class="my-2">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="E-mail" required>

                        <label for="address">Address</label>
                        <input type="address" name="address" id="address" class="form-control" placeholder="Address" required>

                        <label for="phone">Phone</label>
                        <input type="phone" name="phone" id="phone" class="form-control" placeholder="(99) 99999-9999" required>
                    </div>
                    <div class="my-2">
                        <label for="photo">Select Photo</label>
                        <input type="file" name="photo" class="form-control">
                    </div>
                    <div class="mt-2" id="avatar"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="edit_client_btn" class="btn btn-success">Update Client</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src='https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(function() {

        // add new client ajax request
        $("#add_client_form").submit(function(e) {
            e.preventDefault();
            const fd = new FormData(this);
            $("#add_client_btn").text('Adding...');
            $.ajax({
                url: '{{ route('store') }}',
                method: 'post',
                data: fd,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status == 200) {
                        Swal.fire(
                            'Added!',
                            'Client Added Successfully!',
                            'success'
                        )
                        fetchAllClients();
                    }
                    $("#add_client_btn").text('Add Client');
                    $("#add_client_form")[0].reset();
                    $("#addClientModal").modal('hide');
                }
            });
        });

        // edit client ajax request
        $(document).on('click', '.editIcon', function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            $.ajax({
                url: '{{ route('edit') }}',
                method: 'get',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // console.log(response);
                    $("#fname").val(response.first_name);
                    $("#lname").val(response.last_name);
                    $("#email").val(response.email);
                    $("#address").val(response.address);
                    $("#phone").val(response.phone);

                    $("#avatar").html(
                        `<img src="storage/images/${response.photo}" width="100" class="img-fluid img-thumbnail">`);
                    $("#emp_id").val(response.id);
                    $("#emp_photo").val(response.photo);
                }
            });
        });

        // update client ajax request
        $("#edit_client_form").submit(function(e) {
            e.preventDefault();
            const fd = new FormData(this);
            $("#edit_client_btn").text('Updating...');
            $.ajax({
                url: '{{ route('update') }}',
                method: 'post',
                data: fd,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status == 200) {
                        Swal.fire(
                            'Updated!',
                            'Client Updated Successfully!',
                            'success'
                        )
                        fetchAllClients();
                    }
                    $("#edit_client_btn").text('Update Client');
                    $("#edit_client_form")[0].reset();
                    $("#editClientModal").modal('hide');
                }
            });
        });

        // delete client ajax request
        $(document).on('click', '.deleteIcon', function(e) {
            e.preventDefault();
            let id = $(this).attr('id');
            let csrf = '{{ csrf_token() }}';
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('delete') }}',
                        method: 'delete',
                        data: {
                            id: id,
                            _token: csrf
                        },
                        success: function(response) {
                            // console.log(response);
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            fetchAllClients();
                        }
                    });
                }
            })
        });

        // fetch all client ajax request
        fetchAllClients();

        function fetchAllClients() {
            $.ajax({
                url: '{{ route('fetchAll') }}',
                method: 'get',
                success: function(response) {
                    $("#show_all_clients").html(response);
                    $("table").DataTable({
                        order: [0, 'desc']
                    });
                }
            });
        }
    });
</script>
</body>
</html>

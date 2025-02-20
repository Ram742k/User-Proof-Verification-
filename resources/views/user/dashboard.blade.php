@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">User List</h2>
    <div class="row mb-3">
    <div class="col-md-4">
        <label>Filter by Status:</label>
        <select id="filterStatus" class="form-control">
            <option value="all">All</option>
            <option value="Not Submitted">Not Submitted</option>
            <option value="Waiting for Approval">Waiting for Approval</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
        </select>
    </div>
    <div class="col-md-4">
        <label>Search by Email:</label>
        <input type="text" id="searchEmail" class="form-control" placeholder="Enter email">
    </div>
    <div class="col-md-4 mt-4">
        <button id="applyFilters" class="btn btn-primary">Apply Filters</button>
    </div>
</div>

    <!-- User List Table -->
    <table class="table table-bordered" id="userList">
        <thead> 
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>ID Proof</th>
                <th>Address Proof</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Upload Documents</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($users))
                @foreach ($users as $user)
                    @if($user['role'] == 'user') <!-- ✅ Show only users with 'role: user' -->
                        <tr>
                            <td>{{ $user['name'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td>{{ ucfirst($user['role']) }}</td>
                            <td>
                                @if (!empty($user['id_proof']))
                                    <a href="{{ asset('storage/'.$user['id_proof']) }}" target="_blank">
                                        <img src="{{ asset('storage/'.$user['id_proof']) }}" width="60" height="60" class="rounded">
                                    </a>
                                @else
                                    <span class="text-danger">Not Uploaded</span>
                                @endif
                            </td>
                            <td>
                                @if (!empty($user['address_proof']))
                                    <a href="{{ asset('storage/'.$user['address_proof']) }}" target="_blank">
                                        <img src="{{ asset('storage/'.$user['address_proof']) }}" width="60" height="60" class="rounded">
                                    </a>
                                @else
                                    <span class="text-danger">Not Uploaded</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge 
                                    {{ $user['status'] == 'Approved' ? 'bg-success' : 
                                        ($user['status'] == 'Rejected' ? 'bg-danger' : 
                                        ($user['status'] == 'Waiting for Approval' ? 'bg-warning' : 'bg-secondary')) }}">
                                    {{ $user['status'] }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($user['created_at'])->format('d M Y, H:i') }}</td>
                            <td>
                                <!-- Upload Button (Opens Modal) -->
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $user['id'] }}">Upload</button>
                            </td>
                        </tr>

                        <!-- Upload Modal -->
                        <div class="modal fade" id="uploadModal{{ $user['id'] }}" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Upload Documents for {{ $user['name'] }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ url('/upload-proof/'.$user['id']) }}" method="POST" enctype="multipart/form-data" class="upload-form">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">ID Proof:</label>
                                                <input type="file" name="id_proof" class="form-control">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Address Proof:</label>
                                                <input type="file" name="address_proof" class="form-control">
                                            </div>
                                            <button type="submit" class="btn btn-success">Upload</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center text-danger">No Users Found</td>
                </tr>
            @endif
        </tbody>
    </table>
    <nav aria-label="Page navigation" class="d-flex justify-content-center mt-3">
    <ul class="pagination">
        @if ($users->onFirstPage())
            <li class="page-item disabled"><span class="page-link">«</span></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $users->previousPageUrl() }}">«</a></li>
        @endif

        @for ($i = 1; $i <= $users->lastPage(); $i++)
            <li class="page-item {{ ($users->currentPage() == $i) ? 'active' : '' }}">
                <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        @if ($users->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $users->nextPageUrl() }}">»</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">»</span></li>
        @endif
    </ul>
</nav>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- <script>
    $(document).ready(function () {
    function loadUsers(status = 'all', email = '', page = 1) {
        $.ajax({
            url: "/user?page=" + page,
            type: "GET",
            data: { status: status, email: email },
            success: function (response) {
                let userList = "";
                
                if (response.users.length === 0) {
                    userList = `<tr><td colspan="8" class="text-center text-danger">No Users Found</td></tr>`;
                } else {
                    response.users.forEach(user => {
                        let status = user.profile ? user.profile.status : "Not Submitted";

                        userList += `<tr>
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                            <td>${user.role}</td>
                            <td>
                                ${user.id_proof ? `<a href="/storage/${user.id_proof}" target="_blank"><img src="/storage/${user.id_proof}" width="60" height="60"></a>` : '<span class="text-danger">Not Uploaded</span>'}
                            </td>
                            <td>
                                ${user.address_proof ? `<a href="/storage/${user.address_proof}" target="_blank"><img src="/storage/${user.address_proof}" width="60" height="60"></a>` : '<span class="text-danger">Not Uploaded</span>'}
                            </td>
                            <td><span class="badge ${status == 'Approved' ? 'bg-success' : (status == 'Rejected' ? 'bg-danger' : (status == 'Waiting for Approval' ? 'bg-warning' : 'bg-secondary'))}">${status}</span></td>
                            <td>${user.created_at}</td>
                            <td><button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#uploadModal${user.id}">Upload</button></td>
                        </tr>`;
                    });
                }

                $("#userList").html(userList); // ✅ Update Table Rows
                $("#paginationLinks").html(response.pagination); // ✅ Update Pagination

                // ✅ Reattach event listener for pagination links
                $("#paginationLinks .page-link").click(function (e) {
                    e.preventDefault();
                    let page = $(this).attr("href").split("page=")[1];
                    loadUsers($("#filterStatus").val(), $("#searchEmail").val(), page);
                });
            },
            error: function (xhr, status, error) {
                console.log("AJAX Error:", xhr.responseText);
            }
        });
    }

    // ✅ Apply Filters
    $("#applyFilters").click(function () {
        loadUsers($("#filterStatus").val(), $("#searchEmail").val());
    });

    // ✅ Handle Document Upload
    $(".upload-form").submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let userId = form.attr("action").split("/").pop();

        $.ajax({
            url: form.attr("action"),
            type: "POST",
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function (response) {
                $("#uploadModal" + userId).modal('hide'); // ✅ Close Modal
                alert(response.message);
                loadUsers(); // ✅ Refresh user list after upload
            },
            error: function (xhr, status, error) {
                console.log("Upload Error:", xhr.responseText);
            }
        });
    });
});
      
</script> -->
<script>
$(document).ready(function () {
    function filterUsers(status = 'all', email = '', page = 1) {
        $.ajax({
            url: "{{ route('users.filter') }}?page=" + page,
            type: "GET",
            data: { status: status, email: email },
            success: function (response) {
                let userList = "";

                if (response.users.length === 0) {
                    userList = `<tr><td colspan="8" class="text-center text-danger">No Users Found</td></tr>`;
                } else {
                    response.users.forEach(user => {
                        let status = user.profile ? user.profile.status : "Not Submitted";

                        userList += `<tr>
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                            <td>${user.role}</td>
                            <td id="idProof-${user.id}">
                                ${user.id_proof ? `<a href="/storage/${user.id_proof}" target="_blank"><img src="/storage/${user.id_proof}" width="60" height="60"></a>` : '<span class="text-danger">Not Uploaded</span>'}
                            </td>
                            <td id="addressProof-${user.id}">
                                ${user.address_proof ? `<a href="/storage/${user.address_proof}" target="_blank"><img src="/storage/${user.address_proof}" width="60" height="60"></a>` : '<span class="text-danger">Not Uploaded</span>'}
                            </td>
                            <td><span class="badge ${status == 'Approved' ? 'bg-success' : (status == 'Rejected' ? 'bg-danger' : (status == 'Waiting for Approval' ? 'bg-warning' : 'bg-secondary'))}">${status}</span></td>
                            <td>${user.created_at}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#uploadModal${user.id}">Upload</button>
                            </td>
                        </tr>`;
                    });
                }

                $("#userList").html(userList); // ✅ Update Table Content
                $("#paginationLinks").html(response.pagination); // ✅ Update Pagination

                // ✅ Attach Pagination Click Event
                $("#paginationLinks .page-link").click(function (e) {
                    e.preventDefault();
                    let page = $(this).attr("href").split("page=")[1];
                    filterUsers($("#filterStatus").val(), $("#searchEmail").val(), page);
                });
            },
            error: function (xhr) {
                console.log("AJAX Error:", xhr.responseText);
            }
        });
    }

    // ✅ Apply Filters when the button is clicked
    $("#applyFilters").click(function () {
        filterUsers($("#filterStatus").val(), $("#searchEmail").val());
    });

    // ✅ Handle AJAX Document Upload
    $(".upload-form").submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let userId = form.data("userid");
        let formData = new FormData(this);

        $.ajax({
            url: form.attr("action"),
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // ✅ CSRF Protection
        },
            success: function (response) {
                $("#uploadModal" + userId).modal("hide");
                alert(response.message);
                
                
                
                // ✅ Update Uploaded Proof Images Dynamically
                if (response.id_proof) {
                    $("#idProof-" + userId).html(`<a href="/storage/${response.id_proof}" target="_blank"><img src="/storage/${response.id_proof}" width="60" height="60"></a>`);
                }
                if (response.address_proof) {
                    $("#addressProof-" + userId).html(`<a href="/storage/${response.address_proof}" target="_blank"><img src="/storage/${response.address_proof}" width="60" height="60"></a>`);
                }

                filterUsers(); // ✅ Refresh user list
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let errorMessages = "";
                $.each(errors, function (key, value) {
                    errorMessages += value[0] + "\n"; // Displaying first error for each field
                });
                alert("Upload Failed:\n" + errorMessages);
            } else {
                alert("An error occurred. Please try again.");
            }
            }
        });
    });
});

function closeModal(userId) {
    let modalElement = document.getElementById("uploadModal" + userId);
    if (modalElement) {
        let modalInstance = new bootstrap.Modal(modalElement);
        modalInstance.hide();
    }
}

</script>


@endsection

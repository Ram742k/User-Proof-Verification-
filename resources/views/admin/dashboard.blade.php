@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Admin Dashboard</h2>

    <!-- Filter & Search Form -->
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
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>ID Proof</th>
                <th>Address Proof</th>
                <th>Status</th>
                <th>Created Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="userList">
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    
                    <td>
                        @if ($user->profile && $user->profile->id_proof)
                            <a href="{{ asset('storage/'.$user->profile->id_proof) }}" target="_blank">
                                <img src="{{ asset('storage/'.$user->profile->id_proof) }}" width="60" height="60" class="rounded">
                            </a>
                        @else
                            <span class="text-danger">Not Uploaded</span>
                        @endif
                    </td>

                    <td>
                        @if ($user->profile && $user->profile->address_proof)
                            <a href="{{ asset('storage/'.$user->profile->address_proof) }}" target="_blank">
                                <img src="{{ asset('storage/'.$user->profile->address_proof) }}" width="60" height="60" class="rounded">
                            </a>
                        @else
                            <span class="text-danger">Not Uploaded</span>
                        @endif
                    </td>

                    <td>{{ $user->profile ? $user->profile->status : 'Not Submitted' }}</td>
                    <td>{{ \Carbon\Carbon::parse($user['created_at'])->format('d M Y, H:i') }}</td>

                    <td>
                        @if ($user->profile)
                            <button class="btn btn-success btn-sm" onclick="updateStatus({{ $user->profile->id }}, 'Approved')">Approve</button>
                            <button class="btn btn-danger btn-sm" onclick="updateStatus({{ $user->profile->id }}, 'Rejected')">Reject</button>
                        @else
                            <span class="text-danger">No Profile Found</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <nav id="paginationLinks">
        {{ $users->links() }}
    </nav>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    function loadUsers(status = 'all', email = '', page = 1) {
        $.ajax({
            url: "/admin/filter-users?page=" + page, 
            type: "GET",
            data: { status: status, email: email },
            success: function (response) {
                let userList = "";
                response.users.forEach(user => {
                    let status = user.profile ? user.profile.status : "Not Submitted";
                    let idProof = user.profile && user.profile.id_proof ? 
                        `<a href="/storage/${user.profile.id_proof}" target="_blank"><img src="/storage/${user.profile.id_proof}" width="60" height="60"></a>` 
                        : '<span class="text-danger">Not Uploaded</span>';

                    let addressProof = user.profile && user.profile.address_proof ? 
                        `<a href="/storage/${user.profile.address_proof}" target="_blank"><img src="/storage/${user.profile.address_proof}" width="60" height="60"></a>` 
                        : '<span class="text-danger">Not Uploaded</span>';

                    let actionButtons = user.profile ? `
                        <button class="btn btn-success btn-sm" onclick="updateStatus(${user.profile.id}, 'Approved')">Approve</button>
                        <button class="btn btn-danger btn-sm" onclick="updateStatus(${user.profile.id}, 'Rejected')">Reject</button>
                    ` : `<span class="text-danger">No Profile Found</span>`;

                    userList += `<tr>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>${idProof}</td>
                        <td>${addressProof}</td>
                        <td>${status}</td>
                        <td>${user.created_at}</td>
                        <td>${actionButtons}</td>
                    </tr>`;
                });

                $("#userList").html(userList);
                $("#paginationLinks").html(response.pagination); 

                // ✅ Fix Pagination Clicks
                $("#paginationLinks .page-link").click(function (e) {
                    e.preventDefault();
                    let page = $(this).attr("href").split("page=")[1]; 
                    loadUsers($("#filterStatus").val(), $("#searchEmail").val(), page);
                });
            }
        });
    }

    
    loadUsers();

    
    $("#applyFilters").click(function () {
        loadUsers($("#filterStatus").val(), $("#searchEmail").val());
    });

    // ✅ Update proof status via AJAX
    window.updateStatus = function (profileId, status) {
        $.ajax({
            url: "/admin/update-status",
            type: "POST",
            data: { profile_id: profileId, status: status, _token: "{{ csrf_token() }}" },
            success: function () {
                loadUsers($("#filterStatus").val(), $("#searchEmail").val());
            }
        });
    };
});
</script>
@endsection

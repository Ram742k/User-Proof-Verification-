<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index() {
        $users = User::with('profile')->paginate(1);
        return view('admin.dashboard', compact('users'));
    }

    public function updateStatus(Request $request) {
        $profile = Profile::find($request->profile_id);
        $profile->status = $request->status;
        $profile->save();

        return response()->json(['message' => 'Status updated successfully']);
    }

    // public function filterUsers(Request $request) {
    //     $query = User::with('profile'); // ✅ Ensure profile data is properly loaded
    
    //     // Sorting: Show "Waiting for Approval" first
    //     $query->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
    //           ->select('users.*', 'profiles.status', 'profiles.id as profile_id')
    //           ->orderByRaw("FIELD(profiles.status, 'Waiting for Approval', 'Not Submitted', 'Approved', 'Rejected')");
    
    //     // ✅ Filtering by proof status properly
    //     if ($request->status && $request->status != 'all') {
    //         if ($request->status == 'Not Submitted') {
    //             $query->whereNull('profiles.status'); // Only users with no profile
    //         } else {
    //             $query->where('profiles.status', $request->status); // Users with the selected status
    //         }
    //     }
    
    //     // ✅ Searching by email
    //     if ($request->email) {
    //         $query->where('users.email', 'LIKE', "%{$request->email}%");
    //     }
    
    //     $users = $query->paginate(10);
    
    //     return response()->json([
    //         'users' => $users,
    //         'pagination' => $users->links()->toHtml(),
    //     ]);
    // }

//     public function filterUsers(Request $request) {
//     $query = User::with('profile')
//         ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
//         ->select('users.*', 'profiles.status', 'profiles.id as profile_id')
//         ->orderByRaw("
//             CASE 
//                 WHEN profiles.status = 'Waiting for Approval' THEN 1 
//                 WHEN profiles.status = 'Not Submitted' THEN 2 
//                 WHEN profiles.status = 'Approved' THEN 3 
//                 WHEN profiles.status = 'Rejected' THEN 4 
//                 ELSE 5 
//             END
//         ");

//     // ✅ Filtering by proof status
//     if ($request->status && $request->status != 'all') {
//         if ($request->status == 'Not Submitted') {
//             $query->whereNull('profiles.status'); // Only users with no profile
//         } else {
//             $query->where('profiles.status', $request->status); // Filter selected status
//         }
//     }

//     // ✅ Searching by email
//     if ($request->email) {
//         $query->where('users.email', 'LIKE', "%{$request->email}%");
//     }

//     $users = $query->paginate(2); // ✅ Adjust pagination

//     return response()->json([
//         'users' => $users->items(), // ✅ Return only user data
//         'pagination' => view('pagination', ['users' => $users])->render(), // ✅ Use custom pagination view
//     ]);
// }

public function filterUsers(Request $request) {
    $query = User::with('profile')
        ->where('role', 'user') // ✅ Exclude admin users
        ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
        ->select('users.*', 'profiles.status', 'profiles.id as profile_id', 'profiles.id_proof', 'profiles.address_proof')
        ->orderByRaw("
            CASE 
                WHEN profiles.status = 'Waiting for Approval' THEN 1 
                WHEN profiles.status = 'Not Submitted' THEN 2 
                WHEN profiles.status = 'Approved' THEN 3 
                WHEN profiles.status = 'Rejected' THEN 4 
                ELSE 5 
            END
        ");

    // ✅ Filtering by proof status
    if ($request->status && $request->status != 'all') {
        if ($request->status == 'Not Submitted') {
            $query->whereNull('profiles.status');
        } else {
            $query->where('profiles.status', $request->status);
        }
    }

    // ✅ Searching by email
    if ($request->email) {
        $query->where('users.email', 'LIKE', "%{$request->email}%");
    }

    $users = $query->paginate(2);

    return response()->json([
        'users' => $users->items(),
        'pagination' => view('pagination', ['users' => $users])->render(),
    ]);
}

    
}

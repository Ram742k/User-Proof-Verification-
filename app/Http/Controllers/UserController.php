<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::with('profile')
                ->where('role', 'user') //  Only show users
                ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
                ->select('users.*', 'profiles.id_proof', 'profiles.address_proof', 'profiles.status', 'profiles.id as profile_id')
                ->orderByRaw("
                    CASE 
                        WHEN profiles.status = 'Waiting for Approval' THEN 1 
                        WHEN profiles.status = 'Not Submitted' THEN 2 
                        WHEN profiles.status = 'Approved' THEN 3 
                        WHEN profiles.status = 'Rejected' THEN 4 
                        ELSE 5 
                    END
                ")->paginate(5);
        return view('user.dashboard', compact('users'));
    }

public function filterUsers(Request $request)
{
    $query = User::with('profile')
        ->where('role', 'user') //  Show only users
        ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
        ->select('users.*', 'profiles.id_proof', 'profiles.address_proof', 'profiles.status', 'profiles.id as profile_id')
        ->orderByRaw("
            CASE 
                WHEN profiles.status = 'Waiting for Approval' THEN 1 
                WHEN profiles.status = 'Not Submitted' THEN 2 
                WHEN profiles.status = 'Approved' THEN 3 
                WHEN profiles.status = 'Rejected' THEN 4 
                ELSE 5 
            END
        ");

    //  Apply Filters
    if ($request->status && $request->status != 'all') {
        if ($request->status == 'Not Submitted') {
            $query->whereNull('profiles.status'); 
        } else {
            $query->where('profiles.status', $request->status);
        }
    }

    //  Apply Email Search
    if ($request->email) {
        $query->where('users.email', 'LIKE', "%{$request->email}%");
    }

    $users = $query->paginate(5);

    return response()->json([
        'users' => $users->items(),
        'pagination' => view('pagination', ['users' => $users])->render(),
    ]);
}


    //  Handle document upload & update status
    public function uploadProof(Request $request, $userId)
    {
        $request->validate([
            'id_proof' => 'nullable|mimes:jpg,png,pdf|max:2048',
            'address_proof' => 'nullable|mimes:jpg,png,pdf|max:2048'
        ]);

        $user = User::findOrFail($userId);
        $profile = $user->profile ?: new Profile(['user_id' => $user->id]);

        if ($request->hasFile('id_proof')) {
            $profile->id_proof = $request->file('id_proof')->store('proofs', 'public');
        }

        if ($request->hasFile('address_proof')) {
            $profile->address_proof = $request->file('address_proof')->store('proofs', 'public');
        }

        //  Set status to "Waiting for Approval" if at least one document is uploaded
        if ($request->hasFile('id_proof') || $request->hasFile('address_proof')) {
            $profile->status = 'Waiting for Approval';
        }

        $profile->save();

        return response()->json(['message' => 'Proofs uploaded successfully!', 'status' => $profile->status]);
    }
}

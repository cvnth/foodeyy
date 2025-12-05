<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUsersController extends Controller
{
    public function index()
    {
        return view('admin.users');
    }

    public function apiIndex(Request $request)
{
    // Initialize query
    // ADD ->withCount('orders') here. 
    // This creates an attribute "orders_count" in the JSON response.
    $query = User::withCount('orders'); 

    // --- 1. EXCLUDE ADMINS ---
    $query->where('is_admin', 0); 

    // --- 2. SEARCH LOGIC ---
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    // --- 3. SORT & PAGINATE ---
    $users = $query->orderBy('created_at', 'desc')->paginate(10);

    return response()->json($users);
}
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:500',
            'is_admin' => 'required|boolean'
        ]);

        $user->update($validated);

        return response()->json(['message' => 'User updated successfully!']);
    }

    public function destroy($id)
    {
        if (auth()->id() == $id) {
            return response()->json(['message' => 'Cannot delete yourself'], 403);
        }
        User::destroy($id);
        return response()->json(['message' => 'User deleted successfully']);
    }

    // app/Http/Controllers/Admin/AdminUsersController.php

    public function toggleBlock($id)
    {
        // Prevent blocking yourself
        if (auth()->id() == $id) {
            return response()->json(['message' => 'You cannot block your own account.'], 403);
        }

        $user = User::findOrFail($id);
        
        // Toggle the status (0 becomes 1, 1 becomes 0)
        $user->is_blocked = !$user->is_blocked;
        $user->save();

        $status = $user->is_blocked ? 'blocked' : 'unblocked';

        return response()->json([
            'message' => "User has been {$status} successfully.",
            'is_blocked' => $user->is_blocked
        ]);
    }
}
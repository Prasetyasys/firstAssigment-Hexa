<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Members;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MembersController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index(Request $request)
    {
        $query = Members::with(['class' => function ($query) {
            $query->withTrashed();
        }]);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Gender filter
        if ($request->has('gender')) {
            $query->where('gender', $request->gender);
        }

        // Class filter
        if ($request->has('class')) {
            $query->where('class_id', $request->class);
        }

        // Sorting
        if ($request->has('sort_by')) {
            $sort = $request->sort_by === 'latest' ? 'desc' : 'asc';
            $query->orderBy('created_at', $sort);
        }

        // Pagination
        $limit = $request->input('limit', 10);
        // $page = $request->input('page', 1);

        $members = $query->paginate($limit);

        // Transform the response to match required structure
        $members->through(function ($member) {
            return [
                'id' => $member->id,
                'class' => $member->class ? [
                    'id' => $member->class->id,
                    'name' => $member->class->name,
                    'description' => $member->class->description,
                    'created_at' => $member->class->created_at,
                    'updated_at' => $member->class->updated_at,
                    'deleted_at' => $member->class->deleted_at,
                ] : null,
                'first_name' => $member->first_name,
                'last_name' => $member->last_name,
                'email' => $member->email,
                'gender' => $member->gender,
                'address' => $member->address,
            ];
        });


        return response()->json($members);
    }
}

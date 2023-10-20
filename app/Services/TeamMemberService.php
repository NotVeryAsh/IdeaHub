<?php

namespace App\Services;

use App\Http\Requests\Teams\ListTeamMembersRequest;
use App\Models\Team;
use Illuminate\Pagination\LengthAwarePaginator;

class TeamMemberService
{
    public static function filter(ListTeamMembersRequest $request, Team $team): LengthAwarePaginator
    {
        // Get query parameters
        $searchTerm = $request->validated('search_term');
        $orderBy = $request->validated('order_by', 'name');
        $orderByDirection = $request->validated('order_by_direction', 'asc');
        $page = $request->validated('page', 1);
        $perPage = $request->validated('per_page', 15);

        $query = $team->members();

        // Search for users if a search term is provided
        if ($request->has('search_term')) {
            $query->where('name', 'like', "%{$searchTerm}%")
            ->orWhere('email', 'like', "%{$searchTerm}%")
            ->orWhere('username', 'like', "%{$searchTerm}%");
        }

        // order by first name and last name if order by name is provided
        if($orderBy === 'name') {
            $query->selectRaw('users.*, CONCAT(first_name, " ", last_name) AS name')
                ->orderBy('name', $orderByDirection);
        } else {
            $query->orderBy($orderBy, $orderByDirection);
        }

        return $query->paginate($perPage, ['*'], 'team_members', $page);
    }
}

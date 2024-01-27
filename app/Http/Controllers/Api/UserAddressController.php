<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserAddressResource;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserAddressController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        /** @var User $user */
        $user = Auth::user();
        return UserAddressResource::collection($user->addresses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): UserAddressResource
    {
        $validated = $request->validate([
           'address_line' => 'required|string'
        ]);

        $address = UserAddress::query()->create([
            UserAddress::ADDRESS_LINE => $validated['address_line'],
            UserAddress::USER_ID => Auth::id()
        ]);

        return new UserAddressResource($address);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserAddress $address): UserAddressResource
    {
        Gate::authorize('show', [$address]);

        return new UserAddressResource($address);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserAddress $address): UserAddressResource
    {
        Gate::authorize('update', [$address]);

        $validated = $request->validate(['address_line' => 'required|string']);

        $address->update([
            UserAddress::ADDRESS_LINE => $validated['address_line']
        ]);

        return new UserAddressResource($address);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserAddress $address): UserAddressResource
    {
        Gate::authorize('update', [$address]);

        $address->delete();

        return new UserAddressResource($address);
    }
}

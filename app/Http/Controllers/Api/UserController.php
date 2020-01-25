<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        return User::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return User
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\User  $user
     * @return User
     */
    public function update(Request $request, User $user)
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        $user->fill($data);
        $user->save();

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $user
     * @return User
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $user;
    }
}

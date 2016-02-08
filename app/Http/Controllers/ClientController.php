<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Client;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;
use CodeProject\Http\Controllers\Controller;

class ClientController extends Controller
{
    public function index()
    {
        return Client::all();
    }

    public function store(Request $request)
    {
        return Client::create($request->all());
    }

    public function update($id, Request $request)
    {
        Client::find($id)->update($request->all());

        return Client::find($id);
    }

    public function show($id)
    {
        return Client::find($id);
    }

    public function destroy($id)
    {
        Client::findOrFail($id)->delete();
    }
}

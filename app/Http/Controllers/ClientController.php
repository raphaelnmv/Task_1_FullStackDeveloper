<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index() {
        return view('clients.index');
    }

    public function fetchAll() {
        $clients = Client::all();
        $output = '';
        if ($clients->count() > 0) {
            $output .= '<table class="table table-striped align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Client</th>
                <th>E-mail</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>';
            foreach ($clients as $client) {
                $output .= '<tr>
                <td>' . $client->id . '</td>
                <td><img src="storage/images/' . $client->photo . '" width="50" class="img-thumbnail rounded-circle">' . $client->first_name . ' ' . $client->last_name . '</td>
                <td>' . $client->email . '</td>
                <td>
                  <a href="#" id="' . $client->id . '" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editClientModal"><i class="bi-pencil-square h4"></i></a>
                  <a href="#" id="' . $client->id . '" class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i></a>
                </td>
              </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class="text-center text-secondary my-5">No record in the database!</h1>';
        }
    }

    //insert a new client ajax request
    public function store(Request $request) {
        $file = $request->file('photo');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/images', $fileName);

        $clientData = [
            'first_name' => $request->fname,
            'last_name' => $request->lname,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
            'photo' => $fileName
        ];
        Client::create($clientData);
        return response()->json([
            'status' => 200,
        ]);
    }

    // edit an client ajax request
    public function edit(Request $request) {
        $id = $request->id;
        $data = Client::find($id);
        return response()->json($data);
    }

    // update an client ajax request
    public function update(Request $request) {
        $fileName = '';
        $client = Client::find($request->emp_id);
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images', $fileName);
            if ($client->photo) {
                Storage::delete('public/images/' . $client->photo);
            }
        } else {
            $fileName = $request->photo;
        }

        $cltData = ['first_name' => $request->fname, 'last_name' => $request->lname, 'email' => $request->email, 'address' => $request->address, 'phone' =>$request->phone, 'photo' => $fileName];

        $client->update($cltData);
        return response()->json([
            'status' => 200,
        ]);
    }

    // delete an client ajax request
    public function delete(Request $request) {
        $id = $request->id;
        $client = Client::find($id);
        if (Storage::delete('public/images/' . $client->photo)) {
            Client::destroy($id);
        }
    }
}

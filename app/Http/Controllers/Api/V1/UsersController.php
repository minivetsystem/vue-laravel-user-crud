<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Hash;
use Validator;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query= User::where('id', '<>', '');
        if ($request->input('search') != "") {
            $query->whereRaw("CONCAT_WS(' ', name, email) LIKE ?", "%" . $request->input('search') . "%");
        }
        $query->orderBy('id', 'DESC');

        return $query->get();
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $data_msg = [];
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email'
        ]);

        $validator->after(function ($validator) use ($request, $id) {
            if ($request->input('email') != "") {
                $m = User::where('email', $request->input('email'))->where('id', '<>', $id)->first();
                if (!empty($m)) {
                    $validator->errors()->add('email', 'Email already exists.');
                }
            }
        });

        if ($validator->passes()) {
            $user = User::find($id);
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->save();

            $data_msg['type'] = 'success';
            $data_msg['user'] =$user;

        }else{
            $errors = [];
            foreach ($validator->errors()->getMessages() as $key => $val) {
                $errors[$key] = $val[0];
            }
            $data_msg['errors'] = $errors;
            $data_msg['type'] = "warning";
        }


        return response()->json($data_msg);
    }

    public function store(Request $request)
    {
        $data_msg = [];
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|max:16',

        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->input('email') != "") {
                $m = User::where('email', $request->input('email'))->first();
                if (!empty($m)) {
                    $validator->errors()->add('email', 'Email already exists.');
                }
            }
        });

        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->save();

            $data_msg['type'] = 'success';
            $data_msg['user'] =$user;

        }else{
            $errors = [];
            foreach ($validator->errors()->getMessages() as $key => $val) {
                $errors[$key] = $val[0];
            }
            $data_msg['errors'] = $errors;
            $data_msg['type'] = "warning";
        }


        return response()->json($data_msg);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return '';
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function showEmpleado($empleado)
    {
        return User::find($empleado);
    }
    public function recoverPassword()
    {
        return view('user.recover-password');
    }
    public function getUsers(User $empleado, Request $request)
    {
        $empleado = new User;

        if ($request->name != '') {
            $empleado = $empleado->where('complete_name', 'like', '%' . $request->name . '%');
        }
        if ($request->username != '') {
            $empleado = $empleado->where('username', 'like', '%' . $request->username . '%');
        }
        if ($request->email != '') {
            $empleado = $empleado->where('email', 'like', '%' . $request->email . '%');
        }
        if ($request->user_type != '') {
            $empleado = $empleado->where('user_type', 'like', '%' . $request->user_type . '%');
        }
        $empleado = $empleado->paginate(10);
        return [
            'pagination' => [
                'total'        => $empleado->total(),
                'current_page' => $empleado->currentPage(),
                'per_page'     => $empleado->perPage(),
                'last_page'    => $empleado->lastPage(),
                'from'         => $empleado->firstItem(),
                'to'           => $empleado->lastItem(),
            ],
            'empleado' => $empleado
        ];
    }
    public function updatePassword(Request $request)
    {
        $folio = auth()->user()->id;
        $password = $request['password'];
        $usuario = User::query()
            ->where('id', $folio)
            ->update([
                'password' => bcrypt($password),
            ]);
        return $usuario;
    }

    public function delete($id)
    {
        $partlog = User::find($id);
        $partlog->delete();
        return $partlog;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
        ]);
        if ($validator->fails()) {
            return ['state' => false, 'messages' => $validator->errors()];
        } else {

            if (!isset($request->empleado['id'])) {
                $user = new User;
                $user->complete_name = $request->empleado['complete_name'];
                $user->username = $request->empleado['username'];
                $user->email = $request->empleado['email'];
                $user->user_type = $request->empleado['user_type'];
                $user->password = bcrypt('temporal');
                $user->save();
            } else {
                $user = User::updateOrCreate(
                    [
                        'id' => $request->empleado['id'] ?? null
                    ],
                    [
                        'complete_name' => $request->empleado['complete_name'],
                        'username' => $request->empleado['username'],
                        'email' => $request->empleado['email'],
                        'user_type' => $request->empleado['user_type'],
                    ]
                );
            }


            return ['state' => true, 'facility' => $user];
        }
    }
    public function reset(Request $request)
    {
        $user = User::find($request['id']);

        $user->password = bcrypt('temporal');
        $user->save();
        return $user;
    }
}

<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Response;
use App\Models\User;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Contracts\Service\Attribute\Required;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return $request->user()->name;
        $request->user()->id;
    }
    public function getProfile(Request $request)
    {
        $data = auth('api')->user()->id;
        echo $data;
        //try {
        //    $user_id = auth('api')->user()->id;
        //    $data = User::find($user_id);
        //    return response()->json(['status' => 'true', 'message' => 'User Profile', 'data' => $data]);
        //} catch (\Exception $e) {
        //    return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        //}
    }
    public function index()
    {
        try {
            $user_id = auth('api')->user()->id;
            $data = User::find($user_id);
            return response()->json(['status' => 'true', 'message' => 'User Profile', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }
    public function update(Request $request)
    {
        $user_id = auth('api')->user()->id;
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2|max:45',
                'email' => 'required|email|unique:users,id,' . $user_id,
                'foto_profil' => 'nullable|image'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(['status' => 'false', $error, 'data' => []], 422);
            } else {
                $user = User::find($user_id);
                $user->username = $request->username;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->jenis_kelamin = $request->jenis_kelamin;
                $user->tanggal_lahir = $request->tanggal_lahir;
                $user->no_hp = $request->no_hp;
                if ($request->foto_profil && $request->foto_profil->isValid()) {
                    $file_name = time() . '.' . $request->foto_profil->extension();
                    $request->foto_profil->move(public_path('images'), $file_name);
                    $path = "public/images/$file_name";
                    $user->foto_profil = $path;
                }
                $user->update();
                return response()->json(['status' => 'true', 'message' => 'Profil Berhasil diubah', 'data' => $user]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }

    public function checkPassword(Request $request)
    {
        $request->validate(['password' => 'required|string']);
        return response()->json([
            'status' => \Hash::check($request->password, auth()->user()->password),
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $request->validate(['password' => 'required|string|confirmed']);
        $user = User::find(auth()->user()->id);
        $user->update(['password' => \Hash::make($request->password)]);
        return response()->json($user);
    }

    public function all(Request $request)
    {
        return User::where('name', 'like', '%'.$request->q.'%')->where('role', 'like', '%'.$request->role.'%')->get();
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        if ($user->id != auth()->user()->id) {
            $user->delete();
        }
        return response()->json('', 204);
    }
}

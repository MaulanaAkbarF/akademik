<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Golongan;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized access');
            }
            return $next($request);
        });
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $golongan = Golongan::all();
        return view('auth.register', compact('golongan'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,dosen,mahasiswa'],
        ];

        // Add role-specific validation
        if ($data['role'] === 'dosen') {
            $rules['nip'] = ['required', 'string', 'max:20', 'unique:dosen'];
            $rules['alamat_dosen'] = ['required', 'string', 'max:25'];
            $rules['nohp_dosen'] = ['required', 'string', 'max:15'];
        } elseif ($data['role'] === 'mahasiswa') {
            $rules['nim'] = ['required', 'string', 'max:50', 'unique:mahasiswa'];
            $rules['alamat_mahasiswa'] = ['required', 'string', 'max:50'];
            $rules['nohp_mahasiswa'] = ['required', 'string', 'max:50'];
            $rules['semester'] = ['required', 'integer', 'min:1', 'max:8'];
            $rules['id_golongan'] = ['required', 'exists:golongan,id_golongan'];
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Create user first
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
            ]);

            // Create role-specific data and update user
            if ($data['role'] === 'dosen') {
                $dosen = Dosen::create([
                    'nip' => $data['nip'],
                    'nama' => $data['name'],
                    'alamat' => $data['alamat_dosen'],
                    'nohp' => $data['nohp_dosen'],
                ]);
                
                $user->update(['nip' => $dosen->nip]);
            } elseif ($data['role'] === 'mahasiswa') {
                $mahasiswa = Mahasiswa::create([
                    'nim' => $data['nim'],
                    'nama' => $data['name'],
                    'alamat' => $data['alamat_mahasiswa'],
                    'nohp' => $data['nohp_mahasiswa'],
                    'semester' => $data['semester'],
                    'id_golongan' => $data['id_golongan'],
                    'id_user_mahasiswa' => $user->id,
                ]);
                
                $user->update(['nim' => $mahasiswa->nim]);
            }

            return $user;
        });
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'User berhasil didaftarkan!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tugas;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index(){
        $data = array(
            'title'             => 'Data Tugas',
            'menuAdminTugas'    => 'active',
            'tugas'             => Tugas::with('user')->get(),
        );
        return view('admin/tugas/index',$data);
    }

    public function create(){
        $data = array(
            'title'             => 'Tambah Data Tugas',
            'menuAdminTugas'    => 'active',
            'user'              => User::where('jabatan','Karyawan')
            ->where('is_tugas',false)->get(),
        );
        return view('admin/tugas/create',$data);
    }

    public function store(Request $request){
        $request->validate([
            'user_id'          => 'required',
            'tugas'            => 'required',
            'tanggal_mulai'    => 'required',
            'tanggal_selesai'  => 'required',
        ],[
            'user_id.required'         => 'Nama Tidak Boleh Kosong',
            'tugas.required'           => 'Tugas Tidak Boleh Kosong',
            'tanggal_mulai.required'   => 'Tanggal Mulai Tidak Boleh Kosong',
            'tanggal_selesai.required' => 'Tanggal Selesai Tidak Boleh Kosong',
        ]);
      
        $user = User::findOrfail($request->user_id);
        $tugas = new Tugas;
        $tugas->user_id         = $request->user_id;
        $tugas->tugas           = $request->tugas;
        $tugas->tanggal_mulai   = $request->tanggal_mulai;
        $tugas->tanggal_selesai = $request->tanggal_selesai;
        $tugas->save();
        $user->is_tugas = true;
        $user->save();

        return redirect()->route('tugas')->with('success','Data Berhasil Ditambahkan');
    }

    public function edit($id){
        $data = array(
            'title'             => 'Edit Data Tugas',
            'menuAdminTugas'    => 'active',
            'tugas'             => Tugas::with('user')->findOrfail($id),
            
        );
        return view('admin/tugas/update',$data);
    }

    public function update(Request $request, $id){
        $request->validate([
            'tugas'            => 'required',
            'tanggal_mulai'    => 'required',
            'tanggal_selesai'  => 'required',
        ],[
            'tugas.required'           => 'Tugas Tidak Boleh Kosong',
            'tanggal_mulai.required'   => 'Tanggal Mulai Tidak Boleh Kosong',
            'tanggal_selesai.required' => 'Tanggal Selesai Tidak Boleh Kosong',
        ]);

        $tugas = Tugas::findOrfail($id);
        $tugas->tugas           = $request->tugas;
        $tugas->tanggal_mulai   = $request->tanggal_mulai;
        $tugas->tanggal_selesai = $request->tanggal_selesai;
        $tugas->save();

        return redirect()->route('tugas')->with('success','Data Berhasil Di Edit');
    }

    public function destroy($id) {
        $tugas = Tugas::findOrfail($id);
        $tugas->delete();
        $user = User::where('id',$tugas->user_id)->first();
        $user->is_tugas = false;
        $user->save();

        return redirect()->route('tugas')->with('success','Data Berhasil Di Hapus');
    }

}

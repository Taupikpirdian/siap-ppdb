<?php

namespace App\Http\Controllers\admin;

use PDF;
use DB;
use App\Group;
use Auth;
use View;
use Image;
use File;
use Session;
use App\Sekolah;
use App\Report;
use App\UserSekolah;
use App\Tunggakan;
use App\Siswa;
use App\Exports\ExportAlumni;
use App\Imports\SiswaImport;
use App\Exports\SiswaExport;
use App\Exports\ExportSiswaTidakAktif;
use Maatwebsite\Excel\Facades\Excel;
use App\Year;
use App\Program;
use App\PaymentType;
use App\Cost;
use App\Candidate;
use App\DetailPaymentCandidate;
use App\PembayaranSiswaAktif;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class SiswaController extends Controller
{
    public function index()
    {
        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                    ->where('user_groups.user_id', Auth::id())
                    ->select('groups.name AS group')
                    ->first();

        if($login->group!='Admin')
        {
            $user = Auth::user();
            $id_user = Auth::user()->id;
            $student = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
            ->orderBy('siswas.created_at', 'desc')
            ->select(
                    'siswas.npm',
                    'siswas.rfid',
                    'siswas.nama_siswa',
                    'siswas.tempat_lahir',
                    'siswas.kelas',
                    'siswas.subkelas',
                    'siswas.nama_ayah',
                    'siswas.hp_ayah',
                    'siswas.nama_ibu',
                    'siswas.hp_ibu',
                    'siswas.nama_wali',
                    'siswas.hp_wali',
                    'siswas.alamat',
                    'siswas.kecamatan',
                    'siswas.kota_kab',
                    'siswas.semester',
                    'siswas.status',
                    'siswas.foto',
                    'programs.*',
                    'years.*',
                    'sekolahs.nama_sekolah',
                    'siswas.id'
                    )
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->where('status', '=', "Aktif")
            ->paginate(25);
            return view('admin.siswa.list', array('student'=>$student, 'user' => $user));

        }else{
            $user = Auth::user();
            $student = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
            ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
            ->orderBy('siswas.created_at', 'desc')
            ->select(
                'siswas.npm',
                'siswas.rfid',
                'siswas.nama_siswa',
                'siswas.tempat_lahir',
                'siswas.kelas',
                'siswas.subkelas',
                'siswas.nama_ayah',
                'siswas.hp_ayah',
                'siswas.nama_ibu',
                'siswas.hp_ibu',
                'siswas.nama_wali',
                'siswas.hp_wali',
                'siswas.alamat',
                'siswas.kecamatan',
                'siswas.kota_kab',
                'siswas.semester',
                'siswas.status',
                'siswas.foto',
                'programs.*',
                'years.*',
                'sekolahs.nama_sekolah',
                'siswas.id'
                )
            ->where('status', '=', "Aktif")
            ->paginate(25);
            return view('admin.siswa.list', array('student'=>$student, 'user' => $user));
        }
    }   

    public function siswa_tidak_aktif()
    {
        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                    ->where('user_groups.user_id', Auth::id())
                    ->select('groups.name AS group')
                    ->first();

        if($login->group!='Admin')
        {
            $user = Auth::user();
            $id_user = Auth::user()->id;

            $student = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
            ->orderBy('siswas.created_at', 'desc')
            ->select(
                    'siswas.npm',
                    'siswas.rfid',
                    'siswas.nama_siswa',
                    'siswas.tempat_lahir',
                    'siswas.kelas',
                    'siswas.subkelas',
                    'siswas.nama_ayah',
                    'siswas.hp_ayah',
                    'siswas.nama_ibu',
                    'siswas.hp_ibu',
                    'siswas.nama_wali',
                    'siswas.hp_wali',
                    'siswas.alamat',
                    'siswas.kecamatan',
                    'siswas.kota_kab',
                    'siswas.semester',
                    'siswas.status',
                    'siswas.foto',
                    'programs.*',
                    'years.*',
                    'sekolahs.nama_sekolah',
                    'siswas.id'
                    )
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->where('siswas.status', '=', "Tidak Aktif")
            ->paginate(25);
            return view('admin.siswa.siswa_tidak_aktif', array('student'=>$student, 'user' => $user));
        }else{
            $user = Auth::user();
            $student = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
            ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
            ->orderBy('siswas.created_at', 'desc')
            ->where('siswas.status', '=', "Tidak Aktif")
            ->select(
                'siswas.npm',
                'siswas.rfid',
                'siswas.nama_siswa',
                'siswas.tempat_lahir',
                'siswas.kelas',
                'siswas.subkelas',
                'siswas.nama_ayah',
                'siswas.hp_ayah',
                'siswas.nama_ibu',
                'siswas.hp_ibu',
                'siswas.nama_wali',
                'siswas.hp_wali',
                'siswas.alamat',
                'siswas.kecamatan',
                'siswas.kota_kab',
                'siswas.semester',
                'siswas.status',
                'siswas.foto',
                'programs.*',
                'years.*',
                'sekolahs.nama_sekolah',
                'siswas.id'
                )
            ->paginate(25);
            return view('admin.siswa.siswa_tidak_aktif', array('student'=>$student, 'user' => $user));
        }
    }

    public function alumni()
    {
        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                    ->where('user_groups.user_id', Auth::id())
                    ->select('groups.name AS group')
                    ->first();

        if($login->group!='Admin')
        {
            $user = Auth::user();
            $id_user = Auth::user()->id;

            $student = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
            ->orderBy('siswas.created_at', 'desc')
            ->select(
                    'siswas.npm',
                    'siswas.rfid',
                    'siswas.nama_siswa',
                    'siswas.tempat_lahir',
                    'siswas.kelas',
                    'siswas.subkelas',
                    'siswas.nama_ayah',
                    'siswas.hp_ayah',
                    'siswas.nama_ibu',
                    'siswas.hp_ibu',
                    'siswas.nama_wali',
                    'siswas.hp_wali',
                    'siswas.alamat',
                    'siswas.kecamatan',
                    'siswas.kota_kab',
                    'siswas.semester',
                    'siswas.status',
                    'siswas.foto',
                    'programs.*',
                    'years.*',
                    'sekolahs.nama_sekolah',
                    'siswas.id'
                    )
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->where('siswas.status', '=', "Alumni")
            ->paginate(25);
            return view('admin.siswa.alumni', array('student'=>$student, 'user' => $user));
        }else{
            $user = Auth::user();
            $student = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
            ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
            ->orderBy('siswas.created_at', 'desc')
            ->where('siswas.status', '=', "Alumni")
            ->select(
                'siswas.npm',
                'siswas.rfid',
                'siswas.nama_siswa',
                'siswas.tempat_lahir',
                'siswas.kelas',
                'siswas.subkelas',
                'siswas.nama_ayah',
                'siswas.hp_ayah',
                'siswas.nama_ibu',
                'siswas.hp_ibu',
                'siswas.nama_wali',
                'siswas.hp_wali',
                'siswas.alamat',
                'siswas.kecamatan',
                'siswas.kota_kab',
                'siswas.semester',
                'siswas.status',
                'siswas.foto',
                'programs.*',
                'years.*',
                'sekolahs.nama_sekolah',
                'siswas.id'
                )
            ->paginate(25);
            return view('admin.siswa.alumni', array('student'=>$student, 'user' => $user));
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $scholls=Sekolah::pluck('nama_sekolah', 'id');
        $scholls->prepend('Pilih Nama Sekolah', '');
        $years=Year::pluck('tahun', 'id');
        $years->prepend('Pilih Tahun', '');
        $progs=Program::pluck('nama', 'id');
        $progs->prepend('Pilih Program', '');
        $payments=PaymentType::pluck('name', 'id');
        $payments->prepend('Pilih Jenis Pembayaran', '');
        $status = array(
            'Calon'     => 'Calon',
            'Aktif'     => 'Aktif',
            'Alumni'    => 'Alumni'
        );

        return View::make('admin.siswa.create', compact('scholls','years','status', 'progs', 'payments'), array('user' => $user));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'npm' => 'unique:siswas,npm|max:20',
            'nama_siswa' => 'required',
            'status' => 'required',
            'sekolah_id' => 'required',
            'thn_id' => 'required',
            'program_id' => 'required',
        ],
        [
            'sekolah_id.required' => 'Nama sekolah belum dipilih',
            'npm.max' => 'NPM tidak boleh lebih dari 20',
            'npm.unique' => 'NPM sudah digunakan',
            'nama_siswa.required' => 'Nama siswa tidak boleh kosong',
            'status.required' => 'Status belum dipilih',
            'sekolah_id.required' => 'Nama sekolah belum dipilih',
            'thn_id.required' => 'Tahun belum dipilih',
            'program_id.required' => 'Tahun belum dipilih'
        ]
        );
        
        // store
        $student= new Siswa;
        $student->sekolah_id        = Input::get('sekolah_id');
        $student->thn_id            = Input::get('thn_id');
        $student->program_id        = Input::get('program_id');
        $student->npm               = Input::get('npm');
        $student->rfid              = Input::get('rfid');
        $student->nama_siswa        = Input::get('nama_siswa');
        $student->tempat_lahir      = Input::get('tempat_lahir');
        $student->tgl_lahir         = Input::get('tgl_lahir');
        $student->kelas             = Input::get('kelas');
        $student->subkelas          = Input::get('subkelas');
        $student->nama_ayah         = Input::get('nama_ayah');
        $student->hp_ayah           = Input::get('hp_ayah');
        $student->nama_ibu          = Input::get('nama_ibu');
        $student->hp_ibu            = Input::get('hp_ibu');
        $student->nama_wali         = Input::get('nama_wali');
        $student->hp_wali           = Input::get('hp_wali');
        $student->alamat            = Input::get('alamat');
        $student->kecamatan         = Input::get('kecamatan');
        $student->kota_kab          = Input::get('kota_kab');
        $student->status            = Input::get('status');
        $student->semester          = Input::get('semester');

        // New Script
        $img = $request->file('foto');
        // Kalo pas diedit gambar diganti / masukin gambar
        if($img) {
            $img = $request->file('foto');
            $imageName = time().'.'.$img->getClientOriginalExtension();
            //thumbs
            $destinationPath = public_path('images/siswa/thumbs');
                if(!File::exists($destinationPath)){
                    if(File::makeDirectory($destinationPath,0777,true)){
                    throw new \Exception("Unable to upload to invoices directory make sure it is read / writable.");  
                }
            }
                $image = Image::make($img->getRealPath());
                $image->fit(200, 200);
                $image->save($destinationPath.'/'.$imageName);

            //original
            $destinationPath = public_path('images/siswa');
                if(!File::exists($destinationPath)){
                    if(File::makeDirectory($destinationPath,0777,true)){
                        throw new \Exception("Unable to upload to invoices directory make sure it is read / writable.");  
                }
            }
            $img = Image::make($img)->encode('jpg', 50);
            $img->save($destinationPath.'/'.$imageName);
            //save data image to db
            $student->foto = $imageName;
        }
        // End New Script
        $student->save();

        // redirect
        return Redirect::action('admin\SiswaController@index')->with('flash-store','Data berhasil ditambahkan.');
    }
  
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $user = Auth::user();
        $student = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
        ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
        ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
        ->orderBy('siswas.created_at', 'desc')
        ->where('siswas.id', $id)
        ->select(
            'siswas.id',
            'siswas.npm',
            'siswas.rfid',
            'siswas.nama_siswa',
            'siswas.tempat_lahir',
            'siswas.tgl_lahir',
            'siswas.kelas',
            'siswas.subkelas',
            'siswas.nama_ayah',
            'siswas.hp_ayah',
            'siswas.nama_ibu',
            'siswas.hp_ibu',
            'siswas.nama_wali',
            'siswas.hp_wali',
            'siswas.alamat',
            'siswas.kecamatan',
            'siswas.semester',
            'siswas.kota_kab',
            'siswas.status',
            'siswas.foto',
            'sekolahs.nama_sekolah',
            'programs.nama',
            'years.tahun'
        )
        ->firstOrFail();

        return view('admin.siswa.show', compact('student'),array('user' => $user));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $user = Auth::user();
        $scholl=Sekolah::pluck('nama_sekolah', 'id');
        $year=Year::pluck('tahun', 'id');
        $prog=Program::pluck('nama', 'id');
        $status = array(
            'Calon'     => 'Calon',
            'Aktif'     => 'Aktif',
            'Alumni'    => 'Alumni'
        );

        if ($user->id == 3) {
            $is_kasir = true;
        }else{
            $is_kasir = false;
        }
        $student = Siswa::where('siswas.id', $id)->firstOrFail();   

        return view('admin.siswa.edit', compact('is_kasir', 'student', 'scholl', 'year', 'prog', 'status'),array('user' => $user));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'npm'  => 'max:20|unique:siswas,npm,'. $id .'',
            'nama_siswa'  => 'required',
            'status'      => 'required',
            'sekolah_id'  => 'required',
            'thn_id'      => 'required',
            'program_id'  => 'required',
        ],
        [
            'sekolah_id.required' => 'Nama sekolah belum dipilih',
            'npm.max'             => 'NPM tidak boleh lebih dari 12',
            'npm.unique'          => 'NPM sudah digunakan',
            'nama_siswa.required' => 'Nama siswa tidak boleh kosong',
            'status.required'     => 'Status belum dipilih',
            'sekolah_id.required' => 'Nama sekolah belum dipilih',
            'thn_id.required'     => 'Tahun belum dipilih',
            'program_id.required' => 'Tahun belum dipilih'
        ]
        );

        $student = Siswa::findOrFail($id); 
        $student->npm               = Input::get('npm');
        $student->rfid              = Input::get('rfid');
        $student->nama_siswa        = Input::get('nama_siswa');
        $student->tempat_lahir      = Input::get('tempat_lahir');
        $student->tgl_lahir         = Input::get('tgl_lahir');
        $student->kelas             = Input::get('kelas');
        $student->subkelas          = Input::get('subkelas');
        $student->nama_ayah         = Input::get('nama_ayah');
        $student->hp_ayah           = Input::get('hp_ayah');
        $student->nama_ibu          = Input::get('nama_ibu');
        $student->hp_ibu            = Input::get('hp_ibu');
        $student->nama_wali         = Input::get('nama_wali');
        $student->hp_wali           = Input::get('hp_wali');
        $student->alamat            = Input::get('alamat');
        $student->kecamatan         = Input::get('kecamatan');
        $student->kota_kab          = Input::get('kota_kab');
        $student->status            = Input::get('status');
        $student->semester          = Input::get('semester');
        $student->thn_id            = Input::get('thn_id');
        $student->program_id        = Input::get('program_id');

        // New Script
        $img = $request->file('foto');
	    // Kalo pas diedit gambar diganti / masukin gambar
	    if($img) {
	        $img = $request->file('foto');
            $imageName = time().'.'.$img->getClientOriginalExtension();

            //thumbs
            $destinationPath = public_path('images/siswa/thumbs');
                if(!File::exists($destinationPath)){
                    if(File::makeDirectory($destinationPath,0777,true)){
                    throw new \Exception("Unable to upload to invoices directory make sure it is read / writable.");  
                }
            }
                $image = Image::make($img->getRealPath());
                $image->fit(200, 200);
                $image->save($destinationPath.'/'.$imageName);

            //original
            $destinationPath = public_path('images/siswa');
                if(!File::exists($destinationPath)){
                    if(File::makeDirectory($destinationPath,0777,true)){
                        throw new \Exception("Unable to upload to invoices directory make sure it is read / writable.");  
                }
            }
            $img = Image::make($img)->encode('jpg', 50);
            $img->save($destinationPath.'/'.$imageName);
            $student->foto = $imageName;
	    }
        // End New Script
        $student->save();
        return Redirect::action('admin\SiswaController@payment', compact('student'))->with('flash-update','Data berhasil diubah.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {

    if(Auth::user()->hasAnyRole('Delete Siswa')){
        $siswa = Siswa::findOrFail($id);
        $siswas = PembayaranSiswaAktif::orderBy('pembayaran_siswa_aktifs.created_at','asc')
        ->leftJoin('siswas', 'siswas.npm', '=', 'pembayaran_siswa_aktifs.npm')
        ->where('pembayaran_siswa_aktifs.npm', '=', $siswa->npm)
        ->get();

        if(!$siswas->isEmpty()){
            return Redirect::action('admin\SiswaController@index')->with('flash-error','Data Siswa tidak bisa dihapus karena sudah diacu di Data Pembayaran SPP, Silakan menghapus data yang mengacu Data Siswa terlebih dahulu.');  
        }else{
            $siswa->delete();
            return Redirect::action('admin\SiswaController@index')->with('flash-success','Data Siswa sudah berhasil dihapus.');
        }
        }else{
            return Redirect::action('admin\AdminController@memberfeed')->with('flash-error','ERROR PERMISSIONS.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function search(Request $request){
        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                    ->where('user_groups.user_id', Auth::id())
                    ->select('groups.name AS group')
                    ->first();

        if($login->group!='Admin')
        {
            $user = Auth::user();
            $id_user = Auth::user()->id;
            $search = $request->get('search');

            $student = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
            ->where('nama_siswa','LIKE','%'.$search.'%')
            ->orwhere('nama_sekolah','LIKE','%'.$search.'%')
            ->orderBy('siswas.created_at', 'desc')
            ->select(
                    'siswas.id as id_siswa',
                    'siswas.npm',
                    'siswas.rfid',
                    'siswas.nama_siswa',
                    'siswas.tempat_lahir',
                    'siswas.kelas',
                    'siswas.subkelas',
                    'siswas.nama_ayah',
                    'siswas.hp_ayah',
                    'siswas.nama_ibu',
                    'siswas.hp_ibu',
                    'siswas.nama_wali',
                    'siswas.hp_wali',
                    'siswas.alamat',
                    'siswas.kecamatan',
                    'siswas.kota_kab',
                    'siswas.semester',
                    'siswas.status',
                    'siswas.foto',
                    'programs.*',
                    'years.*',
                    'sekolahs.nama_sekolah')
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->paginate(25);
            return view('admin.siswa.list', array('student'=>$student, 'user' => $user));
        }else{
            $user = Auth::user();
            $search = $request->get('search');
            $student = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
            ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
            ->where('nama_siswa','LIKE','%'.$search.'%')
            ->orwhere('nama_sekolah','LIKE','%'.$search.'%')
            ->orderBy('siswas.created_at', 'desc')
            ->select(
                'siswas.id as id_siswa',
                'siswas.npm',
                'siswas.rfid',
                'siswas.nama_siswa',
                'siswas.tempat_lahir',
                'siswas.kelas',
                'siswas.subkelas',
                'siswas.nama_ayah',
                'siswas.hp_ayah',
                'siswas.nama_ibu',
                'siswas.hp_ibu',
                'siswas.nama_wali',
                'siswas.hp_wali',
                'siswas.alamat',
                'siswas.kecamatan',
                'siswas.kota_kab',
                'siswas.semester',
                'siswas.status',
                'siswas.foto',
                'programs.*',
                'years.*',
                'sekolahs.nama_sekolah')
            ->paginate(25);
            return view('admin.siswa.list', array('student'=>$student, 'user' => $user));
        }
    }

    public function searchsekolahonsiswa(Request $request){
        $user = Auth::user();
        $search = $request->get('search');
        $student = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
        ->orderBy('siswas.created_at', 'desc')
        ->where('nama_sekolah','LIKE','%'.$search.'%')
        ->select(
            'siswas.id',
            'siswas.npm',
            'siswas.rfid',
            'siswas.nama_siswa',
            'siswas.tempat_lahir',
            'siswas.kelas',
            'siswas.subkelas',
            'siswas.nama_ayah',
            'siswas.hp_ayah',
            'siswas.nama_ibu',
            'siswas.hp_ibu',
            'siswas.nama_wali',
            'siswas.hp_wali',
            'siswas.alamat',
            'siswas.kecamatan',
            'siswas.semester',
            'siswas.kota_kab',
            'siswas.status',
            'siswas.foto',
            'sekolahs.nama_sekolah')
        ->paginate(25);
        return view('admin.siswa.list', compact('student'),array('user' => $user));
    }

    public function export_tidak_aktif()
    {
        return Excel::download(new ExportSiswaTidakAktif, 'Siswa_Tidak_Aktif.xlsx');
    }

    public function export_alumni()
    {
        return Excel::download(new ExportAlumni, 'Alumni.xlsx');
    }

    public function exportFile($type){

    $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                ->where('user_groups.user_id', Auth::id())
                ->select('groups.name AS group')
                ->first();
    if($login->group=='Admin')
    {
        $user = Auth::user();
        $id_user = Auth::user()->id;
        $siswa = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
            ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
            ->orderBy('siswas.created_at', 'desc')
            ->select(
                    'sekolahs.nama_sekolah',
                    'siswas.npm',
                    'siswas.rfid',
                    'siswas.nama_siswa',
                    'programs.nama',
                    'years.tahun',
                    'siswas.tempat_lahir',
                    'siswas.tgl_lahir',
                    'siswas.kelas',
                    'siswas.subkelas',
                    'siswas.semester',
                    'siswas.nama_ayah',
                    'siswas.hp_ayah',
                    'siswas.nama_ibu',
                    'siswas.hp_ibu',
                    'siswas.nama_wali',
                    'siswas.hp_wali',
                    'siswas.alamat',
                    'siswas.kecamatan',
                    'siswas.kota_kab',
                    'siswas.status'
                    )
            ->get();
        }else{
        $user = Auth::user();
        $id_user = Auth::user()->id;
        $siswa = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
            ->orderBy('siswas.created_at', 'desc')
            ->select(
                    'sekolahs.nama_sekolah',
                    'siswas.npm',
                    'siswas.rfid',
                    'siswas.nama_siswa',
                    'programs.nama',
                    'years.tahun',
                    'siswas.tempat_lahir',
                    'siswas.tgl_lahir',
                    'siswas.kelas',
                    'siswas.subkelas',
                    'siswas.semester',
                    'siswas.nama_ayah',
                    'siswas.hp_ayah',
                    'siswas.nama_ibu',
                    'siswas.hp_ibu',
                    'siswas.nama_wali',
                    'siswas.hp_wali',
                    'siswas.alamat',
                    'siswas.kecamatan',
                    'siswas.kota_kab',
                    'siswas.status'
                    )
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->get();
        }
            return Excel::download(new SiswaExport, 'siswa.xlsx');
        }

        public function payment($id)
        {
        $user = Auth::user();
        $student = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
        ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
        ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
        ->orderBy('siswas.created_at', 'desc')
        ->where('siswas.id', $id)
        ->select(
            'siswas.id',
            'siswas.npm',
            'siswas.rfid',
            'siswas.nama_siswa',
            'siswas.tempat_lahir',
            'siswas.tgl_lahir',
            'siswas.kelas',
            'siswas.subkelas',
            'siswas.nama_ayah',
            'siswas.hp_ayah',
            'siswas.nama_ibu',
            'siswas.hp_ibu',
            'siswas.nama_wali',
            'siswas.hp_wali',
            'siswas.alamat',
            'siswas.kecamatan',
            'siswas.semester',
            'siswas.kota_kab',
            'siswas.status',
            'siswas.foto',
            'siswas.sekolah_id',
            'siswas.thn_id',
            'siswas.program_id',
            'sekolahs.nama_sekolah',
            'programs.nama as nm_prog',
            'years.tahun'
        )
        ->firstOrFail();

        $candidates = Siswa::orderBy('siswas.created_at', 'desc')
        ->leftjoin('detail_payment_candidates', 'detail_payment_candidates.candidates_id', '=', 'siswas.id')
        ->leftjoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
        ->select(
                  'siswas.*',
                  'detail_payment_candidates.tgl_bayar',
                  'detail_payment_candidates.candidates_id',
                  'detail_payment_candidates.payment_id',
                  'detail_payment_candidates.cost_id',
                  'payment_types.name',
                  'detail_payment_candidates.id as id_invoice'                )
        // ->whereCandidateId('id')
        // ->whereCandidatesId('candidate_id')
        ->where('detail_payment_candidates.candidates_id', '=', $student->id)
        ->get();

        $jumlah = 0;
        foreach ($candidates as $key => $value) {
            $jumlah += $value->cost_id;
            $value->amounts = $jumlah;
        }

        $spp = Siswa::orderBy('pembayaran_siswa_aktifs.created_at', 'desc')
        ->rightjoin('pembayaran_siswa_aktifs', 'pembayaran_siswa_aktifs.candidate_id', '=', 'siswas.id')
        ->select(
                  'siswas.*',
                  'pembayaran_siswa_aktifs.*',
                  'pembayaran_siswa_aktifs.id as id_invoice'
                 )
        ->where('pembayaran_siswa_aktifs.candidate_id', '=', $student->id)
        ->get();

        // get data untuk pengecekan isi
        $pembayaran_siswa_aktif = PembayaranSiswaAktif::orderBy('created_at', 'desc')
        ->select(
            'pembayaran_siswa_aktifs.*'
        )
        ->where('pembayaran_siswa_aktifs.candidate_id', '=', $student->id)
        ->get();

        // kondisi jika data tidak kosong
        if(!$pembayaran_siswa_aktif->isEmpty()){
            $subjumlah = DB::table('pembayaran_siswa_aktifs')
            ->where('pembayaran_siswa_aktifs.candidate_id', '=', $student->id)
            ->sum('amount');

            $pembayaran_siswa_aktifs = PembayaranSiswaAktif::orderBy('created_at', 'desc')
            ->select(
                'pembayaran_siswa_aktifs.payment_id'
            )
            ->where('pembayaran_siswa_aktifs.candidate_id', '=', $student->id)
            ->firstOrFail();

            $costTotal = Cost::leftjoin('pembayaran_siswa_aktifs', 'pembayaran_siswa_aktifs.sekolah_id', '=', 'costs.sekolah_id')
            ->where('costs.sekolah_id', '=', $student->sekolah_id)
            ->where('costs.thn_id', '=', $student->thn_id)
            ->where('costs.program_id', '=', $student->program_id)
            ->where('costs.payment_id', '=', $pembayaran_siswa_aktifs->payment_id)
            ->firstOrFail();
            $jumlahseluruh = ($costTotal->name)*($student->semester);
        }else{
            $subjumlah=0;
            $jumlahseluruh=0;
        }
        //sampai sini

        // Penjumlahan persemester
        $semester_1 = DB::table('pembayaran_siswa_aktifs')
            ->where('pembayaran_siswa_aktifs.candidate_id', '=', $student->id)
            ->where('pembayaran_siswa_aktifs.semester', '=', 1)
            ->sum('amount');

        $semester_2 = DB::table('pembayaran_siswa_aktifs')
            ->where('pembayaran_siswa_aktifs.candidate_id', '=', $student->id)
            ->where('pembayaran_siswa_aktifs.semester', '=', 2)
            ->sum('amount');

        $semester_3 = DB::table('pembayaran_siswa_aktifs')
            ->where('pembayaran_siswa_aktifs.candidate_id', '=', $student->id)
            ->where('pembayaran_siswa_aktifs.semester', '=', 3)
            ->sum('amount');
            
        $semester_4 = DB::table('pembayaran_siswa_aktifs')
            ->where('pembayaran_siswa_aktifs.candidate_id', '=', $student->id)
            ->where('pembayaran_siswa_aktifs.semester', '=', 4)
            ->sum('amount');
            
        $semester_5 = DB::table('pembayaran_siswa_aktifs')
            ->where('pembayaran_siswa_aktifs.candidate_id', '=', $student->id)
            ->where('pembayaran_siswa_aktifs.semester', '=', 5)
            ->sum('amount');
            
        $semester_6 = DB::table('pembayaran_siswa_aktifs')
            ->where('pembayaran_siswa_aktifs.candidate_id', '=', $student->id)
            ->where('pembayaran_siswa_aktifs.semester', '=', 6)
            ->sum('amount');

        if ($pembayaran_siswa_aktif) {
            $spp_persemester = Cost::leftjoin('pembayaran_siswa_aktifs', 'pembayaran_siswa_aktifs.sekolah_id', '=', 'costs.sekolah_id')
                ->where('costs.sekolah_id', '=', $student->sekolah_id)
                ->where('costs.thn_id', '=', $student->thn_id)
                ->where('costs.program_id', '=', $student->program_id)
                ->where('costs.payment_id', '=', 3)
                ->first();
        }
        // End

        $scholls=Sekolah::pluck('nama_sekolah', 'id');
        $scholls->prepend('Pilih Nama Sekolah', '');
        $years=Year::pluck('tahun', 'id');
        $years->prepend('Pilih Tahun', '');
        $progs=Program::pluck('nama', 'id');
        $progs->prepend('Pilih Program', '');
        $payments=PaymentType::whereIn('id', ['3'])->pluck('name', 'id');
        $payments->prepend('Pilih Jenis Pembayaran', '');
        $semester = array(
            '1'     => '1',
            '2'     => '2',
            '3'     => '3',
            '4'     => '4',
            '5'     => '5',
            '6'     => '6',
        );
        return view('admin.siswa.payment', compact('semester', 'student', 'scholls', 'candidates', 'spp', 'years', 'progs', 'payments', 'subjumlah', 'jumlahseluruh', 'pembayaran_siswa_aktif', 'semester_1', 'semester_2', 'semester_3', 'semester_4', 'semester_5', 'semester_6', 'spp_persemester'),array('user' => $user));
    }

    public function invoice($id)
    {   
        $user = Auth::user();
        $id_user = Auth::user()->id;
        $name_user = Auth::user()->name;
        $pay = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
            ->leftjoin('pembayaran_siswa_aktifs', 'pembayaran_siswa_aktifs.candidate_id', '=', 'siswas.id')
            ->leftjoin('payment_types', 'payment_types.id', '=', 'pembayaran_siswa_aktifs.payment_id')
            ->leftJoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->orderBy('pembayaran_siswa_aktifs.created_at', 'asc')
            ->where('pembayaran_siswa_aktifs.id', $id)
            ->select(   
                'sekolahs.nama_sekolah',
                'pembayaran_siswa_aktifs.payment_id as cost_name',
                'payment_types.name as payment_name',
                'programs.nama as program_nama',
                'pembayaran_siswa_aktifs.no_kwitansi as no_kwitansi',
                'siswas.id as no_urut',
                'siswas.nama_siswa',
                'siswas.tgl_lahir',
                'siswas.semester',
                'siswas.thn_id',
                'siswas.program_id',
                'pembayaran_siswa_aktifs.*'
            )
            // ->where('user_id', '=', $id_user)
            ->firstOrFail();

        $semester_1 = PembayaranSiswaAktif::where('candidate_id', $pay->no_urut)->where('semester', 1)->get();
        $jumlah_spp_1 = 0;
        foreach ($semester_1 as $key => $spp_1) {
            $jumlah_spp_1 += $spp_1->amount ;
            $spp1 = $jumlah_spp_1;
        }

        $semester_2 = PembayaranSiswaAktif::where('candidate_id', $pay->no_urut)->where('semester', 2)->get();
        if(count($semester_2) == 0){
            $spp2 = 0;
        }else{
            $jumlah_spp_2 = 0;
            foreach ($semester_2 as $key => $spp_2) {
                $jumlah_spp_2 += $spp_2->amount ;
                $spp2 = $jumlah_spp_2;
            }
        }

        $semester_3 = PembayaranSiswaAktif::where('candidate_id', $pay->no_urut)->where('semester', 3)->get();
        if(count($semester_3) == 0){
            $spp3 = 0;
        }else{
            $jumlah_spp_3 = 0;
            foreach ($semester_3 as $key => $spp_3) {
                $jumlah_spp_3 += $spp_3->amount ;
                $spp3 = $jumlah_spp_3;
            }
        }

        $semester_4 = PembayaranSiswaAktif::where('candidate_id', $pay->no_urut)->where('semester', 4)->get();
        if(count($semester_4) == 0){
            $spp4 = 0;
        }else{
            $jumlah_spp_4 = 0;
            foreach ($semester_4 as $key => $spp_4) {
                $jumlah_spp_4 += $spp_4->amount ;
                $spp4 = $jumlah_spp_4;
            }
        }

        $semester_5 = PembayaranSiswaAktif::where('candidate_id', $pay->no_urut)->where('semester', 5)->get();
        if(count($semester_5) == 0){
            $spp5 = 0;
        }else{
            $jumlah_spp_5 = 0;
            foreach ($semester_5 as $key => $spp_5) {
                $jumlah_spp_5 += $spp_5->amount ;
                $spp5 = $jumlah_spp_5;
            }
        }

        $semester_6 = PembayaranSiswaAktif::where('candidate_id', $pay->no_urut)->where('semester', 6)->get();
        if(count($semester_6) == 0){
            $spp6 = 0;
        }else{
            $jumlah_spp_6 = 0;
            foreach ($semester_2 as $key => $spp_6) {
                $jumlah_spp_6 += $spp_6->amount ;
                $spp6 = $jumlah_spp_6;
            }
        }

        $must_pay = Cost::where('thn_id', $pay->thn_id)->where('program_id', $pay->program_id)->where('payment_id', 3)->first();
        if($must_pay->name == $spp1){
            $status_spp1 = "Lunas";
        }else{
            $status_spp1 = "";
        }

        if($must_pay->name == $spp2){
            $status_spp2 = "Lunas";
        }else{
            $status_spp2 = "";
        }

        if($must_pay->name == $spp3){
            $status_spp3 = "Lunas";
        }else{
            $status_spp3 = "";
        }

        if($must_pay->name == $spp4){
            $status_spp4 = "Lunas";
        }else{
            $status_spp4 = "";
        }

        if($must_pay->name == $spp5){
            $status_spp5 = "Lunas";
        }else{
            $status_spp5 = "";
        }

        if($must_pay->name == $spp6){
            $status_spp6 = "Lunas";
        }else{
            $status_spp6 = "";
        }

        // echo "<pre>";
        // print_r($status_spp6);
        // echo "</pre>";
        // exit();

        return view('admin.siswa.invoice', compact('status_spp1', 'status_spp2', 'status_spp3', 'status_spp4', 'status_spp5', 'status_spp6', 'spp1', 'spp2', 'spp3', 'spp4', 'spp5', 'spp6', 'name_user'), array('pay'=>$pay, 'user' => $user));
    }

    public function pay(Request $request)
    {
        $user = Auth::user();
        $id_user = Auth::user()->id;

        $student = Siswa::orderBy('created_at', 'desc')
        ->where('siswas.id', Input::get('siswa_id'))
        ->firstOrFail();

        $student_report = Report::leftjoin('siswas', 'siswas.id', '=', 'reports.candidate_id')
        ->where('reports.candidate_id', '=', $student->id)
        ->get();

        // echo "<pre>";
        // print_r($student_report);
        // echo "</pre>";
        // exit();

        $pay = new PembayaranSiswaAktif;
        $pay->npm             = Input::get('npm');
        $pay->candidate_id    = Input::get('siswa_id');
        $pay->user_id         = $id_user;
        $pay->tgl_bayar       = Input::get('tgl_bayar');
        $pay->sekolah_id      = Input::get('sekolah_id');

        $sekolah = Sekolah::where('id', $pay->sekolah_id)->firstOrFail();
        $sekolah->invoice_no = $sekolah->invoice_no + 1;
        $sekolah->save();

        $pay->no_kwitansi     = $sekolah->invoice_no;
        $pay->amount          = Input::get('amount');
        $pay->ket             = Input::get('ket');
        $pay->semester        = Input::get('semester');
        $pay->payment_id      = Input::get('payment_id');

        $costTotal = Cost::leftjoin('pembayaran_siswa_aktifs', 'pembayaran_siswa_aktifs.sekolah_id', '=', 'costs.sekolah_id')
        ->where('costs.sekolah_id', '=', $pay->sekolah_id)
        ->where('costs.thn_id', '=', $student->thn_id)
        ->where('costs.program_id', '=', $student->program_id)
        ->where('costs.payment_id', '=', $pay->payment_id)
        ->firstOrFail();

        $semester_spp = PembayaranSiswaAktif::where('pembayaran_siswa_aktifs.candidate_id', '=', $pay->candidate_id)
        ->get();

        $subjumlah = DB::table('pembayaran_siswa_aktifs')
        ->where('pembayaran_siswa_aktifs.candidate_id', '=', $pay->candidate_id)
        ->sum('amount');

        // kondisi jika pembayaran berlebihan
        if ($pay->semester == 1) {
            $semester_spp = PembayaranSiswaAktif::where('pembayaran_siswa_aktifs.candidate_id', '=', $pay->candidate_id)
            ->where('pembayaran_siswa_aktifs.semester', '=', 1)
            ->sum('amount');
            $sum_with_input = $semester_spp + $pay->amount;
            if($sum_with_input > $costTotal->name){
                return Redirect::back()->withErrors(['Pembayaran melebihi jumlah seharusnya', 'The Message']);
            }
        } elseif($pay->semester == 2) {
            $semester_spp = PembayaranSiswaAktif::where('pembayaran_siswa_aktifs.candidate_id', '=', $pay->candidate_id)
            ->where('pembayaran_siswa_aktifs.semester', '=', 2)
            ->sum('amount');
            $sum_with_input = $semester_spp + $pay->amount;
            if($sum_with_input > $costTotal->name){
                return Redirect::back()->withErrors(['Pembayaran melebihi jumlah seharusnya', 'The Message']);
            }
        } elseif($pay->semester == 3) {
            $semester_spp = PembayaranSiswaAktif::where('pembayaran_siswa_aktifs.candidate_id', '=', $pay->candidate_id)
            ->where('pembayaran_siswa_aktifs.semester', '=', 3)
            ->sum('amount');
            $sum_with_input = $semester_spp + $pay->amount;
            if($sum_with_input > $costTotal->name){
                return Redirect::back()->withErrors(['Pembayaran melebihi jumlah seharusnya', 'The Message']);
            }
        } elseif($pay->semester == 4) {
            $semester_spp = PembayaranSiswaAktif::where('pembayaran_siswa_aktifs.candidate_id', '=', $pay->candidate_id)
            ->where('pembayaran_siswa_aktifs.semester', '=', 4)
            ->sum('amount');
            $sum_with_input = $semester_spp + $pay->amount;
            if($sum_with_input > $costTotal->name){
                return Redirect::back()->withErrors(['Pembayaran melebihi jumlah seharusnya', 'The Message']);
            }
        } elseif($pay->semester == 5) {
            $semester_spp = PembayaranSiswaAktif::where('pembayaran_siswa_aktifs.candidate_id', '=', $pay->candidate_id)
            ->where('pembayaran_siswa_aktifs.semester', '=', 5)
            ->sum('amount');
            $sum_with_input = $semester_spp + $pay->amount;
            if($sum_with_input > $costTotal->name){
                return Redirect::back()->withErrors(['Pembayaran melebihi jumlah seharusnya', 'The Message']);
            }
        } elseif($pay->semester == 6) {
            $semester_spp = PembayaranSiswaAktif::where('pembayaran_siswa_aktifs.candidate_id', '=', $pay->candidate_id)
            ->where('pembayaran_siswa_aktifs.semester', '=', 6)
            ->sum('amount');
            $sum_with_input = $semester_spp + $pay->amount;
            if($sum_with_input > $costTotal->name){
                return Redirect::back()->withErrors(['Pembayaran melebihi jumlah seharusnya', 'The Message']);
            }
        }
        // End
        
        $spp_report_update = Report::orderBy('created_at', 'desc')
        ->where('candidate_id', $student->id)
        ->first();

        $spp_masuk = $subjumlah + $pay->amount;
        if (empty($spp_report_update)) {
        }else{
            $spp_update = $spp_report_update->has_payment_spp + $pay->amount;
        }
        $spp = $costTotal->name * $pay->semester;
        $remained = $spp - $spp_masuk;
        
        // #1 Ketika memiliki candidate_id dan payment diisi 3, work
        if ($student->candidate_id && $pay->payment_id == 3) {
            $report = Report::orderBy('reports.created_at', 'desc')
            ->where('candidate_id', $student->id)
            ->first();

            // Masuk ke table tunggakans
            $tunggakan = Tunggakan::where('candidates_id', $student->id)
            ->first();
            $tunggakan->npm                  = Input::get('npm');
            $tunggakan->spp                  = $costTotal->name;
            $tunggakan->payment_amount       = $tunggakan->payment_amount + $pay->amount;
            $tunggakan->leftovers            = ($student->semester*$costTotal->name) - $tunggakan->payment_amount;
            $tunggakan->save();
                      
            // Ketika tanggal pembayarannya sama
            if ($report->last_date_payment == $pay->tgl_bayar) {
                $report = Report::orderBy('reports.created_at', 'desc')
                ->where('candidate_id', $student->id)
                ->firstOrFail();
                // Jika pernah membayar di tanggal yang sama, maka menjumlah, jika tidak maka tidak menjumlah
                if ($report->has_payment_spp) {
                    // npm terus update sesuai candidate id yang sama, error
                    $report->npm                  = Input::get('npm');
                    // $report->spp                  = $costTotal->name;
                    $report->candidate_id         = $report->candidate_id;
                    $report->has_payment_spp      = $spp_update;
                    // $report->payment_amount       = $spp_masuk;
                    $report->last_date_payment    = $pay->tgl_bayar;
                    $report->save();
                } else {
                    $report->npm                  = Input::get('npm');
                    // $report->spp                  = $costTotal->name;
                    $report->candidate_id         = $report->candidate_id;
                    $report->has_payment_spp      = $pay->amount;
                    // $report->payment_amount       = $spp_masuk;
                    $report->last_date_payment    = $pay->tgl_bayar;
                    $report->save();
                }

            } else {
                $report_new = new Report;
                $report_new->npm                  = Input::get('npm');
                // $report_new->spp                  = $costTotal->name;
                $report_new->has_payment_spp      = $pay->amount;
                // $report_new->payment_amount       = $spp_masuk;
                $report_new->sekolah_id           = $pay->sekolah_id;
                $report_new->candidate_id         = $student->id;
                $report_new->last_date_payment    = $pay->tgl_bayar;
                $report_new->save();  
            }
        // #2 Ketika npm siswa bersangkutas di table report tidak ada, work
        } elseif ( $student_report->isEmpty() ) {
            $report_new = new Report;
            $report_new->npm                  = Input::get('npm');
            // $report_new->spp                  = $costTotal->name;
            $report_new->has_payment_spp      = $pay->amount;
            // $report_new->payment_amount       = $spp_masuk;
            $report_new->sekolah_id           = $pay->sekolah_id;
            $report_new->candidate_id         = $student->id;
            $report_new->last_date_payment    = $pay->tgl_bayar;
            $report_new->save();

            $tunggakan = new Tunggakan;
            $tunggakan->npm                  = Input::get('npm');
            $tunggakan->candidates_id        = $student->id;
            $tunggakan->spp                  = $costTotal->name;
            $tunggakan->sekolah_id           = $pay->sekolah_id;
            $tunggakan->payment_amount       = $tunggakan->payment_amount + $pay->amount;
            $tunggakan->leftovers            = ($student->semester*$costTotal->name) - $tunggakan->payment_amount;
            $tunggakan->save();            
        // #3 Ketika sudah ada npm siswa bersangkutan di table report dan payment diisi 3, work
        } elseif ( $student_report && $pay->payment_id == 3 ) {

            // Masuk ke table tunggakans
            $tunggakan = Tunggakan::where('candidates_id', $student->id)
            ->first();
            $tunggakan->npm                  = Input::get('npm');
            $tunggakan->spp                  = $costTotal->name;
            $tunggakan->sekolah_id           = $pay->sekolah_id;
            $tunggakan->payment_amount       = $tunggakan->payment_amount + $pay->amount;
            $tunggakan->leftovers            = ($student->semester*$costTotal->name) - $tunggakan->payment_amount;
            $tunggakan->save();

            $report = Report::orderBy('created_at', 'desc')
            ->where('candidate_id', $student->id)
            ->firstOrFail();
            if ($report->last_date_payment == $pay->tgl_bayar) {
                $report->npm                  = Input::get('npm');
                // $report->spp                  = $costTotal->name;
                $report->has_payment_spp      = $spp_update;
                // $report->payment_amount       = $spp_masuk;
                $report->sekolah_id           = $pay->sekolah_id;
                $report->last_date_payment    = $pay->tgl_bayar;
                $report->save();            
            } else {
                $report_new = new Report;
                $report_new->npm                  = Input::get('npm');
                $report_new->candidate_id         = $student->id;
                // $report_new->spp                  = $costTotal->name;
                $report_new->has_payment_spp      = $pay->amount;
                // $report_new->payment_amount       = $spp_masuk;
                $report_new->sekolah_id           = $pay->sekolah_id;
                $report_new->last_date_payment    = $pay->tgl_bayar;
                $report_new->save();
            }
        }

        $status_tunggakan = Tunggakan::orderBy('tunggakans.created_at', 'desc')
        ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
        ->where('tunggakans.candidates_id', $student->id)
        ->first();

        if ($status_tunggakan) {
            if ( ($status_tunggakan->payment_amount >= ($status_tunggakan->spp*$status_tunggakan->semester) ) ) {
            
                // #Status tunggakan masuk ke table report
                // $tunggakan = Report::where('candidate_id', $student->candidate_id)
                // ->get();
                // foreach ($tunggakan as $key => $status) {
                //     $status->arrears_status = 1;
                //     $status->save();
                // }
    
                $tunggakan_on_table_tunggakan = Tunggakan::where('candidates_id', $student->id)
                ->first();
                $tunggakan_on_table_tunggakan->arrears_status = 1;
                $tunggakan_on_table_tunggakan->save();
            } else {
                // #Status tunggakan masuk ke table report
                // $tunggakan = Report::where('candidate_id', $student->candidate_id)
                // ->get();
                // foreach ($tunggakan as $key => $status) {
                //     $status->arrears_status = 2;
                //     $status->save();
                // }
    
                $tunggakan_on_table_tunggakan = Tunggakan::where('candidates_id', $student->id)
                ->first();
                $tunggakan_on_table_tunggakan->arrears_status = 2;
                $tunggakan_on_table_tunggakan->save();
            }
        } else {
            $tunggakan_on_table_tunggakan = Tunggakan::where('candidates_id', $student->id)
            ->first();
            $tunggakan_on_table_tunggakan->arrears_status = 1;
            $tunggakan_on_table_tunggakan->save();
        }
        
        $npm_update = Report::orderBy('reports.created_at', 'desc')
        ->where('candidate_id', $student->id)
        ->get();
        // update npm
        foreach ($npm_update as $key => $value) {
            $value->npm                  = Input::get('npm');
            $value->save();
        }
        
        // $report->save();
        $pay->save();
        return Redirect::back()->with('flash-store','Pembayaran berhasil dilakukan.');
      }

    public function ajax_getsekolah_tahun(Request $request)
    {
        $students = Siswa::where('cost_id','=',$request->get('cost_id'))->get();

        return response()->json($students);
    }

    public function reset(Request $request)
    {       
        $dataDate = date('dm');
        $kode = Input::get('kode');

        $student = Siswa::orderBy('created_at', 'desc')->get();

        if ( $kode == $dataDate) {
                       
            // if ( ($tunggakan_join_npm->payment_amount >= ($tunggakan_join_npm->spp*$tunggakan_join_npm->semester) ) ) {
            //     $tunggakan_join_npm->arrears_status = 1;
            //     $tunggakan_join_npm->save();
            // } else {
            //     $tunggakan_join_npm->arrears_status = 2;
            //     $tunggakan_join_npm->save();
            // }

            foreach ($student as $key => $value) {
            if ( $value->semester < 6 && $value->status == 'Aktif') {
                    $value->semester               = $value->semester + 1;
                    $value->save();

                    $tunggakan_join_npm = Tunggakan::orderBy('created_at', 'desc')->get();
                    foreach ($tunggakan_join_npm as $key => $status) {
                        $status->arrears_status = 2;
                        $status->save();
                    }
                }
            }
            return Redirect::action('admin\SiswaController@index')->with('flash-store','Semester berhasil ditambahkan.');
        }else{
            return Redirect::action('admin\SiswaController@index')->with('flash-destroy','Kode Salah.');
        }


        // $siswas = Siswa::orderBy('created_at', 'desc')->get();
        
        // foreach ($student as $key => $value) {
        //     $id = $value->id;        
        // }

        // DB::table('siswas')->update(['semester'=> $value->semester + 1]);

    }

    public function import(Request $request) 
	{
		// validasi
		$this->validate($request, [
			'file' => 'required|mimes:csv,xls,xlsx'
        ]);
        
		// menangkap file excel
		$file = $request->file('file');

		// membuat nama file unik
		$nama_file = rand().$file->getClientOriginalName();

		// upload ke folder file_siswa di dalam folder public
		$file->move('file_spp',$nama_file);

		// import data
		Excel::import(new SiswaImport, public_path('/file_spp/'.$nama_file));

		// notifikasi dengan session
		Session::flash('sukses','Data Spp Berhasil Diimport!');

		// alihkan halaman kembali
		return redirect('/siswa/index');
    }
}
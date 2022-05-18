<?php

namespace App\Http\Controllers;

use Auth;
use File;
use App\ContactUs;
use App\Document;
use App\CategoryBuku;
use App\UserProfile;
use App\User;   
use App\Group;  
use App\UserGroup;  
use App\Devision; 
use App\ItemType; 
use File as File1;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


class HomeController extends Controller
{
    public function index()
    {
        return view('admin.admin');
    }

    public function admin()
    {
        return view('admin.admin');
    }

    public function admin1()
    {
        return view('admin.admin');
    }

    public function createEx()
    {
        return view('admin.create');
    }

    public function craetEx2()
    {
        return view('admin.create2');
    }      

    public function viewUser($user_id)
    {
        $user_profile = UserProfile::where('users.id', $user_id)
                                ->leftJoin('users', 'users.id', '=', 'user_profiles.user_id')
                                ->leftJoin('user_groups', 'user_groups.user_id', '=', 'users.id')
                                ->orderBy('user_profiles.created_at', 'desc')
                                ->select(
                                          'users.name',
                                          'users.id',
                                          'user_profiles.address',
                                          'user_profiles.birth_date',
                                          'user_profiles.place_birth',
                                          'user_profiles.phone',
                                          'user_profiles.user_status_id',
                                          'user_profiles.foto',
                                          'user_profiles.bio'
                                  )
                                ->first();

        $documents = Document::where('user_id', $user_id)
                            ->leftJoin('users', 'users.id', '=', 'documents.user_id')
                            ->leftJoin('category_bukus', 'category_bukus.id', '=', 'documents.id_category')
                            ->leftJoin('devisions', 'devisions.id', '=', 'documents.id_devision')
                            ->leftJoin('item_types', 'item_types.id', '=', 'documents.id_type')
                            ->orderBy('documents.created_at', 'asc')
                            ->select(
                                'category_bukus.id',
                                'category_bukus.nama',
                                'users.name',
                                'users.id as user_id',
                                'documents.*',
                                'devisions.devision',
                                'category_bukus.nama as nama_category',
                                'item_types.tipe'
                            )
                            ->paginate(10);
        
        foreach ($documents as $key => $document) {
            $document->abstract_no_tag = strip_tags($document->abstract);
            $document->abstract_no_tag = str_replace('&nbsp;', ' ', $document->abstract_no_tag);
            $document->abstract_no_tag = str_replace('&#39;', ' ', $document->abstract_no_tag);
            $document->abstract_no_tag = trim(preg_replace('/\s\s+/', ' ', $document->abstract_no_tag));
        }

        return view('view-user', compact('user_profile', 'documents'));

    }    

    public function search(Request $request)
    {
        $item_types = ItemType::orderBy('created_at', 'asc')->get();
        $category_bukus = CategoryBuku::orderBy('nama','asc')->get();
        $documents = Document::leftJoin('users', 'users.id', '=', 'documents.user_id')
                                  ->leftJoin('category_bukus','category_bukus.id', '=', 'documents.id_category')
                                  ->leftJoin('item_types', 'item_types.id', '=', 'documents.id_type')
                                  ->orderBy('documents.created_at', 'asc')
                                  ->select(
                                            'category_bukus.id',
                                            'category_bukus.nama as category',
                                            'item_types.tipe',
                                            'users.name',
                                            'users.id as user_id',
                                            'documents.name as nama',
                                            'documents.id',
                                            'documents.path',
                                            'documents.caption',
                                            'documents.image',
                                            'documents.abstract',
                                            'documents.kode_buku',
                                            'documents.tempat_terbit',
                                            'documents.tahun_terbit',
                                            'documents.halaman'
                                          );
          
          $search = $request->get('search_judul');
          // if($request->has('search_judul')){
          //   $search = $request->get('search_judul');
          // }else{
          //   $search = '';
          // }

        if ($request->search_category) {
          $documents = $documents->where('category_bukus.nama', "like", "%".$request->search_category."%");
        }

        if ($request->search_tipe) {
          $documents = $documents->where('item_types.tipe', "like", "%".$request->search_tipe."%");
        }
        
        if ($search) {
          $documents = $documents->where(function ($query) use ($search) {
                                      $query->orWhere("documents.name", "like", "%".$search."%")
                                      ->orWhere("documents.caption", "like", "%".$search."%")
                                      ->orWhere("users.name", "like", "%".$search."%");
                                    });
        }


        $documents = $documents->paginate(10);
        // echo "<pre>";
        // // print_r($search);
        // print_r($documents);
        // echo "<br>";
        // // print_r($request->search_judul);
        // // echo "<br>";
        // // print_r($request->search_category);
        // // echo "<br>";
        // // print_r($request->search_tipe);
        // // echo "<br>";
        // print_r($request->all());
        // // echo "</pre>";
        // exit();


        // echo "<pre>";
        // print_r($documents);
        // echo "</pre>";
        // exit();

        return view('all-book', compact('item_types', 'documents', 'category_bukus'));
    }

    // public function searchKategori(Request $request)
    // {
    //   echo "<pre>";
    //   print_r($request->all());
    //   echo "</pre>";
    //   exit();
    //    $user = Auth::user();
    //    $category_bukus = CategoryBuku::orderBy('nama','asc')->get();
    //    $documents = Document::leftJoin('users', 'users.id', '=', 'documents.user_id')
    //                               ->leftJoin('category_bukus','category_bukus.id', '=', 'documents.id_category')
    //                               ->orderBy('documents.created_at', 'asc')
    //                               ->select(
    //                                         'category_bukus.id',
    //                                         'category_bukus.nama',
    //                                         'users.name',
    //                                         'users.id as user_id',
    //                                         'documents.name as nama',
    //                                         'documents.path',
    //                                         'documents.image',
    //                                         'documents.id',
    //                                         'documents.caption',
    //                                         'documents.description'
    //                                       );
    //     if($request->has('search')){
    //     $search = $request->get('search');
    //     $documents = $documents->Where("category_bukus.nama", "like", "%".$search."%");
    //     }
    //     $documents = $documents->paginate(20);
    //     return view('all-book', compact('documents',  'category_bukus'));                     
    // }

    // public function searchType(Request $request)
    // {
    //   $user = Auth::user();
    //   $item_types = ItemType::orderBy('created_at', 'asc')->get();
    //   $documents = Document::leftJoin('users', 'users.id', '=', 'documents.user_id')
    //                         ->leftJoin('item_types', 'item_types.id', '=', 'documents.id_type')
    //                         ->orderBy('documents.created_at', 'asc')
    //                         ->select(
    //                                   'users.name as nama',
    //                                   'users.id as user_id',
    //                                   'item_types.tipe',
    //                                   'documents.*'
    //                                 );
    //     if($request->has('search')){
    //     $search = $request->get('search');
    //     $documents = $documents->Where("item_types.tipe", "like", "%".$search."%");
    //     }
    //     $documents = $documents->paginate(20);
    //     return view('all-book', compact('documents',  'item_types'));
    //   }

    public function detailBook($slug_name, $slug_caption, $id)
    {
        // $document = Document::where('slug_caption', $slug)->first();
        // $document = Document::where((['slug_caption' => '[A-Za-z-0-9]+[^-0-9.]+']), $slug)->firstOrFail();
      $document = Document::leftJoin('users', 'users.id', '=', 'documents.user_id')
                            ->leftJoin('category_bukus', 'category_bukus.id', '=', 'documents.id_category')
                            ->leftJoin('devisions', 'devisions.id', '=', 'documents.id_devision')
                            ->leftJoin('item_types', 'item_types.id', '=', 'documents.id_type')
                            ->orderBy('documents.created_at', 'asc')
                            ->select(
                                      'users.name as nama',
                                      'users.id as user_id',
                                      'documents.*',
                                      'devisions.devision',
                                      'category_bukus.nama as nama_category',
                                      'item_types.tipe'
                                    )
                            ->paginate(10);

        $documents = Document::where('documents.id', $id)
                            ->leftJoin('users', 'users.id', '=', 'documents.user_id')
                            ->leftJoin('category_bukus', 'category_bukus.id', '=', 'documents.id_category')
                            ->leftJoin('devisions', 'devisions.id', '=', 'documents.id_devision')
                            ->leftJoin('item_types', 'item_types.id', '=', 'documents.id_type')
                            ->select(
                                      'users.name as nama',
                                      'users.id as user_id',
                                      'documents.*',
                                      'devisions.devision',
                                      'category_bukus.nama as nama_category',
                                      'item_types.tipe'
                                    )
                            // ->groupBy('documents.name')
                            ->orderBy('documents.caption', 'dsc')
                            // ->groupBy('users.id')
                            ->first($slug_caption);
        // echo "<pre>";
        // // print_r($id);
        // // print_r($slug_caption);
        // print_r($documents);
        // // print_r($slug_name);
        // echo "</pre>";
        // exit();
      if ($slug_caption != Str::slug($documents->caption)& ($slug_caption != Str::slug($documents->nama)))
        return view('detail-book', compact('document', 'documents'), array('nama' => Str::slug($documents->nama), 'slug' => Str::slug($documents->caption)), 301);
      return view('detail-book', compact('document', 'documents'));
    }

    public function allBook()
    {

      $item_types = ItemType::orderBy('created_at', 'asc')->get();
      $category_bukus = CategoryBuku::orderBy('nama','asc')->get();
      $documents = Document::leftJoin('users', 'users.id', '=', 'documents.user_id')
                              ->leftJoin('category_bukus', 'category_bukus.id', '=', 'documents.id_category')
                              ->leftJoin('devisions', 'devisions.id', '=', 'documents.id_devision')
                              ->leftJoin('item_types', 'item_types.id', '=', 'documents.id_type')
                              ->orderBy('documents.created_at', 'asc')
                              ->select(
                                    'category_bukus.id',
                                    'users.name',
                                    'users.id as user_id',
                                    'documents.id',
                                    'documents.name as nama',
                                    'documents.slug_name',
                                    'documents.path',
                                    'documents.caption',
                                    'documents.slug_caption',
                                    'documents.image',
                                    'documents.abstract',
                                    'documents.kode_buku',
                                    'documents.tempat_terbit',
                                    'documents.tahun_terbit',
                                    'documents.halaman',
                                    'devisions.devision',
                                    'category_bukus.nama as category',
                                    'item_types.tipe'
                                  )
                          ->paginate(20);
      // echo "<pre>";
      // print_r($documents);
      // echo "</pre>";
      // exit();

      foreach ($documents as $key => $document) {
          $document->abstract_no_tag = strip_tags($document->abstract);
          $document->abstract_no_tag = str_replace('&nbsp;', ' ', $document->abstract_no_tag);
          $document->abstract_no_tag = str_replace('&#39;', ' ', $document->abstract_no_tag);
          $document->abstract_no_tag = trim(preg_replace('/\s\s+/', ' ', $document->abstract_no_tag));
      }

      return view('all-book', compact('documents', 'category_bukus', 'item_types'));
    }

    public function userDashboard()
    {
        return view('userdashboard.user-dashboard');
    }
/*    public function adminHome()
    {
        if (Auth::check()) {
            return view('admin.admin-dashboard');
        }else{
            return Redirect('/login');
        }
    }*/



}

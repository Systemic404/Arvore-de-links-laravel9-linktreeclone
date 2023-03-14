<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    function search1(){

       
        // if( isset($_GET['query']) && strlen($_GET['query']) > 1){

        //     $search_text = $_GET['query'];
        //     $countries = DB::table('country')->where('Name','LIKE','%'.$search_text.'%')->paginate(2);
        //     // $countries->appends($request->all());
        //     return view('search',['countries'=>$countries]);
            
        // }else{
        //      return view('search');
        // }
        return view('post');
       
    }

    function find1(Request $request){
            $request->validate([
              'id'=>'required|min:1'
           ]);
  
           $search_text = $request->input('id');
           $countries = DB::table('links')
                      ->where('posttitle','LIKE','%'.$search_text.'%')
                    
                  
                    
                    ->paginate(1);
            return view('post',['countries'=>$countries]);

    }
}

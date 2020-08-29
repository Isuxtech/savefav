<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Site;
use App\Category;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Description;

class DashboardController extends Controller
{

    public function index(Request $request){
        $userID = $request->user()->id;
        $sites = Site::orderBy('created_at', 'desc')->where('user_id',$userID)
            ->with(['category'=>function($query){
                $query->select('cat_id','category_name')->first();
            }])
            ->get();
        return $sites;
    }

    public function publicPost(Request $request){
        $sites = Site::where('access_type','1')
            ->with(['category'=>function($query){
                $query->select('cat_id','category_name')->first();
            }])
//            ->orderBY('created_at', desc)
            ->get();
        return $sites;
    }

    public function show($id){
        $sites = Site::where('id',$id)->with('category')->first();
        return $sites;
    }
    public function showPublic($id){
        $sites = Site::where('id',$id)->with(['category'=>function($query){
            $query->select('cat_id','category_name')->first();
        }])->first();
        return $sites;
    }


    public function createPublic(Request $request){
        $this->validate($request,[
            'title' => ['bail','required','min:5','string'],
            'description' => ['required','min:5','string'],
            'category' => ['required','integer'],
            'url' => ['required'],
            'access_type' => ['required','integer'],
        ]);

        try{
            $newSite = new Site();
            $newSite->user_id = 0;
            $newSite->title = $request->input('title');
            $newSite->category = $request->input('category');
            $newSite->url = $request->input('url');
            $newSite->access_type = $request->input('access_type');
            $newSite->description = $request->input('description');
            $newSite->save();

            return response()->json('New link added successfully');
        }catch( \Throwable $ex){
            return response()->json('unable to add link', 400);
        }
    }

    public function create(Request $request){

        $this->validate($request,[
            'title' => ['bail','required','min:5','string'],
            'description' => ['required','min:5','string'],
            'category' => ['required','integer'],
            'url' => ['required'],
            'access_type' => ['required','integer'],
        ]);

        try{
            $newSite = new Site();
            $newSite->user_id = $request->user()->id;
            $newSite->title = $request->input('title');
            $newSite->category = $request->input('category');
            $newSite->url = $request->input('url');
            $newSite->access_type = $request->input('access_type');
            $newSite->description = $request->input('description');
            $newSite->save();

            return response()->json('New link added successfully');
        }catch( \Throwable $ex){
            return response()->json('unable to add link');
        }

    }
    /***
     * @param $id
     * @return int
     * recall that the App\Site model has a softDelete Attribute so it will not be deleted from the database
     */
    public function destroy($id){
        $result = Site::destroy($id);
        if(!$result){
            return response()->json(['Problem deleting link'],422);
        }
        return response()->json(['Link deleted successfully'],200);
    }
}

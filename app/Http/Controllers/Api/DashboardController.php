<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Site;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\DocBlock\Description;

class DashboardController extends Controller
{

    public function index(Request $request){
        $userID = $request->user()->id;
        $sites = Site::orderBy('created_at', 'desc')->where('user_id',$userID)
            ->with('user')
            ->with(['category'=>function($query){
                $query->select('cat_id','category_name');
            }])
            ->paginate(20);
        return response(['savedlink'=>$sites,'publisher'=>$userID]) ;
    }

    /***
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     *
     * the publisher returns a null because the user is never logged in in this route
     */
    public function publicPost(Request $request){
        $sites = Site::orderBY('created_at', 'desc')
            ->where('access_type','1')
            ->with(['category'=>function($query){
                $query->select('cat_id','category_name');
            }])
            ->paginate(20);
        return response(['savedlink'=>$sites,'publisher'=>null]);
    }



    public function searchIndex(Request $request){

       $this->validate($request,[
            'searchquery'=> ['required','min:2']
        ]);
//        return $request;

        $resultString = $request->input('searchquery');// the search string send from the browser
        $userID = $request->user()->id ?? null;
        $sites = null;
        if($userID){
            $sites = Site::Search($resultString)
                ->where(function($query) {
                    $query->where('access_type', '1') // get all public links
                    ->orWhere(function ($query) {
                        $query->where('access_type', '0')->where('user_id', 2); // get a private links that has a user_id of the userID
                    });
                    })
                    ->with(['category'=>function($query){
                        $query->select('cat_id','category_name');
                    }])
                ->paginate(20);

        }else{
                $sites = Site::Search($resultString)
                    ->where('access_type','1')
                    ->with(['category'=>function($query){
                        $query->select('cat_id','category_name');
                    }])
                    ->paginate(20);
        }
        return response(['savedlink'=>$sites,'publisher'=>$userID ?? null,'postsCount'=>$sites->count()]) ;
    }


    public function show($id){
        $sites = Site::where('id',$id)
            ->with('category')
            ->first();
        return $sites;
    }
    public function showPublic($id){
        $sites = Site::where('id',$id)
            ->with(['category'=>function($query){
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

        $linkUrl = !Str::of($request->input('url'))
            ->startsWith('http') ? Str::of($request->input('url'))->prepend('https://') : $request->input('url') ;
        try{
            $newSite = new Site();
            $newSite->user_id = 0;
            $newSite->title = $request->input('title');
            $newSite->category = $request->input('category');
            $newSite->url = $linkUrl;
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

        $linkUrl = Str::of($request->input('url'))
            ->startsWith('http') ? Str::of($request->input('url'))->prepend('https://') : $request->input('url') ;
        try{
            $newSite = new Site();
            $newSite->user_id = $request->user()->id;
            $newSite->title = $request->input('title');
            $newSite->category = $request->input('category');
            $newSite->url = $linkUrl;
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

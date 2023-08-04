<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class usersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users= User::select('name','description','type')->paginate($request->recordsPerPage,$request->page);
        return response()->json(["success"=>true,"data"=>$users,"message"=>'',"status-code"=>200,"error-code"=>0]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'name' => 'required|max:50',
            'description'=> 'required|max:250',
            'type'=> 'required|in:1,2,3',
            'file'=> 'required|max:50000|mimes:jpg,png,jpeg'
        ],[
            'type.in'=> "Given type must be 1, 2, 3"
        ]);
        if ($validator->fails()) {
            return response()->json(["success"=>false,"data"=>[],"message"=>$validator->errors()->first(),"status-code"=>200,"error-code"=>0]);
        }

        $file= $request->file;
        $fileName = time() . '.png';
        $path= storage_path('app/public/private/');

        $baseEncode = str_replace('data:image/png;base64,', '', $file);
        $baseEncode = str_replace('data:image/jpg;base64,', '', $baseEncode);
        $baseEncode = str_replace('data:image/jpeg;base64,', '', $baseEncode);
        if(!is_dir($path)){
            mkdir($path, 0755,true);
        }
        $uploadedImage = file_put_contents($path.'/'.$fileName, base64_decode($baseEncode));

        // 
        $insertedUserId= User::insertGetId([
            'name'=>$request->name,
            'description'=> $request->description,
            'type'=> $request->type,
            'file'=> $fileName
        ]);

        $user= User::select('name','description','type')->where('id',$insertedUserId)->first();
        return response()->json(["success"=>true,"data"=>$user,"message"=>'',"status-code"=>200,"error-code"=>0]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user= User::select('name','description','type','file')->where('id',$id)->first();
        return response()->json(["success"=>true,"data"=>$user,"message"=>'',"status-code"=>200,"error-code"=>0]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

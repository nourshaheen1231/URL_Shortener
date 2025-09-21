<?php

namespace App\Http\Controllers;

use App\Models\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class URLController extends Controller
{
    public function addURL(Request $request){
        $validator = Validator::make($request->all(), [
            'long' => 'required|url'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'error' => 'The provided URL is not valid. Please make sure it is a valid URL.'
            ], 400); 
        }
    
        $count = 0;
        $short = null;
        $x = 0;
        $y = 0;
    
        for ($i = 0; $i < strlen($request->long); $i++) {
            if ($request->long[$i] == '/') {
                $count++;
                $y = $i;
            }
            if ($count == 1) {
                $x = $i;
            }
        }
    
        if ($count == 2) {
            $short = $request->long;
        } else {
            $array = [];
            for ($i = 0; $i < strlen($request->long); $i++) {
                if ($i <= $x + 1 || $i > $y) $array[$i] = $request->long[$i];
            }
            $filteredArray = array_filter($array);
            $short = implode('', $filteredArray) . '.short';
        }
    
        $url = URL::create([
            'long' => $request->long,
            'short' => $short,
        ]);
    
        return response()->json($url, 200);
    }
    
    /////////////
    public function showURLs(){
        $urls = URL::query()->get();
        $response = [];
        foreach($urls as $url){
            $response[] =[
                'id'=>$url->id,
                'long' => $url->long,
                'short' =>$url->short,
            ];
        }
        return response()->json($response,200);
    }
    /////////////
    public function redirectToLong(Request $request){
        $validator = Validator::make($request->all(), [
            'short' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'error' => 'Short URL is required!'
            ], 400); 
        }
    
        $url = URL::where('short', $request->short)->first();
    
        if ($url) {
            $longUrl = preg_match('/^https?:\/\//', $url->long) ? $url->long : 'http://' . $url->long;
            return redirect()->away($longUrl);
        } else {
            return response()->json([
                'error' => 'There is no such url!'
            ], 404); 
        }
    }
    
    

}

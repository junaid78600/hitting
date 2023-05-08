<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\QuickEntry;
use App\Models\QuickEntryAB;
use App\Models\Pitch;
use App\Models\UserVideo;


class ApiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['me']]);
        $this->userId = Auth::guard('jwt')->user()->id;
    }

    public function me()
    {
        return response()->json($this->userId);
    }
    
    
    public function getUserDetail () 
    {
            
        return response()->json([
            'status'=>200,
            'data'=>Auth::guard('jwt')->user(),
            'videoData'=>UserVideo::where('userId', $this->userId)->get(),
            'message'=>'Fetch Successfully',
        ]);
    }
    
    public function updateProfile (Request $request) 
    {
        $user_exit = User::where('id',$this->userId)->first();
        $file_name = '';
        if($user_exit){
            
            if($request->hasFile('image')) {
    
                $file = $request->file('image');
    
                $name = $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();
    
                $image['filePath'] = $name;
    
                $file_name = time().mt_rand(1,99999).'.'.$file->getClientOriginalExtension();;
    
                $file->move(public_path().'/userProfiles/', $file_name);
                
                $url =$user_exit->image;
                
                if($url!=''){
                    
                    $baseURL = url('/');
                
                    $cleanedURL = str_replace($baseURL, '', $url);
                    
                    $cleanedURL = ltrim($cleanedURL, '/');
                    
                    unlink($cleanedURL);
                    
                     $user_exit->image='';
                     $user_exit->save();
                    
                }

            }
         
                $user_exit->name = ($request->name!=''?$request->name:$user_exit->name);
                $user_exit->phone = ($request->phone!=''?$request->phone:$user_exit->phone);
                $user_exit->email = ($request->email!=''?$request->email:$user_exit->email);
                $user_exit->password = $user_exit->password;
                $user_exit->bio = ($request->bio!=''?$request->bio:$user_exit->bio);
                $user_exit->image = ($file_name!=''?$file_name:$user_exit->image);
                $user_exit->save();
    
            return response()->json([
                'status'=>200,
                'message'=>'Updated Successfully',
            ]);
        }
        else{
             return response()->json([
                'status'=>404,
                'message'=>'User not Found',
            ]);
        }
    }
    
    public function updatePassword (Request $request) 
    {
       
        $user_exit = User::where('id',$this->userId)->first();
        
        if($user_exit){
         
                $user_exit->password = Hash::make($request->password);
                $user_exit->save();
    
            return response()->json([
                'status'=>200,
                'message'=>'Password Updated Successfully',
            ]);
        }
        else{
             return response()->json([
                'status'=>404,
                'message'=>'User not Found',
            ]);
        }
    }
    
    public function saveQuickEntry (Request $request) 
    {
       
            $validator = Validator::make($request->all(), [
                'description' => 'required',
                'commitment' => 'required',
                'tension' => 'required',
                'forwards' => 'required',
                'pattern' => 'required',
                'spacing' => 'required',
                'recoil' => 'required',
                'vibes' => 'required',
                'head_height' => 'required',
            ]);
       
            if($validator->fails()){
                
                $response = [
                    'success' => false,
                    'message' => 'Validation Error.',
                ];
        
                $code = '404';
                
                $response['data'] = $validator->errors();
                
                return response()->json($response, $code);

            }
            else{

                    QuickEntry::create([
                        'userID' => $this->userId,
                        'description' => $request->description,
                        'commitment' => $request->commitment,
                        'tension' => $request->tension,
                        'forwards' => $request->forwards,
                        'pattern' => $request->pattern,
                        'spacing' => $request->spacing,
                        'recoil' => $request->recoil,
                        'vibes' => $request->vibes,
                        'head_height' => $request->head_height,
                        'date' => date("d-M-Y"),
                        'time' => date("h:i:s a"),
                    ]);

                    return response()->json([
                        'status'=>200,
                        'message'=>'Saved Successfully',
                    ]);
            }
    }

    public function getQuickEntry (Request $request) 
    {
        
        if(isset($request->date)){
           
            $data = QuickEntry::where('userID',$this->userId)->where('date', '=', $request->date)->paginate(15);
        }
        
        else{
            
           $data = QuickEntry::where('userID',$this->userId)->paginate(15);
        }
        
        if(count($data)>0){

            return response()->json([
                'status'=>200,
                'data'=>$data,
                'message'=>'Fetch Successfully',
            ]);
        }
        else{
                return response()->json([
                    'status'=>404,
                    'message'=>'Data not Found',
                ]);
        }
    }
    
    public function deleteQuickEntry (Request $request) 
    {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
       
            if($validator->fails()){
                
                $response = [
                    'success' => false,
                    'message' => 'Validation Error.',
                ];
        
                $code = '404';
                
                $response['data'] = $validator->errors();
                
                return response()->json($response, $code);

            }
            else{
                    $data = QuickEntry::where('id',$request->id)->first();
            
                    if($data){
                        
                        QuickEntry::where('id',$request->id)->delete();
                        
                        return response()->json([
                            'status'=>200,
                            'message'=>'Delete Successfully',
                        ]);
                    }
                    else{
                            return response()->json([
                                'status'=>404,
                                'message'=>'Data not Found with this ID',
                            ]);
                    }
                }
    }

    public function getPitchList (Request $request) 
    {
            
            $data = Pitch::where('status','active')->get();

            if(count($data)>0){
                
                return response()->json([
                    'status'=>200,
                    'data'=>$data,
                    'message'=>'Fetch Successfully',
                ]);
            }
            else{
                    return response()->json([
                        'status'=>404,
                        'message'=>'Data not Found',
                    ]);
            }
    }

    public function saveQuickEntryAB (Request $request) 
    {
       
            $validator = Validator::make($request->all(), [
                'pitchId' => 'required|integer',
                'abNumber' => 'required|integer',
                'primaryPitch' => 'required',
                'secondaryPitch' => 'required',
                'result' => 'required',
                'whyWasThisSpot' => 'required',
                'image' => 'required|file',
            ]);
       
            if($validator->fails()){
                
                $response = [
                    'success' => false,
                    'message' => 'Validation Error.',
                ];
        
                $code = '404';
                
                $response['data'] = $validator->errors();
                
                return response()->json($response, $code);

            }
            else{

                    if($request->hasFile('image')) {

                        $file = $request->file('image');

                        $name = $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();

                        $image['filePath'] = $name;

                        $file_name = time().mt_rand(1,99999).'.'.$file->getClientOriginalExtension();;

                        $file->move(public_path().'/images/', $file_name);

                    }

                    QuickEntryAB::create([
                        'abNumber' => $request->abNumber,
                        'userId' => $this->userId,
                        'pitchId' => $request->pitchId,
                        'primaryPitch' => $request->primaryPitch,
                        'secondaryPitch' => $request->secondaryPitch,
                        'result' => $request->result,
                        'whyWasThisSpot' => $request->whyWasThisSpot,
                        'image' => $file_name,
                        'date' => date("d-M-Y"),
                        'time' => date("h:i:s a"),
                    ]);

                    return response()->json([
                        'status'=>200,
                        'message'=>'Saved Successfully',
                    ]);
            }
    }

    public function quickEntryAB(Request $request) 
    {
        
        $validator = Validator::make($request->all(), [
            'pitchId' => 'required|integer',
        ]);
   
        if($validator->fails()){
            
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
            ];
    
            $code = '404';
            
            $response['data'] = $validator->errors();
            
            return response()->json($response, $code);

        }
        else{   
                if(isset($request->date)){
                    
                    $data = QuickEntryAB::where('userId',$this->userId)->where('pitchId',$request->pitchId)->where('date',$request->date)->paginate(10);
                }
                else{
                    
                    $data = QuickEntryAB::where('userId',$this->userId)->where('pitchId',$request->pitchId)->paginate(10);
                }

                   
                
                    if(count($data) > 0){
                    
                        return response()->json([
                            'status' => 200,
                            'data' => $data,
                            'message' => 'Fetch Successfully',
                           
                        ]);

                    } else {
                        return response()->json([
                            'status' => 404,
                            'message' => 'No records found',
                        ]);
                    }
            }
    }
    
    public function saveVideo(Request $request) 
    {
            $validator = Validator::make($request->all(), [
                "image"=>"required | mimes:mp4,x-flv,x-mpegURL,MP2T,3gpp,quicktime,x-msvideo,x-ms-wmv |max:9000000",
                'title' => 'required',
            ]);
       
            if($validator->fails()){
                
                    $response = [
                        'success' => false,
                        'message' => 'Validation Error.',
                    ];
            
                    $code = '404';
                    
                    $response['data'] = $validator->errors();
                    
                    return response()->json($response, $code);

            }
            else{

                    if($request->hasFile('image')) {

                        $file = $request->file('image');

                        $name = $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();

                        $image['filePath'] = $name;

                        $file_name = time().mt_rand(1,99999).'.'.$file->getClientOriginalExtension();;

                        $file->move(public_path().'/userVideo/', $file_name);

                    }

                    UserVideo::create([
                        'userId' => $this->userId,
                        'image' => $file_name,
                        'title' => $request->title,
                        'date' => date("d-M-Y"),
                        'time' => date("h:i:s a"),
                    ]);

                    return response()->json([
                        'status'=>200,
                        'message'=>'Saved Successfully',
                    ]);
            }
    }

    public function deleteVideo(Request $request) 
    {
            $validator = Validator::make($request->all(), [
                'videoId' => 'required',
            ]);
       
            if($validator->fails()){
                
                    $response = [
                        'success' => false,
                        'message' => 'Validation Error.',
                    ];
            
                    $code = '404';
                    
                    $response['data'] = $validator->errors();
                    
                    return response()->json($response, $code);

            }
            else{

                    $video = UserVideo::find($request->videoId);
                    
                    $url =$video->image;

                    $baseURL = url('/');
                    
                    $cleanedURL = str_replace($baseURL, '', $url);
                    
                    $cleanedURL = ltrim($cleanedURL, '/');
                    
                
                    if($video){
                        
                        unlink($cleanedURL);
                        $video->delete();
                        return response()->json([
                            'status'=>200,
                            'message'=>'Deleted Successfully',
                        ]);

                    }

                    else{
                        return response()->json([
                            'status'=>400,
                            'message'=>'Vido not found',
                        ]);
                    }
                    
            }
    }


}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileUpdateResource;
use App\Models\RiderBankInformation;
use App\Models\User;
use App\Services\Files\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class ProfileUpdateController extends Controller
{
    public $fileName = null;
    public function __construct(public FileService $fileService)
    {

    }
    public function UserProfileShow(){
         $user = Auth::user();
        $data =  new profileUpdateResource($user);
        return sendResponse(true, 'User profile fetched successfully.', $data, 200);
    }
    public function userProfileUpdate(Request $request){
//        $lat = 23.755613;
//        $lng = 90.368591;
//
//        $response = Http::withHeaders([
//            'User-Agent' => 'YourAppName/1.0 (your@email.com)'
//        ])->get("https://nominatim.openstreetmap.org/reverse", [
//            'format' => 'json',
//            'lat' => $lat,
//            'lon' => $lng,
//            'zoom' => 18,
//            'addressdetails' => 1,
//        ]);
//
//        $data = $response->json();
//       return $address = $data['display_name'] ?? null;
        request()->validate([
            'name' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'phone_number' => 'required',
        ]);
        try {
             $user = Auth::user();
             // assign existing profile image with slice path
            $this->fileName = $this->fileService->sliceFileUrl($user?->profile_image);
            if($request->hasFile('profile_image')){
                $user->profile_image = $this->fileName;
                if(!empty($this->fileName)){
                   $this->fileName = $this->fileService->updateFile($request->profile_image, $this->fileName,  'user');
                }else{
                    $this->fileName = $this->fileService->uploadFile($request->profile_image, 'user');
                }
            }
            $user->update([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'profile_image' => $this->fileName,
                'dod' => $request->dod,
                'gender' => $request->gender,
                'status' => User::$status['active'],
            ]);

            // if user want to an account open as a rider
            if($request->user_type === 'rider'){
                $pathName = [];
                if($request->hasFile('document')){
                    foreach($request->document as $file){
                        $pathName[] = $this->fileService->uploadFile($file, "riderDocument/{$request->document_type}");
                    }
                  //  $pathName = $this->fileService->uploadMultipleFiles($request->document, "riderDocument/{$request->document_type}");
                }
                $user->userRiders()->create([
                    'document_type' => $request->document_type,
                    'document_number' => $request->document_number,
                    'document' => json_encode($pathName)
                ]);
                // assign rider role if not assign role
                if (!$user->hasRole('rider')) {
                    $user->assignRole('rider');
                }
                // rider bank information saving..
                if (!empty($request->is_default_payment)) {
                    foreach ($request->is_default_payment as $key => $item) {
                        $user->riderBankInformations()->updateOrCreate(
                            [
                                'id' => $request->id[$key], // Unique condition
                                'user_id' => $user->id
                            ],
                            [
                                'name' => $request->name[$key],
                                'account_number' => $request->card_number[$key],
                                'cvc_code' => $request->cvc_code[$key],
                                'expire_date' => $request->expire_date[$key],
                                'type' => RiderBankInformation::$type[$request->type[$key]],
                                'is_default_payment' => $request->is_default_payment[$key] == 'true' ? true : false,
                            ]
                        );
                    }
                }
            }
            // using load functions for relationship model data load
            $user->load([
                'riderBankInformations',
                'userRiders'
            ]);

            $data =  new profileUpdateResource($user);
            return sendResponse(true, 'User profile updated successfully.', $data, 200);
        }catch (\Exception $exception){
            return sendResponse(false, $exception->getMessage(), null, 500);
        }

    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileUpdateResource;
use App\Http\Resources\RiderProfileResource;
use App\Http\Resources\UserProfileResource;
use App\Models\RiderBankInformation;
use App\Models\User;
use App\Models\UserRider;
use App\Services\Files\FileService;
use Illuminate\Http\JsonResponse;
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

        $request->validate([
            'name' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'phone_number' => 'required',
        ]);
        try {
             $user = Auth::user();
             // assign existing profile image with slice path

            $this->fileName = $user?->profile_image ? $this->fileService->sliceFileUrl($user?->profile_image) : null;
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
                'dob' => $request->dob,
                'gender' => $request->gender,
                'status' => User::$status['active'],
            ]);


            $data =  new UserProfileResource($user);
            return sendResponse(true, 'User profile updated successfully.', $data, 200);
        }catch (\Exception $exception){
            return sendResponse(false, $exception->getMessage(), null, 500);
        }

    }
    public function UserRiderProfileShow()
    {
        try {
            $user = Auth::user();
            if (!$user->hasRole('rider')) {
                return sendResponse(false, 'You are not authorized for rider', null, 403);
            }
            // load relationship data
            $user->load([
                'riderBankInformations',
                'userRiders'
            ]);
            $data = new RiderProfileResource($user);
            return sendResponse(true, 'User profile fetched successfully.', $data, 200);
        }catch (\Exception $exception){
            return sendResponse(false, $exception->getMessage(), null, 500);
        }

    }
    public function riderProfileUpdate(Request $request)
    {

        try {
            $user = Auth::user();
            $pathName = [];
            if($request->hasFile('document')){
                foreach($request->document as $file){
                    $pathName[] = $this->fileService->uploadFile($file, "riderDocument/{$request->document_type}");
                }
                //  $pathName = $this->fileService->uploadMultipleFiles($request->document, "riderDocument/{$request->document_type}");
            }
            // checking existing or not
           $userRider = UserRider::where('user_id', $user->id)->where('document_type', $request->document_type)->first();
            if(!$userRider){
                $user->userRiders()->create([
                    'document_type' => $request->document_type,
                    'document_number' => $request->document_number,
                    'document' => json_encode($pathName),
                ]);
            }else{
                $user->userRiders()->update([
                    'document_type' => $request->document_type,
                    'document_number' => $request->document_number,
                    'document' => json_encode($pathName),
                ]);
            }
            // assign rider role if not assign role
            if (!$user->hasRole('rider')) {
                $user->assignRole('rider');
            }
            // rider bank information saving..
//            if (!empty($request->is_default_payment)) {
//                foreach ($request->is_default_payment as $key => $item) {
//                    $user->riderBankInformations()->updateOrCreate(
//                        [
//                            'user_id' => $user->id,
//                            'account_number' => $request->card_number[$key], // Uniquely identify by account number
//                        ],
//                        [
//                            'name' => $request->name[$key],
//                            'account_number' => $request->card_number[$key],
//                            'cvc_code' => $request->cvc_code[$key],
//                            'expiry_date' => $request->expire_date[$key],
//                            'type' => RiderBankInformation::$type['card'],
//                            'is_default_payment' => $item === 'true',
//                        ]
//                    );
//                }
//            }

            // load relationship data
            $user->load([
                'userRiders'
            ]);
            $data = new RiderProfileResource($user);
            return sendResponse(true, 'Rider profile updated successfully.', $data, 200);
        }catch (\Exception $ex){
            return sendResponse(false, $ex->getMessage(), null, 500);
        }

    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileUpdateResource;
use App\Models\User;
use App\Services\Files\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileUpdateController extends Controller
{
    public function __construct(public FileService $fileService)
    {

    }
    public function UserProfileShow(){
         $user = Auth::user();
        $data =  new profileUpdateResource($user);
        return sendResponse(true, 'User profile fetched successfully.', $data, 200);
    }
    public function userProfileUpdate(Request $request){
        request()->validate([
            'name' => 'required',
            'gender' => 'required',
        ]);
        try {
            $user = Auth::user();
            $fileName = $this->fileService->sliceFileUrl($user?->profile_image);
            if($request->hasFile('profile_image')){
                $fileName = $this->fileService->uploadFile($request->profile_image, 'user');
            }
            $user->update([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'profile_image' => $fileName,
                'dod' => $request->dod,
                'gender' => $request->gender,
                'status' => User::$status['active'],
            ]);
            if($request->user_type === User::$userType['rider']){
                $riderDocument = [];
                if($request->hasFile('nid_file')){
                    $pathName = $this->fileService->uploadFile($request->nid_file, 'riderDocument');
                    $riderDocument[] = ['document' => $pathName, 'document_type' => 'nid'];
                }
                if($request->hasFile('passport_file')){
                    $pathName = $this->fileService->uploadFile($request->passport_file, 'riderDocument');
                    $riderDocument[] = ['document' => $pathName, 'document_type' => 'passport'];
                }
                if($request->hasFile('driving_license_file')){
                    $pathName = $this->fileService->uploadFile($request->driving_license_file, 'riderDocument');
                    $riderDocument[] = ['document' => $pathName, 'document_type' => 'driving_license'];
                }

                $user->userRiders()->createMany($riderDocument);
            }
            $data =  new profileUpdateResource($user);
            return sendResponse(true, 'User profile updated successfully.', $data, 200);
        }catch (\Exception $exception){
            return sendResponse(false, $exception->getMessage(), null, 500);
        }

    }
}

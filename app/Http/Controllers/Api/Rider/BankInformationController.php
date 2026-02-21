<?php

namespace App\Http\Controllers\Api\Rider;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\BankInformationRequest;
use App\Http\Resources\BankInformationResource;
use App\Models\BankInformation;
use App\Services\Files\FileService;
use Illuminate\Http\Request;

class BankInformationController extends Controller
{


    // Inject the service via constructor
    public function __construct(public FileService $fileService)
    {
    }
    public function index(Request $request){
        try {
            $data = BankInformation::with('user')->where('user_id', auth()->id())->first();
            // if not their bank information
            if(!$data){
                return sendResponse(true, 'Success get data!',  [
                    'account_holer_name' =>null,
                    'account_number' => null,
                    'institution_number' => null,
                    'transit_number' => null,
                    'bank_document' => null,
                    'status' => null
                ], 200);
            }
            $bankInformation = new BankInformationResource($data);
            return sendResponse(true, 'Success get data!', $bankInformation, 200);

        }catch (\Exception $exception){

        }
    }
    public function store(BankInformationRequest $request)
    {
       $checkExistingDoc = BankInformation::where('user_id', auth()->id())->first();
       if($checkExistingDoc){
           throw new CustomException('You have already submitted a bank information!', 409);
       }
       // return $request->all();
       $data = $request->validated();
        $user = auth()->user();
        try{
            // Use your service to upload
            if ($request->hasFile('bank_document')) {
                $data['bank_document'] = $this->fileService->uploadFile(
                    $request->file('bank_document'),
                    'bank_documents'
                );
            }
            $data['user_id'] = $user->id;

            BankInformation::create($data);

            return sendResponse(true, 'Bank Information created successfully!', 200);
        }catch (\Exception $exception){
            return sendResponse(false, $exception->getMessage(), 400);
        }

    }
    public function show(Request $request){

    }
    public function update(BankInformationRequest $request, $id)
    {
        $user = auth()->user();

        // Find the record
        $bankInfo = BankInformation::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$bankInfo) {
            throw new CustomException('Bank information not found!', 404);
        }

        $data = $request->validated();

        try {
            // Handle file update
            if ($request->hasFile('bank_document')) {

                // Delete old file if exists
                if ($bankInfo->bank_document) {
                    $this->fileService->deleteFile($bankInfo->bank_document);
                }

                // Upload new file
                $data['bank_document'] = $this->fileService->uploadFile(
                    $request->file('bank_document'),
                    'bank_documents'
                );
            }

            // Update record
            $bankInfo->update($data);

            return sendResponse(true, 'Bank Information updated successfully!', 200);

        } catch (\Exception $exception) {
            return sendResponse(false, $exception->getMessage(), 400);
        }
    }
}

<?php

namespace App\Http\Controllers\Api\Rider;

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
            $data = BankInformation::with('user')->firstOrFail();
            $bankInformation = new BankInformationResource($data);
            return sendResponse(true, 'Success get data!', $bankInformation, 200);

        }catch (\Exception $exception){

        }
    }
    public function store(BankInformationRequest $request)
    {
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
    public function update(Request $request){

    }
}

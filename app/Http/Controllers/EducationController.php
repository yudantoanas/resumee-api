<?php

namespace App\Http\Controllers;

use App\Models\Education;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EducationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'per_page' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError("Invalid Params", $validator->errors());
        }

        // get auth user
        $user = auth()->user();

        $data = $user->educations()->paginate($request->get("per_page", 20));

        return $this->sendResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        //
        $validator = $this->validateForm($request);

        if ($validator->fails()) {
            return $this->sendError("Failed to Create", $validator->errors());
        }

        // get auth user
        $user = auth()->user();

        $startDate = Carbon::createFromFormat("Y-m-d", $request->start_date);
        $endDate = Carbon::createFromFormat("Y-m-d", $request->end_date);

        if ($startDate->gt($endDate)) {
            return $this->sendError("Invalid Date", "start_date cannot be greater than end_date");
        }

        $data = Education::create([
            'user_id' => $user->id,
            'institution_name' => $request->institution_name,
            'degree' => $request->degree,
            'field_of_study' => $request->field_of_study,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return $this->sendResponse($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateForm(Request $request)
    {
        return Validator::make($request->all(), [
            'institution_name' => 'required',
            'degree' => 'required',
            'field_of_study' => 'required',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
        ]);
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return bool|JsonResponse
     */
    private function validateStartAndEndDate($startDate, $endDate)
    {


        return true;
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function show(int $id)
    {
        //
        // get auth user
        $user = auth()->user();

        $data = $user->educations()->where('id', $id)->first();

        if (!$data) {
            return $this->sendError("Not Found", "The data you're looking for is not found");
        }

        return $this->sendResponse($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        //
        $validator = $this->validateForm($request);

        if ($validator->fails()) {
            return $this->sendError("Failed to Create", $validator->errors());
        }

        // get auth user
        $user = auth()->user();

        // get education data based on its id
        $data = $user->educations()->where('id', $request->id)->first();

        if (!$data) {
            return $this->sendError("Not Found", "The data you're looking for is not found");
        }

        $startDate = Carbon::createFromFormat("Y-m-d", $request->start_date);
        $endDate = Carbon::createFromFormat("Y-m-d", $request->end_date);

        if ($startDate->gt($endDate)) {
            return $this->sendError("Invalid Date", "start_date cannot be greater than end_date");
        }

        $data->institution_name = $request->institution_name;
        $data->degree = $request->degree;
        $data->field_of_study = $request->field_of_study;
        $data->start_date = $startDate;
        $data->end_date = $endDate;

        if ($data->isDirty()) {
            // save when there's a change in on of the attributes
            $data->save();
        }

        return $this->sendResponse($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError("Invalid Form", $validator->errors());
        }

        // get auth user
        $user = auth()->user();

        // get education data based on its id
        $data = $user->educations()->where('id', $request->id)->first();

        $data->delete();

        return $this->sendResponse(null, "Successfully Deleted!");
    }
}

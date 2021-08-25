<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExperienceController extends Controller
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

        $data = $user->experiences()->paginate($request->get("per_page", 20));

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
        $endDate = null;
        //
        $validator = $this->validateForm($request);

        if ($validator->fails()) {
            return $this->sendError("Failed to Create", $validator->errors());
        }

        // get auth user
        $user = auth()->user();

        $startDate = Carbon::createFromFormat("Y-m-d", $request->start_date);

        if ($request->end_date) {
            $endDate = Carbon::createFromFormat("Y-m-d", $request->end_date);

            if ($startDate->gt($endDate)) {
                return $this->sendError("Invalid Date", "start_date cannot be greater than end_date");
            }
        }

        $data = Experience::firstOrCreate([
            'user_id' => $user->id,
            'position' => $request->position,
            'organization_name' => $request->organization_name,
            'location' => $request->location,
            'vacancy' => $request->vacancy,
            'description' => $request->description,
            'duration' => $request->duration,
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
            'position' => 'required',
            'organization_name' => 'required',
            'location' => 'required',
            'vacancy' => 'required',
            'description' => 'required',
            'duration' => 'required|integer',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d',
        ]);
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

        $data = $user->experiences()->where('id', $id)->first();

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
        $endDate = null;
        //
        $validator = $this->validateForm($request);

        if ($validator->fails()) {
            return $this->sendError("Failed to Create", $validator->errors());
        }

        // get auth user
        $user = auth()->user();

        // get experience data based on its id
        $data = $user->experiences()->where('id', $request->id)->first();

        if (!$data) {
            return $this->sendError("Not Found", "The data you're looking for is not found");
        }

        $startDate = Carbon::createFromFormat("Y-m-d", $request->start_date);

        if ($request->end_date) {
            $endDate = Carbon::createFromFormat("Y-m-d", $request->end_date);

            if ($startDate->gt($endDate)) {
                return $this->sendError("Invalid Date", "start_date cannot be greater than end_date");
            }
        }

        $data->position = $request->position;
        $data->organization_name = $request->organization_name;
        $data->location = $request->location;
        $data->vacancy = $request->vacancy;
        $data->description = $request->description;
        $data->duration = $request->duration;
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

        // get experience data based on its id
        $data = $user->experiences()->where('id', $request->id)->first();

        if (!$data) {
            return $this->sendError("Not Found", "The data you're looking for is not found");
        }

        $data->delete();

        return $this->sendResponse(null, "Successfully Deleted!");
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Voucher\StoreVoucherRequest;
use App\Http\Requests\Voucher\UpdateVoucherRequest;
use App\Http\Resources\VoucherResource;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $vouchers = Voucher::get();
            return VoucherResource::collection($vouchers);
        } catch (\Exception $ex) {
            return response()->internalServerError('Something went wrong', $ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVoucherRequest $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();
            $voucher = Voucher::create($validatedData);
            DB::commit();
            return response()->created('Voucher created successfully', new VoucherResource($voucher));
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->internalServerError('Something went wrong', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $voucher = Voucher::find($id);
            if (!$voucher)
                return response()->notFound('Voucher not found');

            return new VoucherResource($voucher);
        } catch (\Exception $ex) {
            return response()->internalServerError('Something went wrong', $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVoucherRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $voucher = Voucher::find($id);
            if (!$voucher)
                return response()->notFound('Voucher not found');
            $validatedData = $request->validated();
            $voucher->update($validatedData);
            DB::commit();
            return response()->success('Voucher updated successfully', new VoucherResource($voucher));
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->internalServerError('Something went wrong', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $voucher = Voucher::find($id);
            if (!$voucher)
                return response()->notFound('Voucher not found');
            $voucher->delete();
            return response()->success('Voucher deleted successfully');
        } catch (\Exception $ex) {
            return response()->internalServerError('Something went wrong', $ex->getMessage());
        }
    }
    public function claim(Request $request)
    {
        $now = Carbon::now(new \DateTimeZone('Asia/Jakarta'));
        try {
            $voucher = Voucher::where('code', $request->code)->first();
            if (!$voucher)
                return response()->notFound('Voucher not found');
            if ($now->greaterThan($voucher->expiration_time))
                return response()->badRequest('Voucher expired');
            if ($now->lessThan($voucher->activation_time))
                return response()->badRequest('Voucher not yet active');
            if ($voucher->is_active)
                return response()->success('Voucher used successfully', new VoucherResource($voucher));
        } catch (\Exception $ex) {
            return response()->internalServerError('Something went wrong', $ex->getMessage());
        }
    }
}

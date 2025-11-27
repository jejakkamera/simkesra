<?php

namespace App\Http\Controllers\sekolah;

use App\Http\Controllers\Controller;
use App\Models\StudentBillingMaster;
use App\Models\StudentBillingDetail;
use App\Models\BillingMaster;
use App\Models\BillingDetail;
use App\Models\SchoolPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Session;
use Throwable;

class StudentBilling extends Controller
{

    public function generateStudentBillingDetails()
    {
        // Get the active period
        $this->periode = SchoolPeriod::where('is_active', 1)->first();
        if (!$this->periode) {
            session()->flash('error', 'Periode akademik not set.');
            redirect()->route(session('active_role') . '.TagihanMasterDatalist')->send();
            return;
        }

        // Get all student billing masters for the active period
        $studentBillingMasters = StudentBillingMaster::where('period', $this->periode->id)->get();

        // Bus::batch([])->then(function (Batch $batch) use ($studentBillingMasters) {
        foreach ($studentBillingMasters as $studentBillingMaster) {
            $studentId = $studentBillingMaster->student_id;

            // Get billing master and billing details
            $billingMaster = BillingMaster::find($studentBillingMaster->billing_master_id);
            $billingDetails = BillingDetail::where('billing_master_id', $studentBillingMaster->billing_master_id)->get();

            if ($billingMaster->frequency === 'monthly') {
                // Create billing details for each month starting from January to December
                for ($month = 1; $month <= 12; $month++) {
                    foreach ($billingDetails as $detail) {
                        $existingDetail = StudentBillingDetail::where('student_id', $studentId)
                            ->where('student_billing_master_id', $studentBillingMaster->id)
                            ->where('billing_details_id', $detail->id)
                            ->where('month', $month)
                            ->exists();

                        if (!$existingDetail) {
                            StudentBillingDetail::create([
                                'student_id' => $studentId,
                                'student_billing_master_id' => $studentBillingMaster->id,
                                'billing_details_id' => $detail->id,
                                'discount_amount' => 0,
                                'discount_note' => '',
                                'paid' => 0,
                                'month' => $month, // Adding a month column to indicate the billing month
                                'created_at' => now()->month($month),
                                'updated_at' => now()->month($month),
                            ]);
                        }
                    }
                }
            } elseif ($billingMaster->frequency === 'yearly') {
                // Create billing details for the year
                foreach ($billingDetails as $detail) {
                    $existingDetail = StudentBillingDetail::where('student_id', $studentId)
                        ->where('student_billing_master_id', $studentBillingMaster->id)
                        ->where('billing_details_id', $detail->id)
                        ->exists();

                    if (!$existingDetail) {
                        StudentBillingDetail::create([
                            'student_id' => $studentId,
                            'student_billing_master_id' => $studentBillingMaster->id,
                            'billing_details_id' => $detail->id,
                            'discount_amount' => 0,
                            'discount_note' => '',
                            'paid' => 0,
                        ]);
                    }
                }
            } elseif ($billingMaster->frequency === 'once') {
                // Check if a similar billing exists for the same period
                foreach ($billingDetails as $detail) {
                    $existingDetail = StudentBillingDetail::where('student_id', $studentId)
                        ->where('student_billing_master_id', $studentBillingMaster->id)
                        ->where('billing_details_id', $detail->id)
                        ->exists();

                    if (!$existingDetail) {
                        StudentBillingDetail::create([
                            'student_id' => $studentId,
                            'student_billing_master_id' => $studentBillingMaster->id,
                            'billing_details_id' => $detail->id,
                            'discount_amount' => 0,
                            'discount_note' => '',
                            'paid' => 0,
                        ]);
                    }
                }
            }
        }
        // })->catch(function (Batch $batch, Throwable $e) {
        //     session()->flash('error', 'Terjadi kesalahan saat memproses tagihan.');
        // })->dispatch();

        session()->flash('message', 'Proses generate tagihan sedang berjalan, silakan cek nanti.');
        redirect()->route(session('active_role') . '.TagihanMasterDatalist')->send();
    }
}

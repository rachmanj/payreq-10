<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payreq;
use App\Models\RealizationDetailReceive;
use Illuminate\Http\Request;

class BucApiController extends Controller
{
    public function receive_buc_payreqs(Request $request)
    {
        $array_not_exist = [];
        $array_exist = [];
        // check if realization_details from payreq-x exist in table realization_detail_receives
        foreach ($request->json['realization_details'] as $realization_detail) {
            $is_exist = RealizationDetailReceive::where('realization_detail_id', $realization_detail['id'])->first();

            // if not exist, create new record in realization_detail_receives
            if (!$is_exist) {
                $realization_detail_receive = new RealizationDetailReceive();
                $realization_detail_receive->realization_detail_id = $realization_detail['id'];
                $realization_detail_receive->save();

                // // also create new record in payreqs table
                $payreq = new Payreq();
                $payreq->payreq_num = $realization_detail['nomor'];
                $payreq->realization_num = $realization_detail['nomor'];
                $payreq->rab_id = $realization_detail['rab_id'];
                $payreq->approve_date = date('Y-m-d', strtotime($realization_detail['created_at']));
                $payreq->outgoing_date = date('Y-m-d', strtotime($realization_detail['created_at']));
                $payreq->realization_date = date('Y-m-d', strtotime($realization_detail['created_at']));
                $payreq->verify_date = date('Y-m-d', strtotime($realization_detail['created_at']));
                $payreq->payreq_idr = $realization_detail['amount'];
                $payreq->realization_amount = $realization_detail['amount'];
                $payreq->remarks = $realization_detail['description'];
                $payreq->payreq_type = 'sync-buc';
                $payreq->otvd = 1;
                $payreq->user_id = 43; // this user_id of dncdiv
                $payreq->advance_category_id = 1; // 
                $payreq->que_group = 1; // 
                $payreq->created_by = 'payreq-x'; // 
                $payreq->save();

                array_push($array_not_exist, $realization_detail['id']);
            }

            if ($is_exist) {
                array_push($array_exist, $realization_detail['id']);
            }
        }

        return response()->json([
            "added" => $array_not_exist,
            "skipped" => $array_exist,
            "message" => 'success'
        ]);
    }
}

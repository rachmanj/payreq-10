<?php

namespace App\Http\Controllers;

use App\Models\Payreq;
use App\Models\RealizationDetailReceive;
use Illuminate\Http\Request;

class BucSyncController extends Controller
{
    public function index()
    {
        $last_sync = RealizationDetailReceive::select('created_at')->orderBy('created_at', 'desc')->first();
        $formated_last_sync = date('d-M-Y H:i:s', strtotime($last_sync->created_at . ' +8 hours'));

        return view('buc-sync.index', compact('formated_last_sync'));
    }

    public function sync_buc_payreqs()
    {
        $url = env('URL_BUC_PAYREQS');
        $response = file_get_contents($url);
        $rabs_data = json_decode($response, true);

        $array_not_exist = [];
        $array_exist = [];
        // check if realization_details from payreq-x exist in table realization_detail_receives
        foreach ($rabs_data['realization_details'] as $realization_detail) {
            $is_exist = RealizationDetailReceive::where('realization_detail_id', $realization_detail['realization_detail_id'])->first();

            // if not exist, create new record in realization_detail_receives
            if (!$is_exist) {
                $realization_detail_receive = new RealizationDetailReceive();
                $realization_detail_receive->realization_detail_id = $realization_detail['realization_detail_id'];
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

                array_push($array_not_exist, $realization_detail['realization_detail_id']);
            }

            if ($is_exist) {
                array_push($array_exist, $realization_detail['realization_detail_id']);
            }
        }

        $last_sync = RealizationDetailReceive::select('created_at')->orderBy('created_at', 'desc')->first();
        $formated_last_sync = date('d-M-Y H:i:s', strtotime($last_sync->created_at . ' +8 hours'));

        return view('buc-sync.result', [
            'added' => count($array_not_exist),
            'skipped' => count($array_exist),
            'formated_last_sync' => $formated_last_sync
        ]);
    }
}

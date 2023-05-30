<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class KomisiController extends Controller
{
    public function getKomisi()
    {
        $perhitungan = Penjualan::leftJoin('marketting', 'marketting.id', 'penjualan.marketting_id')
            ->selectRaw('marketting.name as marketting, MONTH(date) as month, SUM(total_balance) as omzet')
            ->groupBy(['marketting.id', 'month'])
            ->orderBy('month', 'asc')
            ->get();

        // format kondisi jika omzet kurang dari sama dengan 100jt berarti 0% komisi
        // format kondisi jika omzet lebih dari 100jt dan kurang dari sama dengan 200jt berarti 2.5% komisi
        // format kondisi jika omzet lebih dari 200jt dan kurang dari sama dengan 500jt berarti 5% komisi
        // format kondisi jika omzet lebih dari 500jt berarti 10% komisi
        foreach ($perhitungan as $key => $value) {
            if ($value->omzet <= 100000000) {
                $perhitungan[$key]['komisi'] = "0 %";
                $perhitungan[$key]['komisi_nominal'] = 0;
            } elseif ($value->omzet > 100000000 && $value->omzet <= 200000000) {
                $perhitungan[$key]['komisi'] = "2.5 %";
                $perhitungan[$key]['komisi_nominal'] = $value->omzet * 0.025;
            } elseif ($value->omzet > 200000000 && $value->omzet <= 500000000) {
                $perhitungan[$key]['komisi'] = "5 %";
                $perhitungan[$key]['komisi_nominal'] = $value->omzet * 0.05;
            } elseif ($value->omzet > 500000000) {
                $perhitungan[$key]['komisi'] = "10 %";
                $perhitungan[$key]['komisi_nominal'] = $value->omzet * 0.1;
            }
        }


        return response()->json([
            'status' => 'success',
            'data' => $perhitungan
        ]);
    }
}

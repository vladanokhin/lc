<?php

namespace App\Services;

use App\Models\LeadCollector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class DownloadService
{
    protected $lc;

    public function __construct(LeadCollector $leadCollector)
    {
        $this->lc = $leadCollector;
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function requestFor(Request $request): BinaryFileResponse
    {
        $request = $request->only([
            'click_id',
            'aff_network_name',
            'conversion_status',
            'country_code',
            'offer_id',
            'offer_name',
            'created_at_from',
            'created_at_to',
        ]);
        $file = 'leads-' . date('Y-m-d-') . time() . '.csv';
        $file = addslashes("/tmp/$file");
        $this->prepareFileFromRequest($request, $file);

        return response()->download($file);
    }

    private function prepareFileFromRequest($request, $filename): void
    {
        $stmt = [];
        foreach ($request as $key => $value) {
            if (empty($value)) continue;
            $value = str_replace(
                ['#', '1=1',], '',
                $value
            );

            if ($key === 'created_at_from') {
                if (!empty($request['created_at_from'])) {
                    $value = date('Y-m-d H:i:s', strtotime($value));
                    $stmt[] = "created_at > '$value'";
                }
                continue;
            }

            if ($key === 'created_at_to') {
                if (!empty($request['created_at_to'])) {
                    $value = date('Y-m-d H:i:s', strtotime('+23 hour +59 minutes +59 seconds', strtotime($value)));
                    $stmt[] = "created_at <= '$value'";
                }
                continue;
            }
            if ($key === "offer_name") {
                $stmt[] = "{$key} LIKE '%{$value}%'";
                continue;
            }
            $stmt[] = "{$key} LIKE '{$value}%'";
        }
        if (!empty($stmt)) {
            $stmt = 'WHERE ' . implode(' AND ', $stmt);
        } else {
            $stmt = 'LIMIT 1000';
        }
        $stmt = "SELECT * FROM leads {$stmt} INTO OUTFILE '{$filename}' FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n'";

        DB::connection('mysql_lc')->statement($stmt);
    }
}

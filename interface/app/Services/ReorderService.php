<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class ReorderService
{
    /**
     * @param array $payload
     * @param string $link
     * @return Response
     */
    public function reorderEdited(array $payload, string $link)
    {
        $link = "$link?".http_build_query($payload);

        return Http::get($link);
    }
}

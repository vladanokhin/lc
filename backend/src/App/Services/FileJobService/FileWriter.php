<?php

namespace src\App\Services\FileJobService;

final class FileWriter
{
    protected $mode;

    public function __construct(string $mode)
    {
        $this->mode = $mode;
    }

    public function writeCSV(array $data, string $filename = null): bool
    {
        $filename = (null === $filename) ? 'reserve' : $filename;

        if (!is_dir(DIR . "/resources/leads_backup")) {
            mkdir(DIR . "/resources/leads_backup",0775, true);
        }
        $handle = fopen(DIR . "/resources/leads_backup/{$filename}.csv", $this->mode);

        return $this->prepareDataForCsv($data, $handle);
    }

    /**
     * @param array $payload
     * @param $handler
     * @return bool
     */
    protected function prepareDataForCsv(array $payload, $handler): bool
    {
        $headers = implode(',', array_keys($payload));
        $data = [];
        $data[] = $headers;

        $values = [];
        foreach ($payload as $d) {
            $values[] = $d;
        }
        $values = implode(',', $values);

        $data[] = "\n" . $values;
        foreach ($data as $d) {
            fwrite($handler, implode(',', $data) . "\r\r\n\n");
        }

        return true;
    }
}
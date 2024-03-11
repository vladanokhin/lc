<?php /** @noinspection PhpInconsistentReturnPointsInspection */

namespace src\App\Services\FileJobService;

use function fgetcsv;

class FileReader
{
    protected $mode;

    public function __construct($mode)
    {
        $this->mode = $mode;
    }

    public function readCSV($filename = 'reserve', $format = 'csv'): array
    {
        $result = [];
        $row = 1;
        $path = DIR . '/resources';
        $file = fopen("{$path}/leads_backup/{$filename}.{$format}", "{$this->mode}");
        if ($file) {
            while ($data = fgetcsv($file)) {
                $num = count($data);
                $row++;
                for ($i = 0; $i < $num; $i++) {
                    $result[] = $data[$i];
                }
            }
            fclose($file);
        }

        return $result;
    }
}

<?php

namespace src\App\Services\FileJobService;

class FileJobManager
{
    /**
     * This method writes data to CSV file. If some data exists, method adding new data to end of the file.
     * If file not exists, method creates needed file.
     * @param array $data
     * @param string $filename
     * @return bool
     */
    public function write(array $data, string $filename): bool
    {
        $write = new FileWriter('a');

        return $write->writeCSV($data, $filename);
    }

    /**
     * This method is similar to previous, BUT IT REWRITE FILE DATA! It means that you will lost all data in file!
     * @param array $data
     * @param string $filename
     * @return bool
     */
    public function rewrite(array $data, string $filename): bool
    {
        $write = new FileWriter('w+');

        return $write->writeCSV($data, $filename);
    }

    /**
     * This method returns data from needed CSV file.
     * @param $filename
     * @return array
     */
    public function read($filename): array
    {
        $read = new FileReader('r');

        return $read->readCSV($filename);
    }

    /**
     * This method is deleting needed file. Specify full path to needed file.
     * @param string $name
     * @return bool
     */
    public function dropFile(string $name): bool
    {
        return unlink(DIR . '/resources/leads_backup/' . $name . '.csv');
    }
}

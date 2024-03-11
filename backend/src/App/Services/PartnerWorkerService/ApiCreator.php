<?php

namespace src\App\Services\PartnerWorkerService;

class ApiCreator
{
    public function addApi(array $payload): bool
    {
        return $this->putDataIntoClass(
            $this->prepareData($payload),
            $this->createFile($payload['partner'])
        );
    }

    protected function createFile(string $filename)
    {
        return fopen(__DIR__ . "/{$filename}.php", 'w');
    }

    protected function prepareData(array $payload): array
    {
        return $payload;
    }

    protected function putDataIntoClass(array $payload, $resource): bool
    {
        return fwrite($resource, $this->classTemplate($payload));
    }

    private function classTemplate(array $payload): string
    {
        $fieldsForDataPreparation = $this->prepareFieldsForPartner([
            'subid'    => 'click_id',
            'flow_id'  => 'product',
            'username' => 'name',
        ]); // передать поля партнёра соответсвующие полям ЛК
        $curlMethod = 'curl_setopt($curl, CURLOPT_POST, 1);';
        $headers = "[]";


        $class = <<<CLASS
<?php

namespace src\App\Services\PartnerWorkerService;

use src\interfaces\PartnerInterface\PartnerInterface;

class {$payload['partner']} implements PartnerInterface
{
    private \$token;
    private \$endpoint;
    
    public function __construct(\$token = null, \$endpoint = null) 
    {
        \$this->token = \$token;
        \$this->endpoint = \$endpoint;
    }
    
    public function sendLead(array \$lead, string \$product): bool
    {
        \$curl = curl_init();
        curl_setopt(\$curl, CURLOPT_URL, \$this->endpoint);
        curl_setopt(\$curl, CURLOPT_RETURNTRANSFER, 1);
        $curlMethod
        curl_setopt(\$curl, CURLOPT_POSTFIELDS, \$this->prepareDataForPartner(\$lead));
        curl_setopt(\$curl, CURLOPT_HTTPHEADER, $headers);
        
        return curl_exec(\$curl);
    }
    
    protected function prepareDataForPartner(array \$lead): string
    {
        if (isset(\$lead['data_3']) && !empty(\$lead['data_3'])) {
            \$lead['data_3'] = json_decode(\$lead['data_3'], true);
        }
        
        \$secondPhone = (isset(\$lead['second_phone']) && null != \$lead['second_phone']) ?
            \$lead['second_phone'] : null;
            
        return {$fieldsForDataPreparation};
    }
}
CLASS;

        return $class;
    }

    // -> to protected
    public function prepareFieldsForPartner(array $data): string
    {
        $result = '['. PHP_EOL;
        foreach ($data as $partnerField => $lcField)
        {
            $result .= "\t\t\t".'\''.$partnerField.'\' => $lead[\''.$lcField.'\'],' . PHP_EOL;
        }

        // Second phone
        // Проверять необходимость добавления второго номера телефона
        $result .= "\t\t\t'{$partnerField}' => \$secondPhone" . PHP_EOL;
        //

        $result .= "\t\t".']';

        return $result;
    }

    // -> to protected
    public function prepareHeadersForCurl(array $payload)
    {
        return "['1:1', '2:2']";
    }
}
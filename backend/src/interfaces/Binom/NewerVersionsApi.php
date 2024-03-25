<?php

namespace src\interfaces\Binom;

/**
 * The interface is used for newer versions of api binom.
 * From the second version and above.
 */
interface NewerVersionsApi
{

    /**
    * Converting and adding fields to make it look like in api v1,
     * for backwards compatibility.
     *
     * @param array $data
     * @return array
     */
    public function convertFieldsToV1(array $data): array;
}

<?php



namespace App\Helpers;


class ArrayHelper
{
    /**
     * Merges primary array keys with values of secondary, only if the keys in primary are present in secondary
     * @param array $primary
     * @param array $secondary
     * @return array A new array that contains keys of primary with values in secondary.
     */
    public static function mergePrimaryKeysAndValues(array $primary, array $secondary){
        $otherArray = array();
        foreach (array_keys($primary) as $key){
            if(array_key_exists($key, $secondary))
                $otherArray[$key] = $secondary[$key];
        }
        return $otherArray;

    }
}

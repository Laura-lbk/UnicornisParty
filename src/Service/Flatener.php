<?php

namespace App\Service;

class Flatener{

        public function flatten(array $array):array
        {
            $result = [];
            foreach ($array as $item) {
                if (is_null($item)) {
                    continue;
                }
                $result = array_merge($result, is_array($item)?$this->flatten($item):[$item]);
            }
            return $result;
        }
}
        


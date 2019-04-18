<?php

namespace App\NipContainer;

class NipChecker
{
    public static function checkNip ($nip): bool
    {
        $nip = preg_replace('/[^0-9]+/', '', $nip);
 
        if (strlen($nip) !== 10) {
            return false;
        }

        $remainder = NipGenerator::checkModuloResult($nip);

        /*if($remainder===10){
            $intControlNr = 0;
        } else {
            $intControlNr = $remainder;
        }*/
        $intControlNr = $remainder === 10 ? 0 : $remainder;
    
        if ($intControlNr == $nip[9]) {
            return true;
        }
 
	return false;
    }
}
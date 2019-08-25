<?php

namespace App\Nip;

class NipGenerator
{
    public static function checkModuloResult($nip)
    {
        $stepsArray = array(6, 5, 7, 2, 3, 4, 5, 6, 7);
        $multiplicationSum = 0;

        for ($i = 0; $i < 9; $i++) {
            $multiplicationSum += $stepsArray[$i] * $nip[$i];
        }
        $remainder =  $multiplicationSum % 11;
        return $remainder;
    }

    public static function generateNip(): string
    {
        $nip = rand (100000000 , 999999999);
        $remainder = NipGenerator::checkModuloResult($nip);
        $isNipValid = false;

        while($isNipValid === false){
            $nip = rand (100000000 , 999999999);
            $remainder = NipGenerator::checkModuloResult($nip);
            if($remainder === 10) $isNipValid = false;
            settype($remainder, "string");
            settype($nip, "string");
            $nip .=$remainder; 

            $isNipValid = NipChecker::checkNip($nip);
        }



        
        return $nip;
    }

    public static function generateFalseNip(): string
    {
        $nip = NipGenerator::generateNip();

        $replacement = substr($nip, -1);
        settype($replacement, "integer");
        $replacement+=1;
        $intControlNr = $replacement === 10 ? 1 : $replacement;
        $falseNip = substr_replace($nip, $intControlNr,-1); 
        return $falseNip;
    }
}
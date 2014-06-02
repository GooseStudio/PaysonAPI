<?php
namespace CyoniteSystems\PaysonAPI;


class FundingConstraint {

    const NONE = 0;
    const CREDITCARD = 1;
    const BANK = 2;
    const INVOICE = 3;

    public static function toString($constraints) {
        $output=[];
        for ($i=0;$i<sizeof($constraints); $i++) {
            if ($constraints[$i] == self::NONE)
                continue;
            $output[] = "fundingList.fundingConstraint($i).constraint=" . self::ConstantToString($constraints[$i]);
        }
        return implode('&', $output);
    }

    /**
     * @param $constraint
     * @throws PaysonApiException
     * @return string
     */
    public static function ConstantToString($constraint) {
        switch ($constraint) {
            case self::BANK:
                return "BANK";
            case self::CREDITCARD:
                return "CREDITCARD";
            case self::INVOICE:
                return "INVOICE";
            default:
                throw new PaysonApiException('Invalid funding constraint '. $constraint);
        }
    }
}

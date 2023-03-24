<?php
class Calculators
{
    public function calculateBMI($weight, $height)
    {
        $heightInMeter = $height / 100;
        $BMI = $weight / ($heightInMeter * $heightInMeter);

        $this->resultOfBMI($BMI);

        return round($BMI, 2);
    }

    public function calculateBMR($sex, $weight, $height, $dateOfBirth, $activity)
    {
        $date = (int)date("Y");
        $age = $date - $dateOfBirth;
        $BMR = 0;
        $activityBMR = 0;

        if ($sex === 'férfi') {
            $BMR = 66.46 + (13.7 * $weight) + (5 * $height) - (6.8 * $age);;
            $activityBMR = $this->checkActivity($activity, $BMR);
            return [
                "BMR" => round($BMR, 0),
                "activityBMR" => round($activityBMR, 0)
            ];
        }

        $BMR = 655.1 + (9.6 * $weight) + (1.8 * $height) - (4.7 * $age);
        $activityBMR = $this->checkActivity($activity, $BMR);

        return [
            "BMR" => round($BMR, 0),
            "activityBMR" => round($activityBMR, 0)
        ];
    }

    private function checkActivity($activity, $BMR)
    {
        $BMR = (int)$BMR;
        $activity = (int)$activity;
        switch ($activity) {
            case 1:
                $BMR *= 1.2;
                return $BMR;
                break;
            case 2:
                $BMR *= 1.4;
                return $BMR;
                break;
            case 3:
                $BMR *= 1.6;
                return $BMR;
                break;
            case 4:
                $BMR *= 1.8;
                return $BMR;
                break;
            case 5:
                $BMR *= 2.0;
                break;
            default:
                return "Something is went wrong";
        }
    }

    public function resultOfBMI($BMI)
    {
        $result = [];
        switch ($BMI) {
            case $BMI < 18.5:
                $result = [
                    "state" => "sovány",
                    "riskOfHealth" => "Közepes"
                ];
                break;
            case ($BMI > 18.5 && $BMI < 24.9):
                $result = [
                    "state" => "egészséges testsúly",
                    "riskOfHealth" => "Alacsony"
                ];
                break;
            case ($BMI > 25.0 && $BMI < 29.9):
                $result = [
                    "state" => "túlsúlyos",
                    "riskOfHealth" => "Közepes"
                ];
                break;
            case $BMI > 30.0:
                $result = [
                    "state" => "elhízott",
                    "riskOfHealth" => "Magas"
                ];
                break;
            default:
                "Something went wrong";
                break;
        }

        return $result;
    }

    public function resultOfGoal($BMR, $goal)
    {
        $kcalByGoal = 0;
        if ($goal === "testsúly_csökkentése") {
            $kcalByGoal = $BMR - 500;
            return $kcalByGoal;
        } else if ($goal === "testsúly_növelése") {
            $kcalByGoal = $BMR + 500;
            return $kcalByGoal;
        } else {
            return $BMR;
        }
    }
}

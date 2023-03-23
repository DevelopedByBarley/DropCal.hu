<?php
class Validators
{
  public function required()
  {
    return [
      "validatorName" => "required",
      "validatorFn" => fn ($input) => (bool)$input,
      "params" => []
    ];
  }

  public function checkPassword()
  {
    return [
      "validatorName" => "checkPassword",
      "validatorFn" => function ($input) {

        $number = preg_match('@[0-9]@', $input);
        $uppercase = preg_match('@[A-Z]@', $input);
        $lowercase = preg_match('@[a-z]@', $input);
        $specialChars = preg_match('@[^\w]@', $input);

        if (strlen($input) < 8 || !$number || !$uppercase || !$lowercase || !$specialChars) {
          return false;
        } else {
          return true;
        }
      },
      "params" => []
    ];
  }

  public function validateEmail() {
    return [
      "validatorName" => "validateEmail",
      "validatorFn" => fn ($input) => filter_var($input, FILTER_SANITIZE_EMAIL),
      "params" => []
    ];
  }
}

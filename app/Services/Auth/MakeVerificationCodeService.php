<?php
namespace App\Services\Auth;


class MakeVerificationCodeService{

    public function makeEmailVerificationCode(): int
    {
        return rand(100000, 999999);
    }
    public function makePhoneVerificationCode(): int
    {
        return rand(100000, 999999);
    }
}

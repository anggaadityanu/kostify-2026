<?php
namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidEmailDomain implements ValidationRule
{
    protected array $typoMap = [
        'gmial.com'   => 'gmail.com',
        'gmali.com'   => 'gmail.com',
        'gmaal.com'   => 'gmail.com',
        'gmaill.com'  => 'gmail.com',
        'gmail.con'   => 'gmail.com',
        'gmail.co'    => 'gmail.com',
        'gmail.cm'    => 'gmail.com',
        'gmail.vom'   => 'gmail.com',
        'gmai.com'    => 'gmail.com',
        'yahooo.com'  => 'yahoo.com',
        'yahoo.con'   => 'yahoo.com',
        'yaho.com'    => 'yahoo.com',
        'hotmal.com'  => 'hotmail.com',
        'hotmail.con' => 'hotmail.com',
        'outlok.com'  => 'outlook.com',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!str_contains($value, '@')) return;

        $domain = strtolower(substr($value, strpos($value, '@') + 1));

        if (isset($this->typoMap[$domain])) {
            $suggestion = substr($value, 0, strpos($value, '@') + 1) . $this->typoMap[$domain];
            $fail("Domain email sepertinya salah ketik. Maksud Anda: {$suggestion}?");
            return;
        }

        if (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
            $fail("Domain email '{$domain}' tidak valid atau tidak ditemukan.");
        }
    }
}

<?php

namespace App\Reports\Contracts;

interface ReportContract
{
    public function handle(): void;
}

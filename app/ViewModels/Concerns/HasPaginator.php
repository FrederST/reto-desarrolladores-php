<?php

namespace App\ViewModels\Concerns;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Resources\Json\JsonResource;

trait HasPaginator
{
    protected Paginator|JsonResource $collection;

    public function collection(Paginator|JsonResource $collection): self
    {
        $this->collection = $collection;
        return $this;
    }
}

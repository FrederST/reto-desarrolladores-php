<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class Filter
{
    protected array $applicableConditions = [];

    protected string $model;
    protected Builder $query;
    protected array $conditions;

    public function __construct(array $conditions = [])
    {
        $this->query = $this->newModel()->newQuery();
        $this->conditions($conditions);
    }

    private function newModel(): Model
    {
        return new $this->model();
    }

    public function apply(): Builder
    {
        $this->select()->joins()->where();
        return $this->query;
    }

    public function conditions(array $conditions = []): self
    {
        $this->conditions = array_filter($conditions);
        return $this;
    }

    protected function where(): self
    {
        foreach (
            array_intersect_key($this->conditions, $this->applicableConditions)
            as $condition => $value) {
            $conditionClass = $this->getCondition($condition);
            $conditionClass::append($this->query, new Criteria($value));
        }

        return $this;
    }

    private function getCondition(string $condition): Condition
    {
        return new $this->applicableConditions[$condition]();
    }

    protected function joins(): self
    {
        return $this;
    }

    protected function select(): self
    {
        return $this;
    }
}

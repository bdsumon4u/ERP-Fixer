<?php

namespace App\Tables\Tenant\Admin;

use App\Models\Brand;
use Hotash\InertiaTable\TableBuilder;
use Spatie\QueryBuilder\QueryBuilder;

class BrandTable extends TableBuilder
{
    protected string $model = Brand::class;

    protected function buildTable(): void
    {
        $this->withGlobalSearch();
        $this->column('id', 'ID', false, false, true, true);
        $this->column('title', 'Tile', true, false, true, true);
        $this->column('slug', 'Slug', true, false, true, true);
    }

    protected function buildQuery(QueryBuilder $builder): QueryBuilder
    {
        return $builder;
    }
}

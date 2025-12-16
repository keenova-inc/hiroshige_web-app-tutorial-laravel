<?php

declare(strict_types=1);

namespace App\Repositories\Article;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ArticleRepositoryInterface
{
    public function search(int $page): ?LengthAwarePaginator;
    public function find(int $id): ?Article;
    public function create(array $data): ?Article;
    public function update(array $data): ?Article;
    public function delete(int $id): ?Article;
    public function like(int $id): ?Article;
}

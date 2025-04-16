<?php

namespace App\Utils;

class Pagination
{
    private int $perPage;
    private int $currentPage;

    public function __construct(int $perPage = 10, int $currentPage = 1) {
        $this->perPage = $perPage;
        $this->currentPage = $currentPage;
    }

    public function getPerPage(): int {
        return $this->perPage;
    }

    public function setPerPage(int $perPage): void {
        $this->perPage = $perPage;
    }

    public function getCurrentPage(): int {
        return $this->currentPage;
    }

    public function setCurrentPage(int $currentPage): void {
        $this->currentPage = $currentPage;
    }

    public function toArray(): array {
        return [
            'per_page' => $this->perPage,
            'current_page' => $this->currentPage,
        ];
    }
}

<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;


class PaginatorService 
{
    private $previous = 0;
    private $current = 1;
    private $next = 2;
    private $total = 2;

    public function __construct(Request $request, int $count, int $limit = 100) {
        $this->init($request, $count, $limit);
    }

    public function getPreviousPage() : int
    {
       return $this->previous;
    }
    public function getCurrentPage() : int
    {
       return $this->current;
    }
    public function getNextPage() : int
    {
       return $this->next;
    }
    public function getTotalPages() : int
    {
       return $this->total;
    }
    
    private function init(Request $request, int $count, int $limit)
    {
        $currentPage = $request->query->get('page') ?? 1;
        $currentPage = $currentPage > 0 ? $currentPage : 1;

        $totalPages = ($count / $limit) + ($count % $limit > 0 ? 1 : 0); 
        $this->total = $totalPages;
        

        $currentPage = $currentPage > $totalPages ? 1 : $currentPage;
        $this->current = $currentPage;
        
        $previousPage = $currentPage - 1;
        $previousPage = $previousPage > $totalPages ? 0 : $previousPage;
        $this->previous = $previousPage;
        
        $nextPage = $count - (($currentPage + 1) * $limit) > 0 ? ($currentPage + 1) : 0;
        $nextPage = $nextPage > $totalPages ? 0 : $nextPage;
        $this->next = $nextPage;
    }

    
    
}

<?php
namespace Core\Lib\Pagination;

/**
 * Class that supports pagination for views.
 */
class Pagination {
    protected int $currentPage;
    protected int $limit;
    protected int $totalItems;

    /**
     * Constructor for Pagination class
     *
     * @param int $currentPage The current page that is selected.
     * @param int $limit The maximum number of items for a page.
     * @param int $totalItems The total number of items for a list
     */
    public function __construct(int $currentPage = 1, int $limit = 10, int $totalItems = 0) {
        $this->currentPage = $currentPage;
        $this->limit = $limit;
        $this->totalItems = $totalItems;
    }

    /**
     * Retrieves current page from GET request.
     *
     * @param mixed $request The request.
     * @return int The current page number.
     */
    public static function currentPage(mixed $request): int {
        $page = $request->get('page');
        return $page != null ? $page : 1;
    }

    /**
     * Calculates offset needed for pagination.
     *
     * @param int $page The current page.
     * @param int $limit The maximum allowed number of items for a page.
     * @return int The offset
     */
    public function offset(): int {
        return ($this->currentPage - 1) * $this->limit;
    }

    /**
     * Renders Bootstrap 5 pagination controls.
     *
     * @param int $current_page The current page number.
     * @param int $total_pages The total number of pages.
     * @param string $base_url The base URL for pagination links. Default is '?page='.
     *
     * @return string The HTML markup for the pagination controls.
     */
    public static function pagination(int $current_page, int $total_pages, string $base_url = '?page=') {
        $html = '<nav aria-label="Page navigation">';
        $html .= '<ul class="pagination justify-content-center">';

        // Previous Button
        if($current_page <= 1) {
            $html .= '<li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>';
        } else {
        $html .= '<li class="page-item">
                    <a class="page-link" href="' . $base_url . ($current_page - 1) . '" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>';
        }

        // Page Number Links
        for ($i = 1; $i <= $total_pages; $i++) {
            if($i == $current_page) {
                $html .= '<li class="page-item active">
                        <a class="page-link" href="' . $base_url . $i . '">' . $i . '</a>
                    </li>';
            } else {
                $html .= '<li class="page-item">
                        <a class="page-link" href="' . $base_url . $i . '">' . $i . '</a>
                    </li>';
            }
        }

        // Next Button
        if($current_page >= $total_pages) {
            $html .= '<li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>';
        } else {
            $html .= '<li class="page-item">
                <a class="page-link" href="' . $base_url . ($current_page + 1) . '" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>';
        }

        $html .= '</ul></nav>';
        return $html;
    }

    /**
     * Builds parameters for pagination query.
     *
     * @param mixed $conditions The conditions for the query.
     * @param array $bind What we want to bind.
     * @param string $order The order we want the results returned.
     * @return array An array of params tailored for pagination.
     */
    public function paginationParams(mixed $conditions, array $bind = [], string $order): array {
        return [
            'conditions' => $conditions,
            'bind' => $bind,
            'order' => $order,
            'limit' => $this->limit,
            'offset' => $this->offset()
        ];
    }

    /**
     * Returns total number of pages we need to handle.
     *
     * @return int The total number of pages to be manged by pagination.
     */
    public function totalPages(): int {
        return ceil($this->totalItems / $this->limit);
    }
}
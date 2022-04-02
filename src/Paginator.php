<?php

namespace CoffeeCode\Paginator;

/**
 * Class CoffeeCode Paginator
 *
 * @author Robson V. Leite <https://github.com/robsonvleite>
 * @package CoffeeCode\Paginator
 */
class Paginator
{
    /** @var int|null */
    private ?int $page;

    /** @var int */
    private int $pages;

    /** @var int */
    private int $rows;

    /** @var int|null */
    private ?int $limit;

    /** @var int|null */
    private ?int $offset;

    /** @var int */
    private int $range;

    /** @var string */
    private ?string $link;

    /** @var string */
    private string $title;

    /** @var string */
    private string $class;

    /** @var string|null */
    private ?string $hash;

    /** @var array */
    private array $first;

    /** @var array */
    private array $last;

    /** @var string */
    private string $params;

    /**
     * Paginator constructor.
     * @param string|null $link
     * @param string|null $title
     * @param array|null $first
     * @param array|null $last
     */
    public function __construct(string $link = null, string $title = null, array $first = null, array $last = null)
    {
        $this->link = ($link ?? "?page=");
        $this->title = ($title ?? "Página");
        $this->first = ($first ?? ["Primeira página", "<<"]);
        $this->last = ($last ?? ["Última página", ">>"]);
    }

    /**
     * @param int $rows
     * @param int $limit
     * @param int|null $page
     * @param int $range
     * @param string|null $hash
     * @param array $params
     */
    public function pager(
        int $rows,
        int $limit = 10,
        int $page = null,
        int $range = 3,
        string $hash = null,
        array $params = []
    ): void {
        $this->rows = $this->toPositive($rows);
        $this->limit = $this->toPositive($limit);
        $this->range = $this->toPositive($range);
        $this->pages = (int)ceil($this->rows / $this->limit);
        $this->page = ($page <= $this->pages ? $this->toPositive($page) : $this->pages);

        $this->offset = (($this->page * $this->limit) - $this->limit >= 0 ? ($this->page * $this->limit) - $this->limit : 0);
        $this->hash = (!empty($hash) ? "#{$hash}" : null);

        $this->addGetParams($params);

        if ($this->rows && $this->offset >= $this->rows) {
            header("Location: {$this->link}" . ceil($this->rows / $this->limit));
            exit;
        }
    }

    /**
     * @return int
     */
    public function limit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function offset(): int
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function page(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function pages(): int
    {
        return $this->pages;
    }

    /**
     * @param string|null $cssClass
     * @param bool $fixedFirstAndLastPage
     * @return null|string
     */
    public function render(string $cssClass = null, bool $fixedFirstAndLastPage = true): ?string
    {
        $this->class = $cssClass ?? "paginator";

        if ($this->rows > $this->limit):
            $paginator = "<nav class=\"{$this->class}\">";
            $paginator .= $this->firstPage($fixedFirstAndLastPage);
            $paginator .= $this->beforePages();
            $paginator .= "<span class=\"{$this->class}_item {$this->class}_active\">{$this->page}</span>";
            $paginator .= $this->afterPages();
            $paginator .= $this->lastPage($fixedFirstAndLastPage);
            $paginator .= "</nav>";
            return $paginator;
        endif;

        return null;
    }

    /**
     * @return null|string
     */
    private function beforePages(): ?string
    {
        $before = null;
        for ($iPag = $this->page - $this->range; $iPag <= $this->page - 1; $iPag++):
            if ($iPag >= 1):
                $before .= "<a class=\"{$this->class}_item\" title=\"{$this->title} {$iPag}\" href=\"{$this->link}{$iPag}{$this->hash}{$this->params}\">{$iPag}</a>";
            endif;
        endfor;

        return $before;
    }

    /**
     * @return string|null
     */
    private function afterPages(): ?string
    {
        $after = null;
        for ($dPag = $this->page + 1; $dPag <= $this->page + $this->range; $dPag++):
            if ($dPag <= $this->pages):
                $after .= "<a class=\"{$this->class}_item\" title=\"{$this->title} {$dPag}\" href=\"{$this->link}{$dPag}{$this->hash}{$this->params}\">{$dPag}</a>";
            endif;
        endfor;

        return $after;
    }

    /**
     * @param bool $fixedFirstAndLastPage
     * @return string|null
     */
    public function firstPage(bool $fixedFirstAndLastPage = true): ?string
    {
        if ($fixedFirstAndLastPage || $this->page != 1) {
            return "<a class=\"{$this->class}_item\" title=\"{$this->first[0]}\" href=\"{$this->link}1{$this->hash}{$this->params}\">{$this->first[1]}</a>";
        }
        return null;
    }

    /**
     * @param bool $fixedFirstAndLastPage
     * @return string|null
     */
    public function lastPage(bool $fixedFirstAndLastPage = true): ?string
    {
        if ($fixedFirstAndLastPage || $this->page != $this->pages) {
            return "<a class=\"{$this->class}_item\" title=\"{$this->last[0]}\" href=\"{$this->link}{$this->pages}{$this->hash}{$this->params}\">{$this->last[1]}</a>";
        }
        return null;
    }

    /**
     * @param $number
     * @return int
     */
    private function toPositive($number): int
    {
        return ($number >= 1 ? $number : 1);
    }

    /**
     * Add get parameters
     * @param array $params
     * @return Paginator
     */
    private function addGetParams(array $params): Paginator
    {
        $this->params = '';

        if (count($params) > 0) {
            if (isset($params['page'])) {
                unset($params['page']);
            }

            $this->params = '&';
            $this->params .= http_build_query($params);
        }

        return $this;
    }
}
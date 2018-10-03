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
    /** @var int */
    private $page;

    /** @var int */
    private $rows;

    /** @var int */
    private $limit;

    /** @var int */
    private $offset;

    /** @var int */
    private $range;

    /** @var string */
    private $link;

    /** @var string */
    private $title;

    /** @var string */
    private $hash;

    /** @var array */
    private $first;

    /** @var array */
    private $last;

    /** @var string */
    private $cssClass;

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
     */
    public function pager(int $rows, int $limit = 10, int $page = null, int $range = 3, string $hash = null): void
    {
        $this->page = ($page ?? 1);
        $this->rows = $rows;
        $this->limit = $limit;
        $this->range = $range;

        $this->offset = (($page * $limit) - $limit >= 0 ? ($page * $limit) - $limit : 0);
        $this->hash = ($hash ? "#{$hash}" : null);

        if ($this->offset >= $this->rows) {
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
     * @param string $cssClass
     * @return null|string
     */
    public function render(string $cssClass = "paginator"): ?string
    {
        $this->cssClass = $cssClass;

        if ($this->rows > $this->limit):
            $pages = ceil($this->rows / $this->limit);
            $paginator = "<nav class=\"{$this->cssClass}\">";
            $paginator .= "<a class='{$this->cssClass}_item' title=\"{$this->first[0]}\" href=\"{$this->link}1{$this->hash}\">{$this->first[1]}</a>";
            $paginator .= $this->beforePages();
            $paginator .= "<span class=\"{$this->cssClass}_item {$this->cssClass}_active\">{$this->page}</span>";
            $paginator .= $this->afterPages($pages);
            $paginator .= "<a class='{$this->cssClass}_item' title=\"{$this->last[0]}\" href=\"{$this->link}{$pages}{$this->hash}\">{$this->last[1]}</a>";
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
                $before .= "<a class='{$this->cssClass}_item' title=\"{$this->title} {$iPag}\" href=\"{$this->link}{$iPag}{$this->hash}\">{$iPag}</a>";
            endif;
        endfor;

        return $before;
    }

    /**
     * @param int $pages
     * @return null|string
     */
    private function afterPages(int $pages): ?string
    {
        $after = null;
        for ($dPag = $this->page + 1; $dPag <= $this->page + $this->range; $dPag++):
            if ($dPag <= $pages):
                $after .= "<a class='{$this->cssClass}_item' title=\"{$this->title} {$dPag}\" href=\"{$this->link}{$dPag}{$this->hash}\">{$dPag}</a>";
            endif;
        endfor;

        return $after;
    }
}
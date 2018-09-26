<?php

namespace CoffeeCode\Paginator;

/**
 * Class CoffeCodde Paginator
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

    /**@var array */
    private $last;

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
     * @return Paginator
     */
    public function pager(int $rows, int $limit = 10, int $page = null, int $range = 3, string $hash = null): Paginator
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

        return $this;
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
    public function render($cssClass = "paginator"): ?string
    {
        if ($this->rows > $this->limit):
            $pages = ceil($this->rows / $this->limit);
            $range = $this->range;

            $paginator = "<nav class=\"{$cssClass}\">";
            $paginator .= "<a class='{$cssClass}_item' title=\"{$this->first[0]}\" href=\"{$this->link}1{$this->hash}\">{$this->first[1]}</a>";

            for ($iPag = $this->page - $range; $iPag <= $this->page - 1; $iPag++):
                if ($iPag >= 1):
                    $paginator .= "<a class='{$cssClass}_item' title=\"{$this->title} {$iPag}\" href=\"{$this->link}{$iPag}{$this->hash}\">{$iPag}</a>";
                endif;
            endfor;

            $paginator .= "<span class=\"{$cssClass}_item {$cssClass}_active\">{$this->page}</span>";

            for ($dPag = $this->page + 1; $dPag <= $this->page + $range; $dPag++):
                if ($dPag <= $pages):
                    $paginator .= "<a class='{$cssClass}_item' title=\"{$this->title} {$dPag}\" href=\"{$this->link}{$dPag}{$this->hash}\">{$dPag}</a>";
                endif;
            endfor;

            $paginator .= "<a class='{$cssClass}_item' title=\"{$this->last[0]}\" href=\"{$this->link}{$pages}{$this->hash}\">{$this->last[1]}</a>";
            $paginator .= "</nav>";

            return $paginator;
        endif;

        return null;
    }
}
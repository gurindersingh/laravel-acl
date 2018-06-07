<?php

use Illuminate\Support\Collection;

Collection::macro('groupByFirstLetter', function ($column = null) {

    return $this->groupBy(function ($item) use ($column) {

        $itemToSort = is_array($item) ? $item[$column] : $item->$column;

        return $column ? substr($itemToSort, 0, 1) : null;

    });

});
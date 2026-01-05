<?php

namespace Helper;

final class Debug {

    public function dump_block($title, $value): void {
        echo "<h2 style='margin: 16px; color: chartreuse;'>";
        echo $title;
        echo "</h2>";
        var_dump($value);
    }
}



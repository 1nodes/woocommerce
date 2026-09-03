<?php
namespace OneNodes\Gateway\lib;


class View
{
    public static function render(
        string $path,
        array $data = [],
        bool $return = true
    ): bool|string {

        extract(
            $data,
            EXTR_SKIP
        );

        ob_start();

        include ONENODES_PATH . 'views/' . $path;

        $content = ob_get_clean();

        if ($return) {
            return $content;
        }

        echo $content;

        return true;
    }
}
<?php
namespace OneNodes\Gateway\lib;


class View
{
    public static function render(
        string $path,
        array $data = [],
        bool $return = true
    ): bool|string {

        extract($data, EXTR_SKIP);

        if ($return) {
            ob_start();

            include ONENODES_PATH . 'views/' . $path;

            return ob_get_clean();
        }

        include ONENODES_PATH . 'views/' . $path;

        return true;
    }
}
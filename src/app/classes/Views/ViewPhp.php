<?php

namespace MutovSlingr\Views;

/**
 * Class ViewPhp
 *
 * @package MutovSlingr\Views
 */
class ViewPhp extends View
{

    /**
     * @param array $content
     * @return string
     */
    public function render(array $content)
    {
        $output = '<?php $data = ' . var_export($content, true) . ';';
        $fullpath = '/var/www/mutov-slingr/app/var/data.php';

        file_put_contents($fullpath, $output);

        return $output;
    }

}

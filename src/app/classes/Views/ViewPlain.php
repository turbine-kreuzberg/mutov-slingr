<?php


namespace MutovSlingr\Views;


class ViewPlain extends View
{

    const CONTENT_TYPE = 'text/html';

    public function render(array $content){
        return var_dump($content, true);
    }

}

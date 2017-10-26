<?php
namespace TspiderTest;

use Tspider\TspiderItem;

class MyItem extends TspiderItem
{

    public function rules(){
        return [
            'article' => [
                'rule' => '.news .news_list ul a',
                'link' => 'attributes.href',
                'text' => 'textContent'
            ],
            'hot-blog' => [
                'rule' => '.wrap .left .hot_blog ul a',
                'link' => 'attributes.href',
                'text' => 'textContent'
            ]
        ];
    }

    public function handle($items){
        var_dump($items);
    }
}
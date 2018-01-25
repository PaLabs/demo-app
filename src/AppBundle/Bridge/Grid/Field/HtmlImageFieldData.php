<?php


namespace AppBundle\Bridge\Grid\Field;


use PaLabs\DatagridBundle\Field\Type\BaseFieldData;

class HtmlImageFieldData extends BaseFieldData
{
    protected $url;

    public function __construct(string $url, array $options = [])
    {
        parent::__construct($options);
        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

}
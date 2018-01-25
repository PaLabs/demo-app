<?php


namespace AppBundle\Bridge\Grid\Field;


use GuzzleHttp\Client;
use PaLabs\DatagridBundle\Field\Field;
use PaLabs\DatagridBundle\Field\FieldData;
use PaLabs\DatagridBundle\Field\Type\InvalidDataTypeException;
use PaLabs\DatagridBundle\Grid\Export\XlsxExporter;
use PaLabs\DatagridBundle\Grid\GridOptions;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class HtmlImageField implements Field
{
    public static function field(string $url, array $options = [])
    {
        return new HtmlImageFieldData($url, $options);
    }

    public function render(FieldData $data, String $format)
    {
        switch ($format) {
            case GridOptions::RENDER_FORMAT_HTML:
                return $this->renderHtml($data);
            case XlsxExporter::FORMAT:
                return $this->renderXlxs($data);
            default:
                return $this->renderTxt($data);
        }
    }

    public function renderHtml(FieldData $data)
    {
        if (!$data instanceof HtmlImageFieldData) {
            throw new InvalidDataTypeException($data, $this->dataClass());
        }

        $html = sprintf('<img src="%s">', $data->getUrl());
        return $html;
    }

    public function renderTxt(FieldData $data)
    {
        if (!$data instanceof HtmlImageFieldData) {
            throw new InvalidDataTypeException($data, $this->dataClass());
        }
        return $data->getUrl();
    }

    private function renderXlxs(FieldData $data)
    {
        if (!$data instanceof HtmlImageFieldData) {
            throw new InvalidDataTypeException($data, $this->dataClass());
        }

        $tmpfname = tempnam("/tmp", "flags");

        try {
            $client = new Client();
            $client->request('GET', $data->getUrl(), ['sink' => $tmpfname]);
        } catch(\Exception $e) {
            return $data->getUrl();
        }
        $gdImage = imagecreatefrompng($tmpfname);

        $objDrawing = new MemoryDrawing();
        $objDrawing->setName('');
        $objDrawing->setDescription('');
        $objDrawing->setImageResource($gdImage);
        $objDrawing->setRenderingFunction(\PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
        $objDrawing->setMimeType(\PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
        $objDrawing->setWidth(33);
        $objDrawing->setHeight(20);
        return $objDrawing;
    }

    public function dataClass(): string
    {
        return HtmlImageFieldData::class;
    }


}
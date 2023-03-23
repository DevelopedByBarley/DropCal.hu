<?php

class XLSX
{
    public function write($results)
    {
        $writer = new XLSXWriter();

        $header = array(
            'Azonosító' => 'integer',
            'Név' => 'string',
        );
        $writer->writeSheetHeader('Sheet1', $header);

        foreach ($results as $result) {
            $writer->writeSheetRow('Sheet1', array(
                $result['id'],
                $result['name'],
            ));
        }

        // Fájl mentése
        $filename = 'export.xlsx';
        $writer->writeToFile($filename);

        // Fájl letöltése
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Expires: 0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
    }
}

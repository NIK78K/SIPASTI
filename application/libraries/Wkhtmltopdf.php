<?php
class Wkhtmltopdf {

    protected $binary = '"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe"';

    public function generate($html, $output = 'output.pdf', $options = [])
    {
        $tmpHtml = tempnam(sys_get_temp_dir(), 'html_') . '.html';
        file_put_contents($tmpHtml, $html);

        $opt = '';
        foreach ($options as $k => $v) {
            $opt .= " --$k $v ";
        }

        header('Content-Type: application/pdf');
        header("Content-Disposition: attachment; filename=\"$output\"");

        $cmd = "{$this->binary} $opt $tmpHtml -";
        passthru($cmd);

        unlink($tmpHtml);
        exit;
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf extends Dompdf
{
    public function __construct()
    {
        parent::__construct();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $this->setOptions($options);
    }
}

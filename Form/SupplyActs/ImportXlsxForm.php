<?php

namespace App\Entity\Form\SupplyActs;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class ImportXlsxForm
{
    protected $file;

    /**
     * @Assert\File(mimeTypes={
     *     "application/zip",
     *     "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
     *     "application/octet-stream"
     * })
     */
    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
    }
}
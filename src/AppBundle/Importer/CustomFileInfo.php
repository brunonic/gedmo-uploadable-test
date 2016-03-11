<?php

namespace AppBundle\Importer;

use Gedmo\Uploadable\FileInfo\FileInfoArray;

class CustomFileInfo extends FileInfoArray
{
    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $explodeUrl = explode('/', $url);
        $fileName   = $explodeUrl[sizeof($explodeUrl) - 1];

        $content = file_get_contents($url);

        $fp = fopen('/tmp/' . $fileName, 'w');
        fwrite($fp, $content);
        fclose($fp);

        $this->fileInfo = [
            'tmp_name' => '/tmp/' . $fileName,
            'name' => $fileName,
            'size' => strlen(file_get_contents($url)),
            'type' => $this->getImgExtWithFilePath($url),
            'error' => 0
        ];
    }

    /**
     * @return bool
     */
    public function isUploadedFile()
    {
        return false;
    }

    /**
     * @param $url
     *
     * @return bool
     */
    private function getImgExtWithFilePath($url)
    {
        return $this->getImgExtWithImageTypeConst(exif_imagetype($url));
    }

    /**
     * @param $value
     *
     * @return bool
     */
    private function getImgExtWithImageTypeConst($value)
    {
        $extArray = [
            IMAGETYPE_GIF => 'gif',
            IMAGETYPE_JPEG => 'jpeg',
            IMAGETYPE_PNG => 'png',
            IMAGETYPE_SWF => 'swf',
            IMAGETYPE_PSD => 'psd',
            IMAGETYPE_BMP => 'bmp',
            IMAGETYPE_TIFF_II => 'tiff',
            IMAGETYPE_TIFF_MM => 'tiff',
            IMAGETYPE_JPC => 'jpc',
            IMAGETYPE_JP2 => 'jp2',
            IMAGETYPE_JPX => 'jpx',
            IMAGETYPE_JB2 => 'jb2',
            IMAGETYPE_SWC => 'swc',
            IMAGETYPE_IFF => 'iff',
            IMAGETYPE_WBMP => 'wbmp',
            IMAGETYPE_XBM => 'xbm',
            IMAGETYPE_ICO => 'ico',
        ];

        if (isset($extArray[$value])) {

            return $extArray[$value];
        }

        return false;
    }
}

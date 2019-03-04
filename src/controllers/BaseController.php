<?php
/**
 * Created by PhpStorm.
 * User: francisco
 * Date: 2019-03-04
 * Time: 01:42
 */

namespace Controllers;

use Slim\Http\UploadedFile;

class BaseController
{

    /**
     * Moves the uploaded file to the upload directory and assigns it a unique name
     * to avoid overwriting an existing uploaded file.
     *
     * @param string $directory directory to which the file is moved
     * @param UploadedFile $uploadedFile
     * @return string Filename of moved file.
     * @throws \Exception
     */
    protected function moveUploadedFile($directory, UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param bool $couldBeEmpty
     * @return bool
     * @throws \Exception
     */
    protected function fileIsSent(UploadedFile $uploadedFile, $couldBeEmpty = false)
    {
        if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
            switch ($uploadedFile->getError()) {
                case UPLOAD_ERR_INI_SIZE: // Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini.
                    throw new \Exception('O tamanho do arquivo enviado é maior que o servidor pode receber. (Código: ' . UPLOAD_ERR_INI_SIZE . ')');

                case UPLOAD_ERR_FORM_SIZE: // Value: 2; The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
                    throw new \Exception('O tamanho do arquivo enviado é maior que o definido pelo formulário. (Código: ' . UPLOAD_ERR_FORM_SIZE . ')');

                case UPLOAD_ERR_PARTIAL: // Value: 3; The uploaded file was only partially uploaded.
                    throw new \Exception('O arquivo foi enviado parcialmente. (Código: ' . UPLOAD_ERR_PARTIAL . ')');

                case UPLOAD_ERR_NO_FILE: // Value: 4; No file was uploaded.
                    throw new \Exception('Nenhum arquivo foi enviado. (Código: ' . UPLOAD_ERR_NO_FILE . ')');

                case UPLOAD_ERR_NO_TMP_DIR: // Value: 6; Missing a temporary folder. Introduced in PHP 5.0.3.
                    throw new \Exception('Não foi encontrado o diretório temporário para o envio do arquivo. (Código: ' . UPLOAD_ERR_NO_TMP_DIR . ')');

                case UPLOAD_ERR_CANT_WRITE: // Value: 7; Failed to write file to disk. Introduced in PHP 5.1.0.
                    throw new \Exception('Falha ao escrever o arquivo no disco. (Código: ' . UPLOAD_ERR_CANT_WRITE . ')');

                case UPLOAD_ERR_EXTENSION: // Value: 8; A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help. Introduced in PHP 5.2.0.
                    throw new \Exception('Falha ao escrever o arquivo no disco. (Código: ' . UPLOAD_ERR_EXTENSION . ')');
            }

            throw new \Exception('Erro desconhecido ao enviar o arquivo. (Código: -1)');
        } elseif (((is_null($uploadedFile->getSize())) || ($uploadedFile->getSize() == 0)) && (!$couldBeEmpty)) {
            throw new \Exception('O arquivo não pode ser vazio. (Código: -1)');
        } else {
            // UPLOAD_ERR_OK: Value: 0; There is no error, the file uploaded with success.
            return true;
        }
    }

}
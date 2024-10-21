<?php

namespace app\services;

use app\services\BaseService;
use app\exceptions\FileMoveFailedException;
use app\exceptions\FileNotUploadedException;
use app\exceptions\FileSizeExceededException;
use app\exceptions\InvalidFileTypeException;
use app\models\LamaranModel;
use app\repositories\LamaranRepository;
use PDO;

class LamaranService extends BaseService
{
    protected static $instance;

    private function __construct($repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(
                LamaranRepository::getInstance()
            );
        }
        return self::$instance;
    }

    public function createLamaran($note, $cv_file, $video_file): LamaranModel | null
    {
        $uploadDir = 'uploads/';
        $maxCVSize = 2 * 1024 * 1024;
        $maxVideoSize = 100 * 1024 * 1024;

        $lamaran = new LamaranModel();

        // CV
        if ($cv_file['error'] === UPLOAD_ERR_OK){
            if ($cv_file['type'] == 'application/pdf'){
                if ($cv_file['size'] <= $maxCVSize){
                    $fileName = $cv_file['name'];
                    $destination = $uploadDir . basename($fileName);
                    $temporaryPath = $cv_file['tmp_name'];
                    if (move_uploaded_file($temporaryPath, $destination)) {
                        echo "CV File uploaded successfully: $fileName";
                    } else {
                        throw new FileMoveFailedException("Error saving CV file");
                    }
                } else {
                    throw new FileSizeExceededException("CV file exceeded 2MB size limit");
                }
            } else {
                throw new InvalidFileTypeException("CV file extension is not PDF");
            }
        } else {
            throw new FileNotUploadedException("No CV file uploaded or CV upload error");
        }

        // Video
        if ($video_file['error'] !== UPLOAD_ERR_NO_FILE){
            if ($video_file['error'] === UPLOAD_ERR_OK){
                if ($video_file['type'] == 'video/mp4'){
                    if ($video_file['size'] <= $maxVideoSize){
                        $fileName = $video_file['name'];
                        $destination = $uploadDir . basename($fileName);
                        $temporaryPath = $video_file['tmp_name'];
                        if (move_uploaded_file($temporaryPath, $destination)) {
                            echo "Video File uploaded successfully: $fileName";
                        } else {
                            throw new FileMoveFailedException("Error saving Video file");
                        }
                    } else {
                        throw new FileSizeExceededException("Video file exceeded 100MB size limit");
                    }
                } else {
                    throw new InvalidFileTypeException("Video file extension is not MP4");
                }
            } else {
                throw new FileNotUploadedException("Error occured while uploading Video");
            }
        }

        //Note
        $allowedTags = '<p><b><i><u><strong><em><br>';
        $sanitized_note = strip_tags($note, $allowedTags);
        $sanitized_note = htmlentities($sanitized_note, ENT_QUOTES, 'UTF-8');

        // TODO: save in database

        return $lamaran;
    }
}

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

    public function getLamaranByUser($user_id, $ordered = null) {
        $all_lamaran_data = $this->repository->getAllByUserID($user_id, $ordered);

        if ($all_lamaran_data) {
            $all_lamaran_model = [];
            foreach($all_lamaran_data as $lamaran_data){
                $lamaran_model = new LamaranModel();
                $lamaran_model->constructFromArray($lamaran_data);
                $all_lamaran_model[] = $lamaran_model;
            }

            return $all_lamaran_model;
        }

        return null;
    }

    public function getLamaranByLowonganID($lowongan_id, $ordered = null) {
        $all_lamaran_data = $this->repository->getAllByLowonganID($lowongan_id, $ordered);

        if ($all_lamaran_data) {
            $all_lamaran_model = [];
            foreach($all_lamaran_data as $lamaran_data){
                $lamaran_model = new LamaranModel();
                $lamaran_model->constructFromArray($lamaran_data);
                $all_lamaran_model[] = $lamaran_model;
            }

            return $all_lamaran_model;
        }

        return null;
    }

    public function createLamaran($note, $cv_file, $video_file, $lowongan_id): LamaranModel | null
    {
        $uploadDir = 'uploads/';
        $maxCVSize = 2 * 1024 * 1024;
        $maxVideoSize = 100 * 1024 * 1024;
        $cvSuccess = false;
        $videoSuccess = false;
        $noteSuccess = false;
        $cv_destination = '';
        $video_destination = '';

        // CV
        if ($cv_file['error'] === UPLOAD_ERR_OK){
            if ($cv_file['type'] == 'application/pdf'){
                if ($cv_file['size'] <= $maxCVSize){
                    $fileName = $cv_file['name'];
                    $cv_destination = $uploadDir . basename($fileName);
                    $temporaryPath = $cv_file['tmp_name'];
                    if (move_uploaded_file($temporaryPath, $cv_destination)) {
                        // echo "CV File uploaded successfully: $fileName";
                        $cvSuccess = true;
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
                        $video_destination = $uploadDir . basename($fileName);
                        $temporaryPath = $video_file['tmp_name'];
                        if (move_uploaded_file($temporaryPath, $video_destination)) {
                            // echo "Video File uploaded successfully: $fileName";
                            $videoSuccess = true;
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
        } else {
            $videoSuccess = true;
        }

        //Note
        $allowedTags = '<p><b><i><u><strong><em><br>';
        $sanitized_note = strip_tags($note, $allowedTags);
        $sanitized_note = htmlentities($sanitized_note, ENT_QUOTES, 'UTF-8');
        $noteSuccess = true;

        if (($cvSuccess && $videoSuccess) && $noteSuccess) {
            $lamaran = new LamaranModel();
            $lamaran
                ->set('user_id', $_SESSION["user_id"])
                ->set('lowongan_id', $lowongan_id)
                ->set('cv_path', $cv_destination)
                ->set('video_path', $video_destination)
                ->set('note', $note)
                ->set('status', 'waiting')
                ->set('status_reason', null);

            $id = $this->repository->insert($lamaran, array(
                'user_id' => PDO::PARAM_INT,
                'lowongan_id' => PDO::PARAM_INT,
                'cv_path' => PDO::PARAM_STR,
                'video_path' => PDO::PARAM_STR,
                'note' => PDO::PARAM_STR,
                'status' => PDO::PARAM_STR,
                'status_reason' => PDO::PARAM_STR,
            ));
        } 

        $response = $this->repository->getByLamaranID($id);
        $lamaran = new LamaranModel();
        $lamaran->constructFromArray($response);
        return $lamaran;
    }
}

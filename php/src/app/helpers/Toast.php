<?php
namespace app\helpers;

class Toast {
    public static function set($message, $type = 'success') {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $_SESSION['toast_data'] = [
            $type => $message
        ];
    }

    public static function setMultiple($data) {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $_SESSION['toast_data'] = $data;
    }

    public static function get() {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if (isset($_SESSION['toast_data'])) {
            $toast = $_SESSION['toast_data'];
            unset($_SESSION['toast_data']);
            return $toast;
        }
        return null;
    }

    public static function success($message) {
        self::set($message, 'success');
    }

    public static function error($message) {
        self::set($message, 'error');
    }

    public static function warning($message) {
        self::set($message, 'warning');
    }

    public static function help($message) {
        self::set($message, 'help');
    }
}
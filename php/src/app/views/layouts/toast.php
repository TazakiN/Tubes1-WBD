<!-- toast.php -->
<?php
function showToast($data) {
    $toasts = '';
    
    if (isset($data['help'])) {
        $toasts .= sprintf('
            <div class="toast-item help">
                <div class="toast help">
                    <label for="t-help" class="close"></label>
                    <h3>Help!</h3>
                    <p>%s</p>
                </div>
            </div>
        ', htmlspecialchars($data['help']));
    }
    
    if (isset($data['success'])) {
        $toasts .= sprintf('
            <div class="toast-item success">
                <div class="toast success">
                    <label for="t-success" class="close"></label>
                    <h3>Success!</h3>
                    <p>%s</p>
                </div>
            </div>
        ', htmlspecialchars($data['success']));
    }
    
    if (isset($data['warning'])) {
        $toasts .= sprintf('
            <div class="toast-item warning">
                <div class="toast warning">
                    <label for="t-warning" class="close"></label>
                    <h3>Warning!</h3>
                    <p>%s</p>
                </div>
            </div>
        ', htmlspecialchars($data['warning']));
    }
    
    if (isset($data['error'])) {
        $toasts .= sprintf('
            <div class="toast-item error">
                <div class="toast error">
                    <label for="t-error" class="close"></label>
                    <h3>Error!</h3>
                    <p>%s</p>
                </div>
            </div>
        ', htmlspecialchars($data['error']));
    }
    
    if ($toasts) {
        echo '
        <input type="checkbox" name="t-help" id="t-help">
        <input type="checkbox" name="t-success" id="t-success">
        <input type="checkbox" name="t-warning" id="t-warning">
        <input type="checkbox" name="t-error" id="t-error">
        <div class="toast-panel">
            ' . $toasts . '
        </div>';
    }
}
?>
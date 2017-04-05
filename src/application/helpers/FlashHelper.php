<?php

class FlashHelper {

    public function create($message, $class = 'success') {
        if(empty($_SESSION['flash_message'])) {
            $_SESSION['flash_messages'] = [];
        }

        if(!empty($name) && !empty($message)) {
            $_SESSION['flash_messages'][] = [
                'class' => $class,
                'message' => $message
            ];
        }
    }

    public function display() {
        if(!empty($_SESSION['flash_messages'])) {
            foreach($_SESSION['flash_messages'] as $key => $flash) {
                echo '<div class="' . $flash['class'] . '">' . $flash['message'] . '</div>';
                unset($_SESSION['flash_messages'][$key]);
            }
        }
    }

    public function hasMessages() {
        return count($_SESSION['flash_messages']) > 0;
    }

}
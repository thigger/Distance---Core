<?php namespace Core\Repositories\Email;

class LaravelEmail implements EmailInterface {

    public function to($email) {

    }

    public function from($email) {

    }

    public function cc($email) {

    }

    public function bcc($email) {

    }

    public function template($templateName, $data) {

    }

    public function send() {
        dd('send!');
    }

}
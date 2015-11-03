<?php

class Csrf {
    /*
     * Verifica que exista un token_id, sino lo crea
     */

    public function update($token_id) {
        if (isset($_SESSION['token_id'])) {
            Session::destroy($token_id);
            Session::destroy('token_id');
        }
    }

    public function get_token_id() {
        if (isset($_SESSION['token_id'])) {
            return $_SESSION['token_id'];
        } else {
            $token_id = $this->random(10);
            $_SESSION['token_id'] = $token_id;
            return $token_id;
        }
    }

    /*
     * Verifica que exista un token, sino lo crea
     */

    public function get_token($token_id) {
        if (isset($_SESSION[$token_id])) {
            return $_SESSION[$token_id];
        } else {
            $token = hash('sha256', $this->random(500));
            $_SESSION[$token_id] = $token;
            return $token;
        }
    }

    /*
     * Verifica que los valores del token_id y el token sean validos
     */

    public function check_valid($method) {
        if ($method == 'post' || $method == 'get') {
            $post = $_POST;
            $get = $_GET;
            $token_id = $this->get_token_id();
            $asd = ${$method}[$token_id];
            if (isset(${$method}[$token_id]) && (${$method}[$token_id] == $this->get_token($token_id))) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
     * Genera un numero aleatorio usando ficheros de linux para mejorar la entropia
     */

    private function random($len) {
        if (@is_readable('/dev/urandom')) {
            $f = fopen('/dev/urandom', 'r');
            $urandom = fread($f, $len);
            fclose($f);
        }

        $return = '';
        for ($i = 0; $i < $len; ++$i) {
            if (!isset($urandom)) {
                if ($i % 2 == 0)
                    mt_srand(time() % 2147 * 1000000 + (double) microtime() * 1000000);
                $rand = 48 + mt_rand() % 64;
            } else
                $rand = 48 + ord($urandom[$i]) % 64;

            if ($rand > 57)
                $rand+=7;
            if ($rand > 90)
                $rand+=6;

            if ($rand == 123)
                $rand = 52;
            if ($rand == 124)
                $rand = 53;
            $return.=chr($rand);
        }
        return $return;
    }

}

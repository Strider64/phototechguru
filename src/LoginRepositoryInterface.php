<?php

namespace PhotoTech;

interface LoginRepositoryInterface
{
    public function verify_credentials($username, $password): bool;
    public function store_token_in_database($user_id, $token): bool;
    public function check_login_token(): bool;
    public function show_logoff_nav_button(): void;
    public function logoff(): void;
}